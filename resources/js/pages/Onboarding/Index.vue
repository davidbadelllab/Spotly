<template>
  <OnboardingLayout title="Bienvenido a Spotly" description="Configura tu negocio para empezar a recibir reservas">
    <StepIndicator
      :current-step="currentStep"
      :total-steps="totalSteps"
      :progress="completionProgress"
      auto-save-status="idle"
    />
    
    <form @submit.prevent="submit" class="space-y-8">
      <StepForm
        :step="currentStep"
        :form="form"
        :errors="form.errors"
        :venue-types="venueTypes"
        :is-submitting="isSubmitting"
        :handle-logo-upload="handleLogoUpload"
        :handle-field-blur="handleInputBlur"
        :on-next="nextStep"
        :on-prev="prevStep"
        :on-submit="submit"
      />
      
      <Alert v-if="completionProgress < 100" class="border-amber-200 bg-amber-50">
        <AlertDescription>
          Algunos campos opcionales están incompletos. Puedes editarlos más tarde desde la configuración.
        </AlertDescription>
      </Alert>
    </form>
  </OnboardingLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import OnboardingLayout from '../../layouts/OnboardingLayout.vue';
import { Alert, AlertDescription } from '../../components/ui';
import { useOnboarding } from '../../composables/useOnboarding';
import StepIndicator from '../../components/onboarding/StepIndicator.vue';
import StepForm from '../../components/onboarding/StepForm.vue';

// Datos de tipos de negocio
const venueTypes = {
  hotel: {
    name: 'Hotel / Alojamiento',
    description: 'Hoteles, hostales, apartamentos y alojamientos turísticos',
    icon: 'hotel',
    benefits: [
      'Sistema de reservas de habitaciones',
      'Gestión de disponibilidad por fechas',
      'Procesamiento de pagos y depósitos',
      'Gestión de servicios adicionales'
    ]
  },
  restaurant: {
    name: 'Restaurante / Bar',
    description: 'Restaurantes, cafeterías, bares y locales de comida',
    icon: 'restaurant',
    benefits: [
      'Reserva de mesas por franjas horarias',
      'Gestión de menús y carta digital',
      'Control de ocupación en tiempo real',
      'Reservas para eventos especiales'
    ]
  },
  sports: {
    name: 'Instalación Deportiva',
    description: 'Canchas, pistas, gimnasios y centros deportivos',
    icon: 'sports',
    benefits: [
      'Reserva de pistas por horas',
      'Sistema multi-deporte configurable',
      'Gestión de instructores y clases',
      'Alquiler de equipamiento deportivo'
    ]
  },
  coworking: {
    name: 'Coworking / Oficinas',
    description: 'Espacios de trabajo compartido, salas de reuniones y oficinas',
    icon: 'coworking',
    benefits: [
      'Reserva de puestos de trabajo',
      'Gestión de salas de reuniones',
      'Facturación por uso o suscripción',
      'Control de accesos'
    ]
  }
};

// Inicializar el estado del formulario con todos los datos necesarios
const {
  form,
  currentStep,
  totalSteps,
  isSubmitting,
  completionProgress,
  nextStep,
  prevStep,
  handleLogoUpload,
  submit,
  handleInputBlur, // Asegúrate de exportar esto desde useOnboarding.ts
  validateWebsite, // Asegúrate de exportar esto desde useOnboarding.ts
} = useOnboarding({
  name: '',
  type: 'hotel',
  address: '',
  city: '',
  state: '',
  country: '',
  phone: '',
  website: null,
  description: '',
  logo: null,
  socialMedia: {
    instagram: '',
    facebook: '',
    twitter: ''
  },
  operatingHours: {
    monday: { open: '09:00', close: '18:00', isOpen: true },
    tuesday: { open: '09:00', close: '18:00', isOpen: true },
    wednesday: { open: '09:00', close: '18:00', isOpen: true },
    thursday: { open: '09:00', close: '18:00', isOpen: true },
    friday: { open: '09:00', close: '18:00', isOpen: true },
    saturday: { open: '09:00', close: '14:00', isOpen: true },
    sunday: { open: '00:00', close: '00:00', isOpen: false }
  }
});
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>