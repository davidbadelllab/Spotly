<template>
  <div class="space-y-6">
    <TransitionGroup name="step" tag="div">
      <!-- Paso 1: Tipo de Negocio -->
      <div v-if="step === 1" key="step-1" class="space-y-6">
        <h2 class="text-2xl font-semibold mb-6 flex items-center">
          <span class="mr-2">Selecciona el tipo de tu negocio</span>
        </h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <TransitionGroup name="card">
            <Card v-for="(type, key) in venueTypes" :key="key" 
                  :class="[
                    'cursor-pointer transition-all hover:border-primary hover:shadow-md',
                    form.type === key ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : '',
                  ]" 
                  @click="setType(key)">
              <CardHeader>
                <div class="flex items-center gap-3">
                  <div class="p-2 rounded-lg bg-primary/10">
                    <!-- Placeholder en lugar de VenueTypeIcon -->
                    <div class="w-6 h-6 bg-primary/20 rounded-full"></div>
                  </div>
                  <CardTitle>{{ type.name }}</CardTitle>
                </div>
                <CardDescription>{{ type.description }}</CardDescription>
              </CardHeader>
              <CardContent v-if="type.benefits?.length">
                <ul class="text-sm space-y-1 mt-3">
                  <li v-for="(benefit, i) in type.benefits.slice(0, 3)" :key="i"
                      class="flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="mt-0.5 text-green-500">
                      <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <span class="text-muted-foreground">{{ benefit }}</span>
                  </li>
                </ul>
              </CardContent>
            </Card>
          </TransitionGroup>
        </div>
        <InputError v-if="errors.type" :message="errors.type" />
      </div>
      <!-- Paso 2: Información Básica -->
      <div v-if="step === 2" key="step-2" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle>Información Básica</CardTitle>
            <CardDescription>
              Completa la información principal de tu negocio
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <Label for="name" class="flex items-center">
                  Nombre de la Empresa
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="name" 
                  v-model="form.name" 
                  type="text" 
                  required
                  placeholder="Ej. Hotel Plaza Central" 
                  @blur="handleFieldBlur('name')"
                />
                <InputError :message="errors.name" class="mt-1" />
              </div>

              <div class="space-y-2">
                <Label for="phone" class="flex items-center">
                  Teléfono
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="phone" 
                  v-model="form.phone" 
                  type="tel" 
                  required
                  placeholder="Ej. +34 912 345 678" 
                  @blur="handleFieldBlur('phone')"
                />
                <InputError :message="errors.phone" class="mt-1" />
              </div>

              <div class="space-y-2">
                <Label for="website" class="flex items-center">
                  Sitio Web
                  <span class="text-muted-foreground text-xs ml-2">(Opcional)</span>
                </Label>
                <div class="relative">
                  <Input 
                    id="website" 
                    v-model="form.website" 
                    type="url"
                    placeholder="https://tunegocio.com" 
                    class="pl-10"
                    @blur="handleFieldBlur('website')"
                  />
                  <div class="absolute left-3 top-2.5 w-4 h-4 text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="2" y1="12" x2="22" y2="12"></line>
                      <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                  </div>
                </div>
                <InputError :message="errors.website" class="mt-1" />
              </div>

              <div class="md:col-span-2 space-y-2">
                <Label for="description" class="flex items-center">
                  Descripción
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Textarea 
                  id="description" 
                  v-model="form.description" 
                  required 
                  :rows="4"
                  placeholder="Describe tu negocio para tus clientes..."
                  class="resize-y min-h-[100px]"
                  @blur="handleFieldBlur('description')"
                />
                <div class="flex justify-between text-xs text-muted-foreground">
                  <span>Describe los puntos fuertes de tu negocio</span>
                  <span :class="{'text-red-500': form.description && form.description.length > 500}">
                    {{ (form.description || '').length }} / 500 caracteres
                  </span>
                </div>
                <InputError :message="errors.description" class="mt-1" />
              </div>
              
              <div class="md:col-span-2 space-y-2">
                <Label for="logo" class="flex items-center">
                  Logo
                  <span class="text-muted-foreground text-xs ml-2">(Opcional)</span>
                </Label>
                <div class="flex items-center space-x-4">
                  <div v-if="form.logo" class="w-16 h-16 rounded-md overflow-hidden bg-muted flex items-center justify-center">
                    <img :src="form.logo" alt="Logo" class="w-full h-full object-contain" />
                  </div>
                  <div v-else class="w-16 h-16 rounded-md bg-muted flex items-center justify-center text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                      <circle cx="9" cy="9" r="2" />
                      <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                    </svg>
                  </div>
                  <div>
                    <input type="file" id="logoFileInput" class="hidden" @change="onLogoUpload" accept="image/*" />
                    <label for="logoFileInput" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background border border-input hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 bg-background text-secondary-foreground shadow-sm cursor-pointer">
                      Subir Logo
                    </label>
                    <p class="text-xs text-muted-foreground mt-1">
                      Formatos: JPG, PNG. Máximo 2MB
                    </p>
                  </div>
                </div>
                <InputError :message="errors.logo" class="mt-1" />
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Paso 3: Ubicación -->
      <div v-if="step === 3" key="step-3" class="space-y-6">
        <Card>
          <CardHeader>
            <CardTitle>Ubicación</CardTitle>
            <CardDescription>
              Agrega la dirección de tu negocio para que los clientes puedan encontrarte
            </CardDescription>
          </CardHeader>
          <CardContent class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="md:col-span-2 space-y-2">
                <Label for="address" class="flex items-center">
                  Dirección
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="address" 
                  v-model="form.address" 
                  type="text" 
                  required
                  placeholder="Calle, número, piso..."
                  @blur="handleFieldBlur('address')"
                />
                <InputError :message="errors.address" class="mt-1" />
              </div>

              <div class="space-y-2">
                <Label for="city" class="flex items-center">
                  Ciudad
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="city" 
                  v-model="form.city" 
                  type="text" 
                  required
                  @blur="handleFieldBlur('city')"
                />
                <InputError :message="errors.city" class="mt-1" />
              </div>

              <div class="space-y-2">
                <Label for="state" class="flex items-center">
                  Provincia/Estado
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="state" 
                  v-model="form.state" 
                  type="text" 
                  required
                  @blur="handleFieldBlur('state')"
                />
                <InputError :message="errors.state" class="mt-1" />
              </div>

              <div class="space-y-2">
                <Label for="country" class="flex items-center">
                  País
                  <span class="text-red-500 ml-1">*</span>
                </Label>
                <Input 
                  id="country" 
                  v-model="form.country" 
                  type="text" 
                  required
                  @blur="handleFieldBlur('country')"
                />
                <InputError :message="errors.country" class="mt-1" />
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </TransitionGroup>

    <!-- Navegación entre pasos -->
    <div class="flex justify-between mt-6">
      <Button v-if="step > 1" type="button" variant="outline" @click="onPrev">
        Anterior
      </Button>
      <div v-else></div>

      <Button v-if="!isLastStep" type="button" @click="onNext" class="flex items-center gap-2">
        Siguiente
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="5" y1="12" x2="19" y2="12"></line>
          <polyline points="12 5 19 12 12 19"></polyline>
        </svg>
      </Button>

      <Button v-else type="submit" :disabled="isSubmitting" class="flex items-center gap-2" @click="onSubmit">
        <span v-if="isSubmitting">
          <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Guardando...
        </span>
        <span v-else>Completar Configuración</span>
      </Button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription, Button, Input, Label, Textarea } from '../ui';
