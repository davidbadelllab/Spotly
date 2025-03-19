<script setup lang="ts">
import { ref, onMounted, onUnmounted, reactive, computed, watch } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar plugins de GSAP
gsap.registerPlugin(ScrollTrigger);

// Tipos definidos para características
interface Feature {
  id: string;
  title: string;
  description: string;
  icon: string;
  category: string;
  isPopular?: boolean;
  isNew?: boolean;
  stats?: {
    value: string;
    label: string;
  };
  demoLink?: string;
  videoPreview?: string;
  color?: string;
}

// Definir props con tipado estricto
const props = defineProps<{
  features: Feature[];
}>();

// Estado para interacciones
const activeFeature = ref<string | null>(null);
const activeTab = ref('all');
const showAllFeatures = ref(false);
const hoverState = reactive<Record<string, boolean>>({});
const isPreviewOpen = ref(false);
const currentPreview = ref<Feature | null>(null);
const mousePosRef = ref({ x: 0, y: 0 });

// Categorías dinámicas basadas en los datos
const categories = computed(() => {
  const cats = new Set<string>();
  cats.add('all');
  props.features.forEach(feature => cats.add(feature.category));
  return Array.from(cats);
});

// Filtrar características según la categoría seleccionada
const filteredFeatures = computed(() => {
  if (activeTab.value === 'all') {
    return props.features;
  }
  return props.features.filter(feature => feature.category === activeTab.value);
});

// Características a mostrar (limitadas o todas)
const displayedFeatures = computed(() => {
  if (showAllFeatures.value) {
    return filteredFeatures.value;
  }
  return filteredFeatures.value.slice(0, 6);
});

// Métodos para interactividad
const setActiveFeature = (id: string) => {
  activeFeature.value = id;
};

const clearActiveFeature = () => {
  activeFeature.value = null;
};

const setHoverState = (id: string, state: boolean) => {
  hoverState[id] = state;
};

const switchTab = (tab: string) => {
  activeTab.value = tab;
  
  // Animar el cambio de características
  gsap.fromTo('.feature-card', 
    { opacity: 0, y: 20 },
    { 
      opacity: 1, 
      y: 0, 
      stagger: 0.05, 
      duration: 0.5,
      ease: 'power2.out'
    }
  );
};

const toggleShowAll = () => {
  showAllFeatures.value = !showAllFeatures.value;
  
  if (showAllFeatures.value) {
    // Animar nuevas características que aparecen
    gsap.fromTo('.feature-card:nth-child(n+7)', 
      { opacity: 0, y: 20 },
      { 
        opacity: 1, 
        y: 0, 
        stagger: 0.05, 
        duration: 0.5,
        ease: 'power2.out',
        scrollTrigger: {
          trigger: '.feature-grid',
          start: 'top 80%'
        }
      }
    );
  }
};

// Abrir vista previa de video/demo
const openPreview = (feature: Feature) => {
  currentPreview.value = feature;
  isPreviewOpen.value = true;
  
  // Congelar scroll de la página
  document.body.style.overflow = 'hidden';
  
  // Animar apertura del modal
  gsap.fromTo('.preview-modal', 
    { opacity: 0, scale: 0.9 },
    { opacity: 1, scale: 1, duration: 0.4, ease: 'power2.out' }
  );
};

// Cerrar vista previa
const closePreview = () => {
  gsap.to('.preview-modal', {
    opacity: 0, 
    scale: 0.9, 
    duration: 0.3,
    onComplete: () => {
      isPreviewOpen.value = false;
      currentPreview.value = null;
      document.body.style.overflow = '';
    }
  });
};

