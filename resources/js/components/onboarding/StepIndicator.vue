<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { CheckCircle, Circle, Save, AlertTriangle, RefreshCw, Clock } from 'lucide-vue-next';
import { Progress } from '../ui';

interface StepInfo {
  title: string;
  description?: string;
  icon?: string;
}

interface Props {
  currentStep: number;
  totalSteps: number;
  progress: number;
  autoSaveStatus?: 'idle' | 'saving' | 'saved' | 'error';
  stepInfo?: StepInfo[];
  unfinishedSteps?: number[];
  timeSpent?: number;  // Tiempo en segundos
  showTimeSpent?: boolean;
  allowNavigation?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  autoSaveStatus: 'idle',
  stepInfo: () => [],
  unfinishedSteps: () => [],
  timeSpent: 0,
  showTimeSpent: false,
  allowNavigation: true,
});

const emits = defineEmits(['go-to-step']);

// Estado local
const showTooltip = ref<number | null>(null);
const saveAnimation = ref(false);
const showSavedConfirmation = ref(false);
const animate = ref(false);

// Formatear tiempo
const formattedTime = computed(() => {
  if (!props.timeSpent) return '00:00';
  const minutes = Math.floor(props.timeSpent / 60);
  const seconds = props.timeSpent % 60;
  return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
});

// Crear steps con información
const steps = computed(() => {
  return Array.from({ length: props.totalSteps }, (_, i) => {
    const stepNumber = i + 1;
    const info = props.stepInfo[i] || { title: getDefaultStepTitle(stepNumber) };
    
    return {
      number: stepNumber,
      title: info.title,
      description: info.description || '',
      icon: info.icon,
      isComplete: stepNumber < props.currentStep,
      isActive: stepNumber === props.currentStep,
      hasError: props.unfinishedSteps.includes(stepNumber) && stepNumber < props.currentStep,
    };
  });
});

// Clases para el estado del paso
const getStepClass = (step: { number: number; isComplete: boolean; isActive: boolean; hasError: boolean }) => {
  const baseClass = 'flex items-center justify-center rounded-full transition-all duration-300';
  
  if (step.hasError) {
    return `${baseClass} bg-amber-100 text-amber-600 border border-amber-200`;
  }
  
  if (step.isActive) {
    return `${baseClass} bg-primary text-primary-foreground border-2 border-primary shadow-sm`;
  }
  
  if (step.isComplete) {
    return `${baseClass} bg-primary/90 text-primary-foreground`;
  }
  
  return `${baseClass} bg-muted text-muted-foreground`;
};

// Clases para la línea conectora
const getConnectorClass = (index: number) => {
  const baseClass = 'h-[2px] flex-grow mx-1 transition-all duration-500';
  
  if (index < props.currentStep - 1) {
    return `${baseClass} bg-primary`;
  }
  
  return `${baseClass} bg-muted`;
};

// Navegación entre pasos
const navigateToStep = (step: number) => {
  if (!props.allowNavigation) return;
  
  // Solo permitir navegar a pasos completados o al paso actual
  if (step <= props.currentStep) {
    emits('go-to-step', step);
  }
};

// Títulos por defecto
const getDefaultStepTitle = (step: number): string => {
  const titles = {
    1: 'Tipo de Negocio',
    2: 'Información Básica',
    3: 'Ubicación',
    4: 'Detalles Adicionales'
  };
  
  return titles[step as keyof typeof titles] || `Paso ${step}`;
};

// Animación para guardado automático
watch(() => props.autoSaveStatus, (newStatus, oldStatus) => {
  if (newStatus === 'saving' && oldStatus !== 'saving') {
    saveAnimation.value = true;
  } else if (newStatus === 'saved' && oldStatus === 'saving') {
    saveAnimation.value = false;
    showSavedConfirmation.value = true;
    setTimeout(() => {
      showSavedConfirmation.value = false;
    }, 3000);
  } else if (newStatus === 'error') {
    saveAnimation.value = false;
  }
});

// Animación al montar
onMounted(() => {
  setTimeout(() => {
    animate.value = true;
  }, 100);
});
</script>

