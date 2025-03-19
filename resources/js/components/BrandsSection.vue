<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar GSAP ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

interface Brand {
  id: number;
  name: string;
  logo: string;
  clients: number;
  since: number;
  featured?: boolean;
}

// Estado para efectos interactivos
const activeIndex = ref<number | null>(null);
const marqueeWrapper = ref<HTMLElement | null>(null);
const isHovered = ref(false);
const showMoreLogos = ref(false);

// Datos de las marcas principales (con información detallada para tooltips)
const brands: Brand[] = [
  { id: 1, name: 'Airbnb', logo: '/images/brand-1.svg', clients: 12500, since: 2018, featured: true },
  { id: 2, name: 'Spotify', logo: '/images/brand-2.svg', clients: 8900, since: 2019 },
  { id: 3, name: 'Netflix', logo: '/images/brand-3.svg', clients: 5600, since: 2020, featured: true },
  { id: 4, name: 'Slack', logo: '/images/brand-4.svg', clients: 7200, since: 2019 },
  { id: 5, name: 'Microsoft', logo: '/images/brand-5.svg', clients: 15300, since: 2017, featured: true },
  { id: 6, name: 'Uber', logo: '/images/brand-1.svg', clients: 9100, since: 2018 },
  { id: 7, name: 'Adobe', logo: '/images/brand-2.svg', clients: 11200, since: 2019 },
  { id: 8, name: 'Google', logo: '/images/brand-3.svg', clients: 18400, since: 2017 },
  { id: 9, name: 'Amazon', logo: '/images/brand-4.svg', clients: 14200, since: 2018 },
  { id: 10, name: 'Tesla', logo: '/images/brand-5.svg', clients: 6800, since: 2021 }
];

// Marcas adicionales para el carrusel
const additionalBrands = brands.slice().sort(() => Math.random() - 0.5);

// Estadísticas destacadas para mostrar 
const stats = {
  total: 2800,
  growth: 142,
  satisfaction: 98
};

// Métodos para interactividad
const setActive = (index: number) => {
  activeIndex.value = index;
};

const clearActive = () => {
  activeIndex.value = null;
};

const toggleMoreLogos = () => {
  // Animación para mostrar/ocultar logos adicionales
  showMoreLogos.value = !showMoreLogos.value;
  
  if (showMoreLogos.value) {
    gsap.to('.additional-logos', {
      height: 'auto',
      opacity: 1,
      duration: 0.5,
      ease: 'power3.out'
    });
  } else {
    gsap.to('.additional-logos', {
      height: 0,
      opacity: 0,
      duration: 0.5,
      ease: 'power3.in'
    });
  }
};

// Control de carrusel infinito
const pauseMarquee = () => {
  isHovered.value = true;
  if (marqueeWrapper.value) {
    gsap.to(marqueeWrapper.value.children, { paused: true });
  }
};

const resumeMarquee = () => {
  isHovered.value = false;
  if (marqueeWrapper.value) {
    gsap.to(marqueeWrapper.value.children, { paused: false });
  }
};

// Animaciones al montar el componente
onMounted(() => {
  // Animación de entrada de logos
  gsap.from('.brand-logo', {
    scrollTrigger: {
      trigger: '.brands-section',
      start: 'top 80%',
    },
    y: 30,
    opacity: 0,
    stagger: 0.1,
    duration: 0.8,
    ease: 'power3.out',
  });
  
  // Animación para el contador de estadísticas
  gsap.to('.stat-number', {
    scrollTrigger: {
      trigger: '.brands-stats',
      start: 'top 85%',
    },
    duration: 2,
    ease: 'power2.out',
    onStart: function() {
      const elements = document.querySelectorAll('.stat-number');
      elements.forEach(element => {
        const target = parseInt(element.getAttribute('data-target') || '0');
        let count = 0;
        const updateCounter = () => {
          const increment = Math.ceil(target / 70); // Velocidad de conteo
          if (count < target) {
            count += increment;
            if (count > target) count = target;
            element.textContent = count.toString();
            requestAnimationFrame(updateCounter);
          }
        };
        updateCounter();
      });
    }
  });
  
  // Configurar carrusel infinito
  const marqueeItems = document.querySelectorAll('.marquee-item');
  
  gsap.to(marqueeItems, {
    xPercent: -100,
    repeat: -1,
    duration: 30,
    ease: 'none',
    modifiers: {
      xPercent: function(xPercent) {
        return xPercent % 100;
      }
    }
  });
});