// Seguimiento de ratón para efectos de paralaje
const handleMouseMove = (e: MouseEvent) => {
  mousePosRef.value = {
    x: e.clientX,
    y: e.clientY
  };
  
  // Aplicar movimiento paralaje a elementos con clase .parallax
  document.querySelectorAll('.parallax').forEach((el) => {
    const card = el as HTMLElement;
    const rect = card.getBoundingClientRect();
    const centerX = rect.left + rect.width / 2;
    const centerY = rect.top + rect.height / 2;
    
    const distanceX = (mousePosRef.value.x - centerX) / (window.innerWidth / 2);
    const distanceY = (mousePosRef.value.y - centerY) / (window.innerHeight / 2);
    
    gsap.to(card, {
      x: distanceX * 10,
      y: distanceY * 10,
      rotation: distanceX * 2,
      duration: 0.5,
      ease: 'power2.out'
    });
  });
};

// Calcular color del gradiente basado en la categoría o color definido
const getGradientColors = (feature: Feature) => {
  const colorMap: Record<string, string> = {
    'productivity': 'from-indigo-500 to-purple-500',
    'analytics': 'from-blue-500 to-cyan-500',
    'automation': 'from-emerald-500 to-green-500',
    'integration': 'from-amber-500 to-orange-500',
    'security': 'from-rose-500 to-red-500',
    'communication': 'from-violet-500 to-fuchsia-500',
    'default': 'from-slate-700 to-slate-900'
  };
  
  // Usar color definido o buscar por categoría
  if (feature.color) return feature.color;
  return colorMap[feature.category] || colorMap.default;
};

// Configuración de animaciones al cargar
onMounted(() => {
  // Animación del título
  gsap.from('.features-heading h2', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    ease: 'power2.out'
  });
  
  gsap.from('.features-heading p', {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.2,
    ease: 'power2.out'
  });
  
  // Animación de las pestañas de categoría
  gsap.from('.category-tab', {
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.5,
    delay: 0.4,
    ease: 'power2.out'
  });
  
  // Animación de las tarjetas de características
  gsap.from('.feature-card', {
    opacity: 0,
    y: 30,
    stagger: 0.1,
    duration: 0.6,
    delay: 0.6,
    ease: 'power2.out',
    scrollTrigger: {
      trigger: '.feature-grid',
      start: 'top 80%'
    }
  });
  
  // Agregar listener para efecto paralaje
  window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
  // Limpiar listeners y animaciones
  window.removeEventListener('mousemove', handleMouseMove);
  ScrollTrigger.getAll().forEach(trigger => trigger.kill());
});
</script>

