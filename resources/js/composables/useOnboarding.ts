import { ref, computed, watch, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from '../components/ui/toast';
import debounce from 'lodash/debounce';

export type VenueType = 'hotel' | 'sports' | 'restaurant' | 'coworking' | 'cultural' | 'entertainment';

interface SocialMedia {
    instagram?: string | null;
    facebook?: string | null;
    twitter?: string | null;
}

interface OperatingHours {
    monday?: { open: string; close: string; isOpen: boolean };
    tuesday?: { open: string; close: string; isOpen: boolean };
    wednesday?: { open: string; close: string; isOpen: boolean };
    thursday?: { open: string; close: string; isOpen: boolean };
    friday?: { open: string; close: string; isOpen: boolean };
    saturday?: { open: string; close: string; isOpen: boolean };
    sunday?: { open: string; close: string; isOpen: boolean };
}

export type OnboardingFormData = {
    [key: string]: any;
    name: string;
    type: VenueType;
    address: string;
    city: string;
    state: string;
    country: string;
    phone: string;
    website?: string | null;
    description: string;
    logo?: string | null;
    socialMedia?: SocialMedia;
    operatingHours?: OperatingHours;
}

interface ValidationError {
    field: string;
    message: string;
}

const fieldLabels: Record<string, string> = {
    name: 'nombre del negocio',
    type: 'tipo de negocio',
    address: 'dirección',
    city: 'ciudad',
    state: 'provincia/estado',
    country: 'país',
    phone: 'teléfono',
    website: 'sitio web',
    description: 'descripción',
    logo: 'logo',
    'socialMedia.instagram': 'Instagram',
    'socialMedia.facebook': 'Facebook',
    'socialMedia.twitter': 'Twitter',
};

export function useOnboarding(initialData: Partial<OnboardingFormData> = {}) {
    const currentStep = ref(1);
    const totalSteps = 4;
    const isSubmitting = ref(false);
    const autoSaveStatus = ref<'idle' | 'saving' | 'saved' | 'error'>('idle');
    const previewMode = ref(false);
    const formTouched = ref(false);
    const formProgress = ref(0);
    const isLoading = ref(false);
    const unfinishedSteps = ref<number[]>([]);
    
    const shouldValidateOnChange = ref(false);
    const fieldModified = ref<Record<string, boolean>>({});

    const defaultData: OnboardingFormData = {
        name: initialData.name || '',
        type: initialData.type || 'hotel',
        address: initialData.address || '',
        city: initialData.city || '',
        state: initialData.state || '',
        country: initialData.country || '',
        phone: initialData.phone || '',
        website: initialData.website || null,
        description: initialData.description || '',
        logo: initialData.logo || null,
        socialMedia: initialData.socialMedia || {
            instagram: null,
            facebook: null,
            twitter: null,
        },
        operatingHours: initialData.operatingHours || {
            monday: { open: '09:00', close: '18:00', isOpen: true },
            tuesday: { open: '09:00', close: '18:00', isOpen: true },
            wednesday: { open: '09:00', close: '18:00', isOpen: true },
            thursday: { open: '09:00', close: '18:00', isOpen: true },
            friday: { open: '09:00', close: '18:00', isOpen: true },
            saturday: { open: '09:00', close: '14:00', isOpen: true },
            sunday: { open: '00:00', close: '00:00', isOpen: false },
        },
    };

    const form = useForm<OnboardingFormData & { [key: string]: any }>(defaultData);

    // Definir un tipo estricto para los campos que pueden estar en los pasos
    type StepField = keyof OnboardingFormData;
    
    // Definir el tipo específico para el objeto stepValidation
    type StepValidation = {
        [key: number]: readonly StepField[];
    };
    
    const stepValidation: StepValidation = {
        1: ['type'] as const,
        2: ['name', 'phone', 'description', 'website', 'logo'] as const,
        3: ['address', 'city', 'state', 'country'] as const,
        4: ['socialMedia', 'operatingHours'] as const,
    };

    const validateField = (fieldName: string, value: any): ValidationError | null => {
        if (!value && ['website', 'logo', 'socialMedia', 'operatingHours'].includes(fieldName)) {
            return null;
        }

        switch (fieldName) {
            case 'name':
                if (!value || value.length < 3) {
                    return { field: fieldName, message: 'El nombre debe tener al menos 3 caracteres' };
                }
                if (value.length > 255) {
                    return { field: fieldName, message: 'El nombre no puede exceder los 255 caracteres' };
                }
                break;

            case 'address':
                if (!value || value.length < 5) {
                    return { field: fieldName, message: 'La dirección debe tener al menos 5 caracteres' };
                }
                if (value.length > 255) {
                    return { field: fieldName, message: 'La dirección no puede exceder los 255 caracteres' };
                }
                break;

            case 'city':
            case 'state':
            case 'country':
                if (!value || value.length < 2) {
                    return { field: fieldName, message: `${fieldLabels[fieldName]} debe tener al menos 2 caracteres` };
                }
                if (value.length > 100) {
                    return { field: fieldName, message: `${fieldLabels[fieldName]} no puede exceder los 100 caracteres` };
                }
                break;

            case 'phone':
                if (!value || !/^[+]?[(]?[0-9]{1,4}[)]?[-\s.0-9]{7,}$/.test(value)) {
                    return { field: fieldName, message: 'El formato del teléfono no es válido (mínimo 8 dígitos)' };
                }
                break;

            case 'website':
                if (value) {
                    try {
                        new URL(value.startsWith('http') ? value : `https://${value}`);
                    } catch {
                        return { field: fieldName, message: 'URL inválida' };
                    }
                }
                break;

            case 'description':
                if (!value || value.length < 10) {
                    return { field: fieldName, message: 'La descripción debe tener al menos 10 caracteres' };
                }
                if (value.length > 1000) {
                    return { field: fieldName, message: 'La descripción no puede exceder los 1000 caracteres' };
                }
                break;

            case 'socialMedia':
                if (value) {
                    const socialMedia = value as SocialMedia;
                    for (const [network, url] of Object.entries(socialMedia)) {
                        if (url) {
                            try {
                                new URL(url);
                            } catch {
                                return { field: `socialMedia.${network}`, message: `URL de ${network} inválida` };
                            }
                        }
                    }
                }
                break;
        }

        return null;
    };

    const validateFields = (fields: readonly string[]): ValidationError[] => {
        const errors: ValidationError[] = [];
        const formData = form.data();

        Array.from(fields).forEach(field => {
            const error = validateField(field, formData[field]);
            if (error) {
                errors.push(error);
            }
        });

        return errors;
    };

    const markFieldAsModified = (fieldName: string) => {
        fieldModified.value[fieldName] = true;
    };

    const shouldValidateField = (fieldName: string): boolean => {
        return shouldValidateOnChange.value || fieldModified.value[fieldName] === true;
    };

    const handleInputBlur = (fieldName: string) => {
        markFieldAsModified(fieldName);
        const formData = form.data();
        const error = validateField(fieldName, formData[fieldName as keyof OnboardingFormData]);
        
        if (error) {
            form.setError(error.field, error.message);
        } else {
            form.clearErrors(fieldName);
        }
    };

    const debouncedValidation = debounce(() => {
        if (!formTouched.value) return;

        Object.keys(fieldModified.value).forEach(fieldName => {
            if (fieldModified.value[fieldName]) {
                handleInputBlur(fieldName);
            }
        });
        
        updateProgress();
        autoSave();
    }, 500);

    watch(() => form.data(), (newVal, oldVal) => {
        if (!formTouched.value) {
            formTouched.value = true;
        }
        
        Object.keys(newVal).forEach(key => {
            if (JSON.stringify(newVal[key]) !== JSON.stringify(oldVal[key])) {
                markFieldAsModified(key);
            }
        });
        
        debouncedValidation();
    }, { deep: true });

    const updateProgress = () => {
        const fields = form.data();
        let filled = 0;
        let total = 0;

        const requiredFields = ['name', 'type', 'address', 'city', 'state', 'country', 'phone', 'description'];
        requiredFields.forEach(field => {
            total++;
            if (fields[field as keyof OnboardingFormData] && String(fields[field as keyof OnboardingFormData]).trim() !== '') {
                filled++;
            }
        });

        const optionalFields = ['website', 'logo'];
        optionalFields.forEach(field => {
            if (fields[field as keyof OnboardingFormData]) {
                filled++;
            }
            total++;
        });

        formProgress.value = Math.round((filled / total) * 100);
    };

    const completionProgress = computed(() => formProgress.value);

    const autoSave = debounce(async () => {
        if (!formTouched.value) return;
        
        try {
            autoSaveStatus.value = 'saving';
            
            const dataToSave = {
                ...form.data(),
                lastSaved: new Date().toISOString(),
            };
            
            localStorage.setItem('onboardingFormData', JSON.stringify(dataToSave));
            
            autoSaveStatus.value = 'saved';
            setTimeout(() => {
                if (autoSaveStatus.value === 'saved') {
                    autoSaveStatus.value = 'idle';
                }
            }, 2000);
        } catch (error) {
            console.error('Error al guardar datos:', error);
            autoSaveStatus.value = 'error';
            toast({
                title: "Error al guardar",
                description: "No se pudieron guardar los cambios automáticamente",
                variant: "destructive",
            });
        }
    }, 1000);

    onMounted(() => {
        try {
            const savedData = localStorage.getItem('onboardingFormData');
            if (savedData) {
                const parsedData = JSON.parse(savedData);
                
                // Filtrar solo las propiedades que existen en el formulario
                const validFormKeys = Object.keys(form.data());
                const filteredData = Object.fromEntries(
                    Object.entries(parsedData).filter(([key]) => validFormKeys.includes(key))
                );
                
                // Verificar la estructura básica antes de validar
                if (typeof filteredData === 'object' && filteredData !== null) {
                    try {
                        // Validar solo los campos que existen y tienen valor
                        const fieldsToValidate = Object.keys(filteredData).filter(key => 
                            filteredData[key] !== null && 
                            filteredData[key] !== undefined && 
                            filteredData[key] !== ''
                        );
                        
                        const errors = validateFields(fieldsToValidate);
                        
                        if (errors.length === 0) {
                            // Cargar datos campo por campo para evitar problemas
                            Object.entries(filteredData).forEach(([key, value]) => {
                                if (key in form.data() && typeof form.setData === 'function') {
                                    form.setData(key, value);
                                }
                            });
                            
                            updateProgress();
                            
                            toast({
                                title: "Datos recuperados",
                                description: "Se han cargado tus datos guardados anteriormente",
                            });
                        } else {
                            console.warn('Datos guardados con errores de validación:', errors);
                            // Opcionalmente, intentar cargar datos parciales que sí son válidos
                            toast({
                                title: "Advertencia",
                                description: "Algunos datos guardados no son válidos y fueron ignorados",
                                variant: "warning",
                            });
                        }
                    } catch (error) {
                        console.warn('Error en validación de datos guardados:', error);
                        toast({
                            title: "Datos descartados",
                            description: "Los datos guardados previamente no son válidos y fueron descartados",
                            variant: "warning",
                        });
                        localStorage.removeItem('onboardingFormData');
                    }
                } else {
                    throw new Error('Formato de datos inválido');
                }
            }
        } catch (error) {
            console.error('Error al recuperar datos guardados:', error);
            localStorage.removeItem('onboardingFormData');
            toast({
                title: "Error",
                description: "No se pudieron recuperar tus datos guardados",
                variant: "destructive",
            });
        }
    });

    const validateCurrentStep = (showToast = true): boolean => {
        shouldValidateOnChange.value = true;
        // Necesitamos una aseveración de tipo aquí para que TypeScript entienda que currentStep.value es una clave válida
        const step = currentStep.value as keyof typeof stepValidation;
        const currentFields = stepValidation[step];
        
        // Convertir campos a array para poder iterarlos
        Array.from(currentFields).forEach(field => {
            if (field === 'socialMedia') {
                ['instagram', 'facebook', 'twitter'].forEach(social => {
                    form.clearErrors(`socialMedia.${social}`);
                });
            } else if (field === 'operatingHours') {
                ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                    form.clearErrors(`operatingHours.${day}`);
                });
            } else {
                form.clearErrors(field);
            }
        });
     
        // Convertimos explícitamente currentFields a un array de strings para evitar problemas de tipo
        const fieldArray = Array.from(currentFields).map(field => field as string);
        const errors = validateFields(fieldArray);
        
        if (errors.length > 0) {
            errors.forEach(error => {
                form.setError(error.field, error.message);
            });
            
            if (showToast) {
                toast({
                    title: "Error de validación",
                    description: "Por favor completa todos los campos requeridos correctamente",
                    variant: "destructive",
                });
            }
            
            return false;
        }
        
        return true;
     };

    const goToStep = (step: number) => {
        if (step >= 1 && step <= totalSteps) {
            if (step > currentStep.value) {
                if (validateCurrentStep()) {
                    currentStep.value = step;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    shouldValidateOnChange.value = false;
                }
            } else {
                currentStep.value = step;
                window.scrollTo({ top: 0, behavior: 'smooth' });
                shouldValidateOnChange.value = false;
            }
        }
    };

    const nextStep = () => goToStep(currentStep.value + 1);
    const prevStep = () => goToStep(currentStep.value - 1);

  /**
 * Maneja la carga y procesamiento de imágenes de logo con validaciones
 * @param event Evento del input de archivos
 */