import InputError from '../InputError.vue';

interface VenueTypeInfo {
  name: string;
  description: string;
  icon: string;
  benefits: string[];
}

interface Props {
  step: number;
  form: Record<string, any>;
  errors: Record<string, string>;
  venueTypes: Record<string, VenueTypeInfo>;
  onNext: () => void;
  onPrev: () => void;
  onSubmit: () => void;
  isSubmitting?: boolean;
  handleLogoUpload?: (event: Event) => void;
  handleFieldBlur?: (field: string) => void;
}

const props = withDefaults(defineProps<Props>(), {
  isSubmitting: false,
  handleLogoUpload: undefined,
  handleFieldBlur: undefined,
});

const isLastStep = computed(() => props.step === 3);

// Manejar la carga del logo
const onLogoUpload = (event: Event) => {
  if (props.handleLogoUpload) {
    props.handleLogoUpload(event);
  }
};

// Manejar el blur de campos para validación
const handleFieldBlur = (field: string) => {
  if (props.handleFieldBlur) {
    props.handleFieldBlur(field);
  }
};

// Establecer el tipo de negocio - CORREGIDO
const setType = (type: string) => {
  // Accede a form a través de props en lugar de directamente
  props.form.type = type;
  
  // También validar el campo al seleccionar un tipo
  if (props.handleFieldBlur) {
    props.handleFieldBlur('type');
  }
};
</script>

<style scoped>
.card-enter-active,
.card-leave-active {
  transition: all 0.3s ease;
}

.card-enter-from,
.card-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

.step-enter-active,
.step-leave-active {
  transition: all 0.3s ease-out;
}

.step-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.step-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}
</style>