<template>
  <div class="mb-8 space-y-3">
    <!-- Cabecera con progreso y tiempo -->
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-2">
        <h3 class="text-lg font-medium leading-6">Progreso</h3>
        <span v-if="showTimeSpent && timeSpent > 0" 
              class="flex items-center text-xs text-muted-foreground px-2 py-1 bg-muted rounded-full">
          <Clock class="w-3 h-3 mr-1 animate-pulse" />
          {{ formattedTime }}
        </span>
      </div>
      <div class="flex items-center space-x-1">
        <span class="text-sm font-medium">{{ progress }}%</span>
        
        <!-- Indicador de guardado -->
        <div v-if="autoSaveStatus !== 'idle'" class="ml-3 flex items-center">
          <Transition name="fade" mode="out-in">
            <div v-if="autoSaveStatus === 'saving'" key="saving" 
                 class="flex items-center text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
              <RefreshCw 
                class="w-3 h-3 mr-1 animate-spin" 
                :class="{'animate-spin': saveAnimation}" />
              <span>Guardando</span>
            </div>
            <div v-else-if="autoSaveStatus === 'saved'" key="saved" 
                 class="flex items-center text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
              <CheckCircle class="w-3 h-3 mr-1" />
              <span>Guardado</span>
            </div>
            <div v-else-if="autoSaveStatus === 'error'" key="error" 
                 class="flex items-center text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">
              <AlertTriangle class="w-3 h-3 mr-1" />
              <span>Error</span>
            </div>
          </Transition>
        </div>
      </div>
    </div>
    
    <!-- Barra de progreso -->
    <div class="relative">
      <Progress :value="progress" class="h-2">
        <div class="h-full bg-primary transition-all duration-700 ease-in-out"
             :style="{ width: `${progress}%` }" />
      </Progress>
      <div v-if="animate"
           class="absolute top-0 left-0 h-full w-full overflow-hidden">
        <div class="h-full bg-primary/20 animate-pulse rounded-full"
             :style="{ width: `${Math.min(progress + 10, 100)}%`, 
                       transitionDelay: '0.5s',
                       transition: 'width 1s ease-in-out' }" />
      </div>
    </div>

    <!-- Indicadores de pasos -->
    <div class="relative mt-6">
      <div class="flex items-center justify-between">
        <template v-for="(step, index) in steps" :key="step.number">
          <!-- Paso -->
          <div class="relative z-10"
               @mouseenter="showTooltip = step.number"
               @mouseleave="showTooltip = null"
               @click="navigateToStep(step.number)">
            <div class="relative">
              <div :class="[
                    getStepClass(step),
                    step.isActive ? 'w-8 h-8' : 'w-7 h-7',
                    props.allowNavigation && step.number <= currentStep ? 'cursor-pointer hover:ring-2 hover:ring-primary/20' : ''
                  ]">
                <template v-if="step.hasError">
                  <AlertTriangle class="w-3 h-3" />
                </template>
                <template v-else-if="step.isComplete">
                  <CheckCircle class="w-4 h-4" />
                </template>
                <template v-else>
                  <span class="text-xs font-medium">{{ step.number }}</span>
                </template>
              </div>
              
              <!-- Tooltip con información del paso -->
              <Transition name="tooltip">
                <div v-if="showTooltip === step.number && step.title"
                     class="absolute left-1/2 transform -translate-x-1/2 -top-12 w-48 bg-white border border-gray-200 rounded-md shadow-lg p-2 z-20">
                  <div class="text-xs font-semibold">{{ step.title }}</div>
                  <div v-if="step.description" class="text-xs text-muted-foreground mt-1">{{ step.description }}</div>
                  <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-2 h-2 bg-white border-b border-r border-gray-200 rotate-45"></div>
                </div>
              </Transition>
            </div>
            <div class="mt-2 text-center">
              <span class="text-xs whitespace-nowrap"
                    :class="step.isActive ? 'font-medium text-primary' : 'text-muted-foreground'">
                {{ step.title }}
              </span>
            </div>
          </div>
          
          <!-- Línea conectora entre pasos -->
          <div v-if="index < steps.length - 1" :class="getConnectorClass(index)"></div>
        </template>
      </div>
    </div>
  </div>
</template>

<style scoped>
.tooltip-enter-active,
.tooltip-leave-active {
  transition: all 0.2s ease;
}

.tooltip-enter-from,
.tooltip-leave-to {
  opacity: 0;
  transform: translateX(-50%) translateY(-5px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.step-indicator-enter-active,
.step-indicator-leave-active {
  transition: all 0.5s ease;
}

.step-indicator-enter-from,
.step-indicator-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>