onUnmounted(() => {
  // Limpiar las animaciones de GSAP al desmontar
  ScrollTrigger.getAll().forEach(t => t.kill());
  gsap.killTweensOf('.marquee-item');
});
</script>

<template>
  <section class="brands-section overflow-hidden relative py-16 sm:py-20">
    <!-- Fondo decorativo con gradiente sutil -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-50/50 to-white/80 dark:from-slate-900/50 dark:to-slate-950/80 pointer-events-none"></div>
    
    <!-- Patrón decorativo de puntos -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" 
         style="background-image: radial-gradient(circle at 1px 1px, #666 1px, transparent 0); background-size: 24px 24px;">
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Encabezado con animación sutil -->
      <div class="text-center mb-14 max-w-3xl mx-auto">
        <div class="flex items-center justify-center mb-4">
          <div class="h-[1px] w-12 bg-indigo-300 dark:bg-indigo-700"></div>
          <div class="px-4 py-1 mx-4 rounded-full bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50">
            <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 tracking-wider uppercase">
              Enterprise-Grade
            </p>
          </div>
          <div class="h-[1px] w-12 bg-indigo-300 dark:bg-indigo-700"></div>
        </div>
        
        <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-4">
          Backed by the World's Most Innovative Brands
        </h2>
        
        <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
          Join over <span class="font-medium text-slate-900 dark:text-white">2,800+ businesses</span> who trust our platform to optimize their booking operations and enhance customer experiences.
        </p>
      </div>
      
      <!-- Sección principal de logos con efectos hover avanzados -->
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-10 mb-12">
        <div v-for="(brand, index) in brands.slice(0, 5)" :key="brand.id"
             class="group relative flex items-center justify-center"
             @mouseenter="setActive(index)"
             @mouseleave="clearActive()">
          
          <!-- Fondo con efecto en hover -->
          <div class="absolute inset-0 rounded-xl bg-white dark:bg-slate-800/40 opacity-0 
                      group-hover:opacity-100 transform group-hover:scale-110 shadow-lg
                      transition-all duration-300 -z-10"></div>
          
          <!-- Logo con efectos -->
          <div class="brand-logo relative py-5 px-6 w-full">
            <img :src="brand.logo" :alt="brand.name" 
                 class="h-9 sm:h-10 object-contain w-full mx-auto transition-all duration-500
                        group-hover:!grayscale-0 group-hover:!opacity-100
                        group-hover:scale-110"
                 :class="{'grayscale opacity-60': !isHovered && activeIndex !== index,
                          'filter-none opacity-100': isHovered || activeIndex === index}"
            />
            
            <!-- Indicador de clientes destacados -->
            <div v-if="brand.featured" 
                 class="absolute -top-2 -right-2 w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 
                        flex items-center justify-center border border-indigo-200 dark:border-indigo-800">
              <svg class="w-3 h-3 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            
            <!-- Tooltip avanzado en hover -->
            <div v-if="activeIndex === index" 
                 class="absolute top-full left-1/2 transform -translate-x-1/2 mt-3 z-10
                        bg-white dark:bg-slate-800 rounded-lg shadow-xl p-4 w-52
                        border border-slate-200 dark:border-slate-700
                        animate-fade-in pointer-events-none">
              <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-4 h-4 
                          bg-white dark:bg-slate-800 border-t border-l border-slate-200 
                          dark:border-slate-700 rotate-45"></div>
              <p class="font-medium text-slate-900 dark:text-white mb-1">
                {{ brand.name }}
              </p>
              <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">
                Partner since {{ brand.since }}
              </p>
              <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-700 pt-2">
                <div class="text-left">
                  <p class="text-xs text-slate-500 dark:text-slate-400">Active clients</p>
                  <p class="font-semibold text-slate-900 dark:text-white">{{ brand.clients.toLocaleString() }}+</p>
                </div>
                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-full h-8 w-8 flex items-center justify-center">
                  <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Sección expandible con logos adicionales -->
      <div class="additional-logos overflow-hidden h-0 opacity-0">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-10 mb-12">
          <div v-for="(brand, index) in brands.slice(5)" :key="brand.id"
               class="group relative flex items-center justify-center">
            <div class="absolute inset-0 rounded-xl bg-white dark:bg-slate-800/40 opacity-0 
                      group-hover:opacity-100 transform group-hover:scale-110 shadow-lg
                      transition-all duration-300 -z-10"></div>
            <div class="brand-logo relative py-5 px-6 w-full">
              <img :src="brand.logo" :alt="brand.name" 
                   class="h-10 object-contain w-full mx-auto transition-all duration-300
                          grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100
                          group-hover:scale-110"
              />
            </div>
          </div>
        </div>
      </div>
      
      <!-- Botón para mostrar más logos -->
      <div class="text-center mb-16">
        <button @click="toggleMoreLogos" 
                class="inline-flex items-center px-4 py-2 text-sm font-medium 
                      text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300
                      bg-indigo-50 dark:bg-indigo-900/20 rounded-lg transition-colors duration-300
                      border border-indigo-100 dark:border-indigo-800/50">
          <span>{{ showMoreLogos ? 'Show less brands' : 'View all trusted brands' }}</span>
          <svg class="ml-2 w-4 h-4 transition-transform duration-200" 
              :class="showMoreLogos ? 'rotate-180' : ''"
              fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      
      <!-- Estadísticas de confianza -->
      <div class="brands-stats grid grid-cols-1 md:grid-cols-3 gap-8 mb-16 px-4">
        <div class="bg-white dark:bg-slate-800/30 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Global Customer Base</h3>
            <div class="bg-green-50 dark:bg-green-900/20 p-2 rounded-lg">
              <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="flex items-baseline">
            <span class="stat-number text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400" data-target="2800">0</span>
            <span class="ml-1 text-xl text-slate-700 dark:text-slate-300">+</span>
          </div>
          <p class="mt-1 text-slate-600 dark:text-slate-400">Active businesses on our platform</p>
        </div>
        
        <div class="bg-white dark:bg-slate-800/30 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Customer Growth</h3>
            <div class="bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg">
              <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
          </div>
          <div class="flex items-baseline">
            <span class="stat-number text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-amber-500 to-orange-500 dark:from-amber-400 dark:to-orange-400" data-target="142">0</span>
            <span class="ml-1 text-xl text-slate-700 dark:text-slate-300">%</span>
          </div>
          <p class="mt-1 text-slate-600 dark:text-slate-400">Year-over-year customer growth</p>
        </div>
        
        <div class="bg-white dark:bg-slate-800/30 rounded-xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Client Satisfaction</h3>
            <div class="bg-rose-50 dark:bg-rose-900/20 p-2 rounded-lg">
              <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
          </div>
          <div class="flex items-baseline">
            <span class="stat-number text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-rose-500 to-pink-500 dark:from-rose-400 dark:to-pink-400" data-target="98">0</span>
            <span class="ml-1 text-xl text-slate-700 dark:text-slate-300">%</span>
          </div>
          <p class="mt-1 text-slate-600 dark:text-slate-400">Customer satisfaction rate</p>
        </div>
      </div>
      
      <!-- Carrusel infinito de logos -->
      <div class="mb-16 overflow-hidden relative">
        <div class="py-2">
          <p class="text-center text-sm text-slate-500 dark:text-slate-400 font-medium mb-6">
            AND MANY MORE BUSINESSES WORLDWIDE
          </p>
          
          <!-- Carrusel con efecto de desplazamiento infinito -->
          <div 
            ref="marqueeWrapper"
            class="flex overflow-hidden"
            @mouseenter="pauseMarquee"
            @mouseleave="resumeMarquee"
          >
            <div class="flex items-center space-x-12 marquee-item">
              <div v-for="(brand, index) in additionalBrands" :key="`marquee1-${index}`" 
                   class="flex-shrink-0 px-4">
                <img :src="brand.logo" :alt="brand.name" 
                     class="h-6 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all" />
              </div>
            </div>
            
            <!-- Duplicado para el efecto continuo -->
            <div class="flex items-center space-x-12 marquee-item">
              <div v-for="(brand, index) in additionalBrands" :key="`marquee2-${index}`" 
                   class="flex-shrink-0 px-4">
                <img :src="brand.logo" :alt="brand.name" 
                     class="h-6 grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all" />
              </div>
            </div>
          </div>
        </div>
        
        <!-- Desvanecimiento en los bordes -->
        <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-white dark:from-slate-900 to-transparent pointer-events-none"></div>
        <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-white dark:from-slate-900 to-transparent pointer-events-none"></div>
      </div>
      
      <!-- CTA final -->
      <div class="text-center">
        <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-xl mx-auto">
          Join these industry leaders and transform your booking experience with our trusted platform.
        </p>
        <a href="#" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
          Request Enterprise Demo
        </a>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Animaciones avanzadas */
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.3s ease-out forwards;
}

/* Mejoras de rendimiento para animaciones */
.brand-logo {
  transform: translateZ(0);
  will-change: transform;
}
</style>