const handleLogoUpload = async (event: Event): Promise<void> => {
  // Constantes de configuración
  const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB en bytes
  const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/svg+xml', 'image/webp'];
  
  // Obtener archivo con seguridad de tipos
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  // Limpiar input
  const resetInput = () => { target.value = ''; };
  
  // Validar existencia del archivo
  if (!file) {
    return;
  }
  
  // Validar tamaño del archivo
  if (file.size > MAX_FILE_SIZE) {
    toast({
      title: "Archivo no permitido",
      description: "La imagen excede el límite de 2MB permitido.",
      variant: "destructive",
    });
    resetInput();
    return;
  }
  
  // Validar tipo de archivo
  if (!ALLOWED_MIME_TYPES.includes(file.type)) {
    toast({
      title: "Formato no soportado",
      description: "Por favor selecciona un formato de imagen válido (JPEG, PNG, SVG o WebP).",
      variant: "destructive",
    });
    resetInput();
    return;
  }
  
  try {
    // Activar indicador de carga
    isLoading.value = true;
    
    // Procesar imagen a Base64
    const base64 = await fileToBase64(file);
    
    // Actualizar estado del formulario - Corregido el método según el error
    // Dependiendo del formulario que estás usando, una de estas opciones debería funcionar:
    
    // Opción 1: Si usas un form reactivo de Vue o similar
    form.logo = base64;
    
    // Opción 2: Si usas FormData nativo
    // formData.append('logo', base64);
    
    // Opción 3: Si usas alguna librería como vee-validate, react-hook-form, etc.
    // form.setValue('logo', base64);
    
    // Opción 4: Para corregir el error específico
    // Si 'setData' es un método personalizado que debería estar disponible
    if (typeof form.setData === 'function') {
      form.setData('logo', base64);
    } else {
      // Fallback si el método no existe
      form.logo = base64;
    }
    
    // Guardar cambios
    await autoSave();
    
    // Notificar éxito
    toast({
      title: "Logo actualizado",
      description: "La imagen se ha cargado correctamente.",
      variant: "success",
    });
  } catch (error) {
    // Manejo detallado de errores
    console.error('[Logo Upload]', error);
    
    const errorMessage = error instanceof Error 
      ? error.message 
      : "Ocurrió un error desconocido";
    
    toast({
      title: "Error al procesar imagen",
      description: `No se pudo cargar el logo: ${errorMessage}`,
      variant: "destructive",
    });
    
    resetInput();
  } finally {
    // Desactivar indicador de carga
    isLoading.value = false;
  }
};

    const submit = async () => {
        shouldValidateOnChange.value = true;
        
        Object.keys(form.data()).forEach(key => {
            markFieldAsModified(key);
        });
        
        const errors = validateFields(Object.keys(form.data()));
        if (errors.length === 0) {
            isSubmitting.value = true;
            form.post(route('onboarding.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast({
                        title: "¡Configuración completada!",
                        description: "Tu negocio ha sido configurado correctamente.",
                        variant: "success",
                    });
                    localStorage.removeItem('onboardingFormData');
                },
                onError: (errors) => {
                    handleServerErrors(errors);
                },
                onFinish: () => {
                    isSubmitting.value = false;
                }
            });
        } else {
            handleValidationErrors(errors);
        }
    };

    const handleServerErrors = (errors: Record<string, string>) => {
        const errorFields = Object.keys(errors);
        let errorStep = 1;
     
        for (let step = 1; step <= totalSteps; step++) {
            const stepFields = stepValidation[step as keyof typeof stepValidation];
            for (const field of stepFields) {
                if (errorFields.includes(field as string) || 
                    (field === 'socialMedia' && errorFields.some(f => f.startsWith('socialMedia.'))) || 
                    (field === 'operatingHours' && errorFields.some(f => f.startsWith('operatingHours.')))) {
                    errorStep = step;
                    break;
                }
            }
            if (errorStep !== 1) break;
        }
     
        if (errorStep !== currentStep.value) {
            currentStep.value = errorStep;
        }
     
        toast({
            title: "Error de validación",
            description: "Por favor verifica los campos marcados en rojo",
            variant: "destructive",
        });
     };

    const handleValidationErrors = (errors: ValidationError[]) => {
        let errorStep = 1;
        
        errors.forEach(error => {
            const field = error.field.split('.')[0];
            form.setError(error.field, error.message);
            
            for (let step = 1; step <= totalSteps; step++) {
                const stepFields = stepValidation[step as keyof typeof stepValidation];
                if (Array.from(stepFields).includes(field as StepField)) {
                    errorStep = step;
                    break;
                }
            }
        });
        
        if (errorStep !== currentStep.value) {
            currentStep.value = errorStep;
        }
        
        toast({
            title: "Error de validación",
            description: "Por favor completa todos los campos requeridos",
            variant: "destructive",
        });
    };

    const fileToBase64 = (file: File): Promise<string> => {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result as string);
            reader.onerror = error => reject(error);
        });
    };

    const togglePreview = () => {
        if (!previewMode.value) {
            const isValid = validateCurrentStep(false);
            if (!isValid) {
                toast({
                    title: "Atención",
                    description: "Hay campos con errores. La vista previa podría no mostrar toda la información correctamente.",
                    variant: "warning",
                });
            }
        }
        
        previewMode.value = !previewMode.value;
    };

    const isFieldComplete = (field: string): boolean => {
        const formData = form.data();
        if (field.includes('.')) {
            const [parent, child] = field.split('.');
            return !!formData[parent as keyof OnboardingFormData] && !!(formData[parent as keyof OnboardingFormData] as any)[child];
        }
        return !!formData[field as keyof OnboardingFormData] && (typeof formData[field as keyof OnboardingFormData] !== 'string' || (formData[field as keyof OnboardingFormData] as string).trim() !== '');
    };

    const isStepComplete = (step: number): boolean => {
        if (step > totalSteps || step < 1) return false;
        
        const stepFields = stepValidation[step as keyof typeof stepValidation];
        
        // Verifica si algún campo obligatorio está incompleto
        return !Array.from(stepFields).some(field => {
            // Ignorar campos opcionales en la verificación
            if (field === 'website' || field === 'logo' || field === 'socialMedia') {
                return false;
            }
            
            const formData = form.data();
            const value = formData[field as keyof OnboardingFormData];
            
            // Un campo está incompleto si:
            // 1. Es undefined o null
            // 2. Es un string vacío después de quitar espacios
            const isEmpty = 
                value === undefined || 
                value === null || 
                (typeof value === 'string' && value.trim() === '');
                
            return isEmpty; // Retorna true si el campo está vacío (incompleto)
        });
    };

    return {
        form,
        currentStep,
        totalSteps,
        isSubmitting,
        autoSaveStatus,
        previewMode,
        completionProgress,
        isLoading,
        unfinishedSteps,
        formTouched,
        nextStep,
        prevStep,
        goToStep,
        handleLogoUpload,
        submit,
        togglePreview,
        isFieldComplete,
        isStepComplete,
        validateCurrentStep,
        fieldLabels,
        handleInputBlur,
        markFieldAsModified,
        shouldValidateField,
    };
}