<template>
  <section id="features" class="py-20 md:py-32 relative overflow-hidden">
    <!-- Fondo decorativo con gradiente sutil -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 to-white dark:from-gray-950 dark:to-slate-900 pointer-events-none"></div>
    
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 opacity-5 dark:opacity-10 pointer-events-none">
      <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_800px_at_100%_20%,rgba(99,102,241,0.1),transparent)]"></div>
      <div class="absolute bottom-0 right-0 w-full h-full bg-[radial-gradient(circle_800px_at_0%_80%,rgba(168,85,247,0.1),transparent)]"></div>
    </div>
    
    <!-- Patrones geométricos decorativos -->
    <div class="absolute -top-24 -right-24 w-96 h-96 opacity-30 dark:opacity-20 pointer-events-none">
      <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-indigo-300 dark:text-indigo-700">
        <defs>
          <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5" />
          </pattern>
        </defs>
        <rect width="100" height="100" fill="url(#grid)" />
      </svg>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Encabezado de la sección mejorado -->
      <div class="features-heading text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center mb-4 px-4 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800">
          <span class="flex h-2 w-2 mr-2">
            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-indigo-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
          </span>
          Enterprise-Grade Functionality
        </div>
        
        <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400">
          Powerful Features for Modern Businesses
        </h2>
        
        <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
          Discover how our platform can transform your booking operations with cutting-edge tools designed for efficiency and growth.
        </p>
      </div>
      
      <!-- Navegación por pestañas de categoría -->
      <div class="flex flex-wrap justify-center mb-12 gap-3">
        <button 
          v-for="category in categories" 
          :key="category"
          @click="switchTab(category)"
          class="category-tab px-5 py-2.5 text-sm font-medium rounded-full transition-all duration-300 border"
          :class="activeTab === category ? 
            'bg-indigo-600 text-white border-indigo-600 dark:bg-indigo-500 dark:border-indigo-500 shadow-lg' : 
            'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/30'"
        >
          {{ category && typeof category === 'string' ? category.charAt(0).toUpperCase() + category.slice(1) : category }}
        </button>
      </div>
      
      <!-- Grid de características con diseño avanzado -->
      <div class="feature-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="feature in displayedFeatures" 
          :key="feature.id" 
          class="feature-card group relative"
          @mouseenter="setHoverState(feature.id, true)"
          @mouseleave="setHoverState(feature.id, false)"
        >
          <!-- Tarjeta principal con efectos de hover avanzados -->
          <div
            class="h-full bg-white dark:bg-slate-800/70 rounded-2xl p-7 border border-slate-200 dark:border-slate-700
                  shadow-sm hover:shadow-xl transition-all duration-500 transform group-hover:scale-[1.02]
                  relative z-10 overflow-hidden"
          >
            <!-- Fondo con gradiente animado -->
            <div 
              class="absolute top-0 right-0 h-40 w-40 bg-gradient-to-br opacity-10 dark:opacity-20 rounded-full blur-2xl -mr-10 -mt-10 group-hover:opacity-30 dark:group-hover:opacity-40 transition-opacity duration-500"
              :class="getGradientColors(feature)"
            ></div>
            
            <!-- Contenido de la característica -->
            <div class="relative z-10">
              <!-- Icono con contenedor personalizado -->
              <div class="relative mb-5">
                <div 
                  class="w-14 h-14 rounded-xl flex items-center justify-center transform group-hover:rotate-3 transition-transform duration-500 bg-gradient-to-br"
                  :class="getGradientColors(feature)"
                >
                  <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="white" class="transform group-hover:scale-110 transition-transform duration-500">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                  </svg>
                </div>
                
                <!-- Badge para características nuevas o populares -->
                <div 
                  v-if="feature.isNew" 
                  class="absolute -top-2 -right-2 px-2 py-1 text-xs font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 rounded-full"
                >
                  NEW
                </div>
                <div 
                  v-else-if="feature.isPopular" 
                  class="absolute -top-2 -right-2 px-2 py-1 text-xs font-semibold text-white bg-gradient-to-r from-amber-500 to-orange-600 rounded-full"
                >
                  POPULAR
                </div>
              </div>
              
              <!-- Título y descripción -->
              <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">
                {{ feature.title }}
              </h3>
              
              <p class="text-slate-600 dark:text-slate-300 mb-4 line-clamp-3">
                {{ feature.description }}
              </p>
              
              <!-- Estadísticas si existen -->
              <div v-if="feature.stats" class="mb-4 px-3 py-2 bg-slate-50 dark:bg-slate-700/30 rounded-lg border border-slate-100 dark:border-slate-700 text-center">
                <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ feature.stats.value }}</span>
                <span class="text-xs text-slate-500 dark:text-slate-400">{{ feature.stats.label }}</span>
              </div>
              
              <!-- Botones de acción -->
              <div class="flex items-center justify-between mt-5 pt-4 border-t border-slate-100 dark:border-slate-700">
                <button 
                  v-if="feature.videoPreview"
                  @click="openPreview(feature)"
                  class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors"
                >
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                  </svg>
                  Watch demo
                </button>
                
                <button 
                  v-if="feature.demoLink" 
                  @click="openPreview(feature)"
                  class="inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors group"
                >
                  <span>Learn more</span>
                  <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </button>
              </div>
            </div>
            
            <!-- Brillo superior -->
            <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white/80 to-transparent dark:from-white/5 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
          </div>
          
          <!-- Elemento flotante con efecto de paralaje (solo en hover) -->
          <div
            v-if="hoverState[feature.id]"
            class="parallax absolute -z-10 w-20 h-20 rounded-xl bg-gradient-to-br opacity-20 dark:opacity-40 blur-lg transform -translate-y-6 translate-x-16"
            :class="getGradientColors(feature)"
          ></div>
        </div>
      </div>
      
      <!-- Botón para mostrar más características -->
      <div v-if="filteredFeatures.length > 6" class="mt-12 text-center">
        <button 
          @click="toggleShowAll"
          class="px-6 py-3 text-base font-medium bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 rounded-xl border border-indigo-200 dark:border-indigo-800/50 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-300 shadow-sm hover:shadow"
        >
          <span v-if="!showAllFeatures">Show all features</span>
          <span v-else>Show less</span>
          <svg 
            class="inline-block ml-2 w-5 h-5 transition-transform duration-300" 
            :class="{'rotate-180': showAllFeatures}"
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      
      <!-- Sección de destacados finales -->
      <div class="mt-24 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Tarjeta CTA a la izquierda -->
        <div class="relative overflow-hidden rounded-2xl border border-indigo-100 dark:border-indigo-900/50 bg-gradient-to-br from-indigo-50 to-slate-50 dark:from-indigo-950/30 dark:to-slate-900/50 shadow-lg p-8">
          <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-indigo-300/20 to-purple-300/20 dark:from-indigo-500/10 dark:to-purple-500/10 rounded-full blur-3xl transform translate-x-1/4 -translate-y-1/4"></div>
          
          <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-4 relative">Ready to transform your business?</h3>
          <p class="text-slate-600 dark:text-slate-300 mb-6 relative">
            Our platform offers more than just features – it's a complete solution designed to grow with your business.
          </p>
          
          <div class="relative">
            <a href="#pricing" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all">
              Get started
              <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
          </div>
        </div>
        
        <!-- Estadísticas a la derecha -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-lg p-8">
          <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Why businesses choose us</h3>
          
          <div class="space-y-6">
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="ml-3">
                <h4 class="text-lg font-medium text-slate-900 dark:text-white">Increased operational efficiency</h4>
                <p class="text-slate-600 dark:text-slate-400">Businesses report saving up to 15 hours per week on administrative tasks.</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="ml-3">
                <h4 class="text-lg font-medium text-slate-900 dark:text-white">Higher customer satisfaction</h4>
                <p class="text-slate-600 dark:text-slate-400">93% of users report improved customer experience after implementation.</p>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="flex-shrink-0 mt-1">
                <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <div class="ml-3">
                <h4 class="text-lg font-medium text-slate-900 dark:text-white">Enterprise-grade security</h4>
                <p class="text-slate-600 dark:text-slate-400">SOC 2 Type II compliant with 99.99% uptime guarantee.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal de vista previa -->
    <transition name="modal-fade">
      <div v-if="isPreviewOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div class="preview-modal relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
          <!-- Botón de cierre -->
          <button 
            @click="closePreview" 
            class="absolute top-4 right-4 z-20 p-2 rounded-full bg-slate-800/50 text-white hover:bg-slate-800/80 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <!-- Contenido del modal -->
          <div v-if="currentPreview" class="p-0">
            <!-- Video preview o iframe de demo -->
            <div class="aspect-video bg-slate-900 w-full">
              <iframe 
                v-if="currentPreview.videoPreview"
                class="w-full h-full"
                :src="currentPreview.videoPreview"
                title="Feature Demo"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
              
              <iframe 
                v-else-if="currentPreview.demoLink"
                class="w-full h-full"
                :src="currentPreview.demoLink"
                title="Interactive Demo"
                frameborder="0"
              ></iframe>
            </div>
            
            <!-- Información del feature -->
            <div class="p-6">
              <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">
                {{ currentPreview.title }}
              </h3>
              <p class="text-slate-600 dark:text-slate-300">
                {{ currentPreview.description }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<style scoped>
/* Animaciones y transiciones */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.3s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

/* Mejoras de rendimiento */
.feature-card {
  will-change: transform;
  transform: translateZ(0);
}

/* Efecto de parallax */
.parallax {
  will-change: transform;
  transition: transform 0.1s linear;
}

/* Limitar altura de descripción */
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>