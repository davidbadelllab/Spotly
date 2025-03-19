<script setup lang="ts">
import { ref, onMounted, computed, watch, reactive } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar plugins de GSAP
gsap.registerPlugin(ScrollTrigger);

// Definición de tipos para el componente
interface VenueTypeFeature {
  id: string;
  name: string;
  icon: string;
}

interface VenueTypeTestimonial {
  id: string;
  quote: string;
  author: string;
  role: string;
  company: string;
  avatar?: string;
}

interface VenueType {
  id: string;
  name: string;
  description: string;
  image: string;
  icon: string;
  color: string;
  examples: string[];
  features: VenueTypeFeature[];
  testimonial?: VenueTypeTestimonial;
  stats?: {
    label: string;
    value: string;
    growth?: string;
  }[];
}

// Props con tipado estricto
const props = defineProps<{
  venueTypes: VenueType[];
}>();

// Estado local
const activeTab = ref<string>(props.venueTypes[0]?.id || '');
const activeVenueType = computed(() => props.venueTypes.find(type => type.id === activeTab.value) || props.venueTypes[0]);
const isModalOpen = ref(false);
const activeImageIndex = ref(0);
const hoverStates = reactive<Record<string, boolean>>({});
const animatedElements = ref<HTMLElement[]>([]);

// Cambiar la pestaña activa
const setActiveTab = (id: string) => {
  // Si ya está activa, no hacer nada
  if (activeTab.value === id) return;
  
  // Animar salida del contenido actual
  gsap.to('.solution-content', {
    opacity: 0,
    y: 20,
    duration: 0.3,
    onComplete: () => {
      activeTab.value = id;
      
      // Animar entrada del nuevo contenido
      gsap.fromTo('.solution-content',
        { opacity: 0, y: 20 },
        { 
          opacity: 1, 
          y: 0, 
          duration: 0.5,
          clearProps: 'all'
        }
      );
    }
  });
};

// Abrir modal de galería
const openGalleryModal = (index: number = 0) => {
  activeImageIndex.value = index;
  isModalOpen.value = true;
  document.body.style.overflow = 'hidden';
  
  // Animar apertura del modal
  gsap.fromTo('.gallery-modal',
    { opacity: 0, scale: 0.95 },
    { opacity: 1, scale: 1, duration: 0.4 }
  );
};

// Cerrar modal de galería
const closeGalleryModal = () => {
  gsap.to('.gallery-modal', {
    opacity: 0,
    scale: 0.95,
    duration: 0.3,
    onComplete: () => {
      isModalOpen.value = false;
      document.body.style.overflow = '';
    }
  });
};

// Marcar elemento como hover
const setHoverState = (id: string, state: boolean) => {
  hoverStates[id] = state;
};

// Montar animaciones
onMounted(() => {
  // Animar encabezado de la sección
  gsap.from('.solutions-heading h2', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.solutions-heading',
      start: 'top 80%'
    }
  });
  
  gsap.from('.solutions-heading p', {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.2,
    scrollTrigger: {
      trigger: '.solutions-heading',
      start: 'top 80%'
    }
  });
  
  // Animar pestañas
  gsap.from('.solution-tab', {
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.5,
    scrollTrigger: {
      trigger: '.solution-tabs',
      start: 'top 80%'
    }
  });
  
  // Animar contenido inicial
  gsap.from('.solution-content', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.solution-content',
      start: 'top 80%'
    }
  });
  
  // Animar características
  gsap.from('.feature-item', {
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.5,
    scrollTrigger: {
      trigger: '.feature-list',
      start: 'top 85%'
    }
  });
  
  // Inicializar elementos animados
  animatedElements.value = Array.from(document.querySelectorAll('.parallax-element'));
  
  // Configurar efecto de paralaje
  document.addEventListener('mousemove', handleMouseMove);
});

// Efecto de paralaje en movimiento del ratón
const handleMouseMove = (e: MouseEvent) => {
  const { clientX, clientY } = e;
  const centerX = window.innerWidth / 2;
  const centerY = window.innerHeight / 2;
  
  animatedElements.value.forEach(element => {
    const depth = parseFloat(element.dataset.depth || '0.1');
    const moveX = (clientX - centerX) * depth;
    const moveY = (clientY - centerY) * depth;
    
    gsap.to(element, {
      x: moveX,
      y: moveY,
      duration: 1,
      ease: 'power2.out'
    });
  });
};

// Limpiar listener al desmontar
const onBeforeUnmount = () => {
  document.removeEventListener('mousemove', handleMouseMove);
};
</script>

<template>
  <section id="solutions" class="py-20 md:py-32 relative overflow-hidden">
    <!-- Efectos de fondo -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 via-white to-white dark:from-slate-950 dark:via-slate-900 dark:to-slate-900"></div>
    
    <!-- Patrón de puntos decorativo -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.07]" 
         style="background-image: radial-gradient(#6366f1 0.5px, transparent 0.5px); background-size: 24px 24px;">
    </div>
    
    <!-- Formas decorativas flotantes con efecto paralaje -->
    <div class="absolute top-20 right-20 w-96 h-96 bg-indigo-200/20 dark:bg-indigo-500/10 rounded-full blur-3xl parallax-element" data-depth="0.05"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-200/20 dark:bg-purple-500/10 rounded-full blur-3xl parallax-element" data-depth="0.07"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Encabezado de la sección mejorado -->
      <div class="solutions-heading text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center mb-4 px-4 py-1.5 rounded-full text-xs font-semibold bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 border border-purple-100 dark:border-purple-800/50">
          <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
          </svg>
          Industry Solutions
        </div>
        
        <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
          Tailored for Your Specific Industry
        </h2>
        
        <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
          Our platform adapts to the unique requirements of your business, providing specialized tools for maximum efficiency and growth.
        </p>
      </div>
      
      <!-- Navegación por pestañas mejorada -->
      <div class="solution-tabs flex flex-wrap justify-center mb-10 gap-2">
        <button 
          v-for="type in venueTypes" 
          :key="type.id"
          @click="setActiveTab(type.id)"
          class="solution-tab group relative px-5 py-3 rounded-xl border transition-all duration-300"
          :class="activeTab === type.id ? 
            'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 shadow-md' : 
            'bg-white/50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-800 hover:bg-white hover:dark:bg-slate-800'"
        >
          <div class="flex items-center">
            <!-- Icono con color personalizado -->
            <div 
              class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 transition-colors duration-300"
              :class="activeTab === type.id ? 
                `bg-${type.color}-100 dark:bg-${type.color}-900/30 text-${type.color}-600 dark:text-${type.color}-400` : 
                'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 group-hover:bg-slate-200 dark:group-hover:bg-slate-600'"
            >
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="type.icon" />
              </svg>
            </div>
            
            <div class="text-left">
              <h3 
                class="font-medium transition-colors duration-300"
                :class="activeTab === type.id ? 
                  'text-slate-900 dark:text-white' : 
                  'text-slate-600 dark:text-slate-400'"
              >
                {{ type.name }}
              </h3>
            </div>
          </div>
          
          <!-- Indicador activo -->
          <div 
            class="absolute bottom-0 left-0 h-0.5 bg-gradient-to-r transition-all duration-300 w-0"
            :class="`from-${activeVenueType.color}-500 to-${activeVenueType.color}-600 dark:from-${activeVenueType.color}-600 dark:to-${activeVenueType.color}-500`"
            :style="{ width: activeTab === type.id ? '100%' : '0%' }"
          ></div>
        </button>
      </div>
      
      <!-- Contenido principal con diseño avanzado -->
      <div class="solution-content grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
        <!-- Columna izquierda (3 columnas) -->
        <div class="lg:col-span-3 order-2 lg:order-1">
          <!-- Descripción con formato mejorado -->
          <div class="p-8 bg-white dark:bg-slate-800/70 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 mb-8">
            <div class="flex items-start mb-6">
              <div 
                class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center mr-4"
                :class="`bg-${activeVenueType.color}-100 dark:bg-${activeVenueType.color}-900/30 text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`"
              >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="activeVenueType.icon" />
                </svg>
              </div>
              <div>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white">
                  {{ activeVenueType.name }} Solution
                </h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm">
                  Specialized platform tailored for {{ activeVenueType.name.toLowerCase() }} businesses
                </p>
              </div>
            </div>
            
            <p class="text-slate-700 dark:text-slate-300 mb-6 leading-relaxed">
              {{ activeVenueType.description }}
            </p>
            
            <!-- Lista de ejemplos mejorada -->
            <div class="space-y-4">
              <h4 class="text-sm font-semibold text-slate-900 dark:text-white uppercase tracking-wider">
                Key Capabilities
              </h4>
              <ul class="space-y-3">
                <li 
                  v-for="example in activeVenueType.examples" 
                  :key="example" 
                  class="flex items-start"
                >
                  <div 
                    class="flex-shrink-0 w-5 h-5 rounded-full flex items-center justify-center mt-0.5 mr-3"
                    :class="`bg-${activeVenueType.color}-100 dark:bg-${activeVenueType.color}-900/30 text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`"
                  >
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                  <span class="text-slate-700 dark:text-slate-300">{{ example }}</span>
                </li>
              </ul>
            </div>
            
            <!-- Estadísticas destacadas -->
            <div v-if="activeVenueType.stats" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
              <div 
                v-for="(stat, index) in activeVenueType.stats" 
                :key="index"
                class="text-center p-4 rounded-lg bg-slate-50 dark:bg-slate-800"
              >
                <p 
                  class="text-2xl font-bold"
                  :class="`text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`"
                >
                  {{ stat.value }}
                  <span v-if="stat.growth" class="text-sm ml-1 text-green-500">
                    +{{ stat.growth }}
                  </span>
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ stat.label }}</p>
              </div>
            </div>
          </div>
          
          <!-- Lista de características avanzada -->
          <div class="feature-list grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div 
              v-for="feature in activeVenueType.features" 
              :key="feature.id"
              class="feature-item group bg-white dark:bg-slate-800/60 p-5 rounded-xl border border-slate-100 dark:border-slate-700 hover:shadow-md transition-all duration-300"
              @mouseenter="setHoverState(feature.id, true)"
              @mouseleave="setHoverState(feature.id, false)"
            >
              <div class="flex items-start">
                <div 
                  class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center mr-4 transition-all duration-300 transform group-hover:rotate-3"
                  :class="`bg-${activeVenueType.color}-100 dark:bg-${activeVenueType.color}-900/30 text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`"
                >
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                  </svg>
                </div>
                <div>
                  <h4 
                    class="text-base font-semibold text-slate-900 dark:text-white mb-1 transition-colors duration-300"
                    :class="{ [`text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`]: hoverStates[feature.id] }"
                  >
                    {{ feature.name }}
                  </h4>
                  <p class="text-sm text-slate-600 dark:text-slate-400">
                    Optimize your {{ activeVenueType.name.toLowerCase() }} operations with our powerful {{ feature.name.toLowerCase() }} tools.
                  </p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Sección de testimonios -->
          <div v-if="activeVenueType.testimonial" class="mt-8 p-6 bg-white dark:bg-slate-800/70 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-md relative overflow-hidden">
            <!-- Comillas decorativas -->
            <svg class="absolute top-6 left-6 w-16 h-16 text-slate-100 dark:text-slate-700" viewBox="0 0 24 24" fill="currentColor">
              <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
            </svg>
            
            <div class="relative">
              <p class="text-lg italic text-slate-700 dark:text-slate-300 mb-4 pl-12">
                "{{ activeVenueType.testimonial.quote }}"
              </p>
              
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <img 
                    v-if="activeVenueType.testimonial.avatar" 
                    :src="activeVenueType.testimonial.avatar" 
                    :alt="activeVenueType.testimonial.author"
                    class="w-12 h-12 rounded-full object-cover border-2"
                    :class="`border-${activeVenueType.color}-200 dark:border-${activeVenueType.color}-900`"
                  />
                  <div 
                    v-else
                    class="w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold"
                    :class="`bg-${activeVenueType.color}-100 dark:bg-${activeVenueType.color}-900/30 text-${activeVenueType.color}-600 dark:text-${activeVenueType.color}-400`"
                  >
                    {{ activeVenueType.testimonial.author.charAt(0) }}
                  </div>
                </div>
                <div>
                  <p class="font-semibold text-slate-900 dark:text-white">
                    {{ activeVenueType.testimonial.author }}
                  </p>
                  <p class="text-sm text-slate-500 dark:text-slate-400">
                    {{ activeVenueType.testimonial.role }}, {{ activeVenueType.testimonial.company }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Columna derecha con imagen destacada (2 columnas) -->
        <div class="lg:col-span-2 order-1 lg:order-2">
          <div class="sticky top-28">
            <!-- Imagen principal con efectos avanzados -->
            <div 
              class="relative rounded-2xl overflow-hidden shadow-xl border-8 cursor-pointer transition-transform duration-300 hover:scale-[1.02]"
              :class="`border-${activeVenueType.color}-100 dark:border-${activeVenueType.color}-900/30`"
              @click="openGalleryModal(0)"
            >
              <!-- Overlay con gradiente -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent z-10"></div>
              
              <!-- Etiqueta flotante -->
              <div 
                class="absolute top-4 left-4 z-20 px-3 py-1 rounded-full text-xs font-semibold"
                :class="`bg-${activeVenueType.color}-500 text-white`"
              >
                Premium Solution
              </div>
              
              <!-- Imagen -->
              <img 
                :src="activeVenueType.image" 
                :alt="activeVenueType.name" 
                class="w-full aspect-[3/4] object-cover transition-transform duration-700 hover:scale-110"
              />
              
              <!-- Botón de play para sugerir interactividad -->
              <div class="absolute inset-0 flex items-center justify-center z-20">
                <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                  <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                  </svg>
                </div>
              </div>
              
              <!-- Footer con info -->
              <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                <h3 class="text-white text-xl font-bold mb-1">{{ activeVenueType.name }} Dashboard</h3>
                <p class="text-white/80 text-sm">Click to see detailed screenshots</p>
              </div>
            </div>
            
            <!-- CTA secundario -->
            <div class="mt-6 p-6 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 shadow-md">
              <h4 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">
                Ready to revolutionize your {{ activeVenueType.name.toLowerCase() }} business?
              </h4>
              <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">
                Get started with our {{ activeVenueType.name }} solution today and see the difference.
              </p>
              <a 
                href="#"
                class="block w-full text-center py-3 px-4 rounded-lg text-white font-medium shadow-sm transition-all duration-300"
                :class="`bg-${activeVenueType.color}-600 hover:bg-${activeVenueType.color}-700 dark:bg-${activeVenueType.color}-600 dark:hover:bg-${activeVenueType.color}-700`"
              >
                Request Demo
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal de galería de imágenes -->
    <transition name="fade">
      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="gallery-modal relative w-full max-w-5xl bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
          <!-- Botón de cierre -->
          <button 
            @click="closeGalleryModal" 
            class="absolute top-4 right-4 z-20 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <!-- Contenido del modal -->
          <div class="p-0">
            <!-- Imagen ampliada -->
            <div class="aspect-video bg-slate-900">
              <img 
                :src="activeVenueType.image" 
                :alt="activeVenueType.name" 
                class="w-full h-full object-contain"
              />
            </div>
            
            <!-- Información de la imagen -->
            <div class="p-6">
              <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                {{ activeVenueType.name }} Solution Dashboard
              </h3>
              <p class="text-slate-600 dark:text-slate-300">
                A comprehensive view of the {{ activeVenueType.name.toLowerCase() }} management interface, designed for maximum efficiency and ease of use.
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
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Mejoras de rendimiento para animaciones */
.parallax-element {
  will-change: transform;
}

.solution-content {
  will-change: opacity, transform;
}
</style>