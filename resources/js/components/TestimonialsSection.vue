<script setup lang="ts">
import { ref, reactive, onMounted, onUnmounted, computed, watch } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar plugins GSAP
gsap.registerPlugin(ScrollTrigger);

// Interfaz para testimonios
interface Testimonial {
  id: number | string;
  content: string;
  name: string;
  role: string;
  company?: string;
  image: string;
  rating: number;
  location?: string;
  featured?: boolean;
  date?: string;
  video?: string;
  socialLink?: string;
  platform?: string;
}

// Definir props con tipado estricto
interface Props {
  testimonials: Testimonial[];
  autoplaySpeed?: number;
  autoplay?: boolean;
  primaryColor?: string;
  layout?: 'modern' | 'classic' | 'cards' | 'masonry';
}

const props = withDefaults(defineProps<Props>(), {
  autoplaySpeed: 5000,
  autoplay: true,
  primaryColor: 'indigo',
  layout: 'modern'
});

// Estado para el carrusel y animaciones
const activeTestimonialIndex = ref(0);
const isPaused = ref(false);
const isTransitioning = ref(false);
const containerElement = ref<HTMLElement | null>(null);
const carouselElement = ref<HTMLElement | null>(null);
const videoModal = ref<boolean>(false);
const currentVideo = ref<string | null>(null);
const mousePosition = reactive({ x: 0, y: 0 });
const hasInteracted = ref(false);
const dragging = reactive({ 
  active: false, 
  startX: 0, 
  currentX: 0,
  threshold: 50 
});

// Filtrar testimonios destacados
const featuredTestimonials = computed(() => {
  return props.testimonials.filter(testimonial => testimonial.featured);
});

// Obtener el testimonial activo
const activeTestimonial = computed(() => {
  return props.testimonials[activeTestimonialIndex.value];
});

// Determinar si hay testimonios con video
const hasVideos = computed(() => {
  return props.testimonials.some(t => t.video);
});

// Métodos para navegar entre testimonios
const nextTestimonial = () => {
  if (isTransitioning.value) return;
  isTransitioning.value = true;
  
  // Transition animation
  animateTransition('next', () => {
    activeTestimonialIndex.value = (activeTestimonialIndex.value + 1) % props.testimonials.length;
    isTransitioning.value = false;
  });
  
  hasInteracted.value = true;
};

const prevTestimonial = () => {
  if (isTransitioning.value) return;
  isTransitioning.value = true;
  
  // Transition animation
  animateTransition('prev', () => {
    activeTestimonialIndex.value = (activeTestimonialIndex.value - 1 + props.testimonials.length) % props.testimonials.length;
    isTransitioning.value = false;
  });
  
  hasInteracted.value = true;
};

const goToTestimonial = (index: number) => {
  if (activeTestimonialIndex.value === index || isTransitioning.value) return;
  isTransitioning.value = true;
  
  const direction = index > activeTestimonialIndex.value ? 'next' : 'prev';
  
  // Transition animation
  animateTransition(direction, () => {
    activeTestimonialIndex.value = index;
    isTransitioning.value = false;
  });
  
  hasInteracted.value = true;
};

// Método para abrir el modal de video
const openVideoModal = (videoUrl: string) => {
  currentVideo.value = videoUrl;
  videoModal.value = true;
  document.body.style.overflow = 'hidden';
  
  // Animate modal opening
  gsap.fromTo('.video-modal', 
    { opacity: 0, scale: 0.9 },
    { opacity: 1, scale: 1, duration: 0.4, ease: 'power2.out' }
  );
};

// Método para cerrar el modal de video
const closeVideoModal = () => {
  gsap.to('.video-modal', {
    opacity: 0,
    scale: 0.9,
    duration: 0.3,
    ease: 'power2.in',
    onComplete: () => {
      videoModal.value = false;
      currentVideo.value = null;
      document.body.style.overflow = '';
    }
  });
};

// Pausar/Reanudar autoplay
const toggleAutoplay = () => {
  isPaused.value = !isPaused.value;
};

// Animación de transición entre testimonios
const animateTransition = (direction: 'next' | 'prev', callback: () => void) => {
  const currentElement = document.querySelector('.testimonial-active');
  if (!currentElement) {
    callback();
    return;
  }
  
  const xOffset = direction === 'next' ? 50 : -50;
  
  gsap.to(currentElement, {
    opacity: 0,
    x: -xOffset,
    duration: 0.4,
    ease: 'power1.in',
    onComplete: () => {
      callback();
      
      gsap.fromTo('.testimonial-active',
        { opacity: 0, x: xOffset },
        { opacity: 1, x: 0, duration: 0.4, ease: 'power1.out' }
      );
    }
  });
};

// Manejo de gestos de deslizamiento (swipe)
const handleDragStart = (e: MouseEvent | TouchEvent) => {
  dragging.active = true;
  dragging.startX = 'touches' in e ? e.touches[0].clientX : e.clientX;
  document.addEventListener('mousemove', handleDragMove);
  document.addEventListener('touchmove', handleDragMove);
  document.addEventListener('mouseup', handleDragEnd);
  document.addEventListener('touchend', handleDragEnd);
};

const handleDragMove = (e: MouseEvent | TouchEvent) => {
  if (!dragging.active) return;
  dragging.currentX = 'touches' in e ? e.touches[0].clientX : e.clientX;
};

const handleDragEnd = () => {
  if (!dragging.active) return;
  
  const delta = dragging.currentX - dragging.startX;
  
  if (Math.abs(delta) > dragging.threshold) {
    delta > 0 ? prevTestimonial() : nextTestimonial();
  }
  
  dragging.active = false;
  document.removeEventListener('mousemove', handleDragMove);
  document.removeEventListener('touchmove', handleDragMove);
  document.removeEventListener('mouseup', handleDragEnd);
  document.removeEventListener('touchend', handleDragEnd);
};

// Efecto de paralaje para fondos
const handleMouseMove = (e: MouseEvent) => {
  if (!containerElement.value) return;
  
  const rect = containerElement.value.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  
  mousePosition.x = x / rect.width;
  mousePosition.y = y / rect.height;
  
  const parallaxElements = document.querySelectorAll('.parallax-bg');
  parallaxElements.forEach((el) => {
    const element = el as HTMLElement;
    const speedX = parseFloat(element.dataset.speedX || '0.05');
    const speedY = parseFloat(element.dataset.speedY || '0.05');
    
    const moveX = (mousePosition.x - 0.5) * speedX * 100;
    const moveY = (mousePosition.y - 0.5) * speedY * 100;
    
    gsap.to(element, {
      x: moveX,
      y: moveY,
      duration: 1,
      ease: 'power2.out'
    });
  });
};

// Autorotación de testimonios con intervalo
let autoplayInterval: number | undefined;

const startAutoplay = () => {
  if (!props.autoplay) return;
  
  clearInterval(autoplayInterval);
  autoplayInterval = window.setInterval(() => {
    if (!isPaused.value && !hasInteracted.value) {
      nextTestimonial();
    }
  }, props.autoplaySpeed);
};

// Animaciones en scroll
const setupScrollAnimations = () => {
  // Animación del encabezado
  gsap.from('.testimonials-heading h2', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.testimonials-heading',
      start: 'top 80%'
    }
  });
  
  gsap.from('.testimonials-heading p', {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.2,
    scrollTrigger: {
      trigger: '.testimonials-heading',
      start: 'top 80%'
    }
  });
  
  // Animación del carrusel
  gsap.from('.testimonial-carousel', {
    opacity: 0,
    y: 30,
    duration: 1,
    scrollTrigger: {
      trigger: '.testimonial-carousel',
      start: 'top 80%'
    }
  });
  
  // Animación de los testimonios destacados
  gsap.from('.featured-testimonial', {
    opacity: 0,
    y: 30,
    stagger: 0.2,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.featured-testimonials',
      start: 'top 80%'
    }
  });
};

// Lifecycle hooks
onMounted(() => {
  startAutoplay();
  setupScrollAnimations();
  
  if (containerElement.value) {
    containerElement.value.addEventListener('mousemove', handleMouseMove);
  }
  
  if (carouselElement.value) {
    carouselElement.value.addEventListener('mousedown', handleDragStart);
    carouselElement.value.addEventListener('touchstart', handleDragStart);
  }
  
  // Preparar videos embedidos si existen
  if (hasVideos.value) {
    // Precargar miniaturas de videos
    props.testimonials.forEach(testimonial => {
      if (testimonial.video) {
        const img = new Image();
        img.src = `https://img.youtube.com/vi/${getYoutubeId(testimonial.video || '')}/0.jpg`;
      }
    });
  }
});

onUnmounted(() => {
  clearInterval(autoplayInterval);
  
  if (containerElement.value) {
    containerElement.value.removeEventListener('mousemove', handleMouseMove);
  }
  
  if (carouselElement.value) {
    carouselElement.value.removeEventListener('mousedown', handleDragStart);
    carouselElement.value.removeEventListener('touchstart', handleDragStart);
  }
  
  // Limpiar listeners de arrastre
  document.removeEventListener('mousemove', handleDragMove);
  document.removeEventListener('touchmove', handleDragMove);
  document.removeEventListener('mouseup', handleDragEnd);
  document.removeEventListener('touchend', handleDragEnd);
  
  // Limpiar animaciones GSAP
  ScrollTrigger.getAll().forEach(trigger => trigger.kill());
});

// Utility functions
const getYoutubeId = (url: string): string => {
  const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  const match = url.match(regExp);
  return (match && match[2].length === 11) ? match[2] : '';
};

// Watch para reiniciar autoplay cuando cambie la velocidad
watch(() => props.autoplaySpeed, () => {
  startAutoplay();
});
</script>

<template>
  <section 
    id="testimonials" 
    ref="containerElement"
    class="relative py-20 md:py-32 overflow-hidden"
    :class="{
      'bg-gray-50 dark:bg-gray-900/20': layout === 'classic',
      'bg-gradient-to-b from-white to-gray-50 dark:from-gray-900 dark:to-gray-900/30': layout === 'modern',
      'bg-white dark:bg-gray-900': layout === 'cards',
      'bg-gray-100 dark:bg-gray-900/40': layout === 'masonry'
    }"
  >
    <!-- Elementos decorativos de fondo con efecto parallax -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
      <div 
        class="parallax-bg absolute -top-20 right-10 w-96 h-96 bg-gradient-to-br opacity-10 dark:opacity-20 rounded-full blur-3xl" 
        :class="`from-${primaryColor}-200 to-${primaryColor}-400 dark:from-${primaryColor}-600 dark:to-${primaryColor}-800`"
        data-speed-x="0.05" 
        data-speed-y="0.03"
      ></div>
      <div 
        class="parallax-bg absolute bottom-10 -left-20 w-96 h-96 bg-gradient-to-tr opacity-10 dark:opacity-20 rounded-full blur-3xl" 
        :class="`from-purple-200 to-${primaryColor}-300 dark:from-purple-700 dark:to-${primaryColor}-600`"
        data-speed-x="-0.03" 
        data-speed-y="-0.05"
      ></div>
      <div 
        class="parallax-bg absolute top-1/3 left-1/3 w-32 h-32 bg-gradient-to-br opacity-10 dark:opacity-20 rounded-full blur-xl" 
        :class="`from-amber-200 to-${primaryColor}-300 dark:from-amber-700 dark:to-${primaryColor}-600`"
        data-speed-x="0.07" 
        data-speed-y="-0.04"
      ></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Encabezado mejorado -->
      <div class="testimonials-heading text-center max-w-3xl mx-auto mb-16">
        <div 
          class="inline-flex items-center mb-4 px-3 py-1 rounded-full text-xs font-semibold border"
          :class="`bg-${primaryColor}-50 text-${primaryColor}-600 border-${primaryColor}-100 dark:bg-${primaryColor}-900/30 dark:text-${primaryColor}-400 dark:border-${primaryColor}-800/50`"
        >
          <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
          </svg>
          Customer Success Stories
        </div>
        
        <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
          Trusted by Thousands of Businesses
        </h2>
        
        <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
          Discover how our platform has transformed booking operations for organizations across multiple industries.
        </p>
      </div>
      
      <!-- Carrusel principal de testimonios -->
      <div 
        ref="carouselElement"
        class="testimonial-carousel relative max-w-4xl mx-auto mb-16"
      >
        <!-- Tarjeta principal del testimonio con efectos mejorados -->
        <div 
          class="relative overflow-hidden rounded-2xl shadow-xl transition-all duration-300 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700"
          :class="{'transform hover:scale-[1.01]': !dragging.active}"
        >
          <!-- Fondo decorativo con gradiente animado -->
          <div 
            class="absolute inset-0 bg-gradient-to-br opacity-5 dark:opacity-10 transition-opacity duration-500"
            :class="`from-${primaryColor}-300 to-${primaryColor}-600 dark:from-${primaryColor}-600 dark:to-${primaryColor}-900`"
          ></div>
          
          <div class="px-6 py-10 md:p-12 lg:p-14 relative">
            <!-- Icono de comillas mejorado -->
            <div 
              class="mb-8 w-16 h-16 rounded-full flex items-center justify-center"
              :class="`bg-${primaryColor}-50 dark:bg-${primaryColor}-900/30 text-${primaryColor}-300 dark:text-${primaryColor}-700`"
            >
              <svg width="28" height="22" fill="currentColor" class="transform -translate-y-1">
                <path d="M13.415.001C6.07 5.185.887 13.681.887 23.041c0 7.632 4.608 12.096 9.936 12.096 5.04 0 8.784-4.032 8.784-8.784 0-4.752-3.312-8.208-7.632-8.208-.864 0-2.016.144-2.304.288.72-4.896 5.328-10.656 9.936-13.536L13.415.001zm24.768 0c-7.2 5.184-12.384 13.68-12.384 23.04 0 7.632 4.608 12.096 9.936 12.096 4.896 0 8.784-4.032 8.784-8.784 0-4.752-3.456-8.208-7.776-8.208-.864 0-1.872.144-2.16.288.72-4.896 5.184-10.656 9.792-13.536L38.183.001z"></path>
              </svg>
            </div>
            
            <!-- Testimonios con animación mejorada -->
            <div class="min-h-[200px] relative">
              <div 
                v-for="(testimonial, index) in testimonials"
                :key="testimonial.id"
                class="transition-all duration-500 absolute w-full"
                :class="[
                  index === activeTestimonialIndex ? 'testimonial-active opacity-100 z-10' : 'opacity-0 z-0'
                ]"
                :style="index !== activeTestimonialIndex ? 'pointer-events: none; left: 0; right: 0;' : ''"
              >
                <blockquote>
                  <!-- Fecha del testimonio si existe -->
                  <div 
                    v-if="testimonial.date" 
                    class="mb-4 text-sm text-gray-500 dark:text-gray-400 flex items-center"
                  >
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ testimonial.date }}
                  </div>
                  
                  <!-- Contenido del testimonio -->
                  <p 
                    class="text-xl md:text-2xl font-medium leading-relaxed text-gray-900 dark:text-white mb-8"
                    :class="{ 'italic': layout === 'classic' }"
                  >
                    "{{ testimonial.content }}"
                  </p>
                  
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Autor del testimonio con imagen y datos -->
                    <div class="flex items-center">
                      <div class="relative">
                        <img 
                          :src="testimonial.image" 
                          :alt="testimonial.name"
                          class="h-14 w-14 rounded-full object-cover border-2"
                          :class="`border-${primaryColor}-200 dark:border-${primaryColor}-800`"
                        />
                        <!-- Badge de verificado para testimonios destacados -->
                        <div 
                          v-if="testimonial.featured"
                          class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center bg-white dark:bg-gray-800 shadow-sm"
                          :class="`text-${primaryColor}-500`"
                        >
                          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                          </svg>
                        </div>
                      </div>
                      <div class="ml-4">
                        <p class="font-bold text-gray-900 dark:text-white">{{ testimonial.name }}</p>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                          <p>{{ testimonial.role }}{{ testimonial.company ? `, ${testimonial.company}` : '' }}</p>
                          <!-- Ubicación si existe -->
                          <p v-if="testimonial.location" class="flex items-center mt-1">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ testimonial.location }}
                          </p>
                        </div>
                      </div>
                    </div>
                    
                    <div class="flex items-center">
                      <!-- Valoración con estrellas mejorada -->
                      <div class="flex mr-4">
                        <template v-for="i in 5" :key="`star-${testimonial.id}-${i}`">
                          <svg 
                            class="w-5 h-5" 
                            :class="[
                              i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600',
                              { 'transform scale-110': i === testimonial.rating }
                            ]" 
                            fill="currentColor" 
                            viewBox="0 0 20 20"
                          >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                          </svg>
                        </template>
                      </div>
                      
                      <!-- Botón de reproducir video si existe -->
                      <button 
                        v-if="testimonial.video"
                        @click="openVideoModal(testimonial.video || '')"
                        class="flex items-center justify-center px-3 py-1.5 rounded-full text-sm font-medium transition-colors duration-300"
                        :class="`bg-${primaryColor}-100 text-${primaryColor}-600 hover:bg-${primaryColor}-200 dark:bg-${primaryColor}-900/30 dark:text-${primaryColor}-400 dark:hover:bg-${primaryColor}-900/50`"
                      >
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                        </svg>
                        Watch
                      </button>
                      
                      <!-- Enlace a social si existe -->
                      <a 
                        v-if="testimonial.socialLink"
                        :href="testimonial.socialLink"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="ml-2 flex items-center justify-center w-8 h-8 rounded-full transition-colors duration-300"
                        :class="`bg-${primaryColor}-50 text-${primaryColor}-500 hover:bg-${primaryColor}-100 dark:bg-${primaryColor}-900/30 dark:text-${primaryColor}-400 dark:hover:bg-${primaryColor}-900/50`"
                      >
                        <svg v-if="testimonial.platform === 'twitter'" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.097 10.097 0 01-3.127 1.195A4.92 4.92 0 0016.953 2a4.91 4.91 0 00-4.925 4.925c0 .388.03.764.114 1.124A14.064 14.064 0 012.083 3.13a4.922 4.922 0 001.524 6.57 4.907 4.907 0 01-2.228-.616v.061a4.915 4.915 0 003.95 4.82 4.904 4.904 0 01-2.221.085A4.922 4.922 0 007.63 17.18a9.87 9.87 0 01-6.115 2.107c-.397 0-.79-.023-1.175-.07a13.929 13.929 0 007.555 2.213c9.056 0 14.01-7.502 14.01-14.01 0-.215-.005-.428-.015-.64a9.9 9.9 0 002.46-2.53l-.047-.02z" />
                        </svg>
                        <svg v-else-if="testimonial.platform === 'linkedin'" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                        </svg>
                        <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.25 8.25h-1.5a.75.75 0 00-.75.75v1.5h2.25v2.25h-2.25v6h-2.25v-6H10.5v-2.25h2.25v-1.5c0-1.24 1.01-2.25 2.25-2.25h2.25v1.5z" />
                        </svg>
                      </a>
                    </div>
                  </div>
                </blockquote>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Controles de navegación mejorados -->
        <!-- Botón de pausa/reproducción de autoplay -->
        <div class="absolute top-6 right-6 z-20">
          <button 
            @click="toggleAutoplay"
            class="p-2 rounded-full transition-colors duration-300"
            :class="`bg-white/70 backdrop-blur-sm dark:bg-gray-800/70 hover:bg-white dark:hover:bg-gray-800 text-${primaryColor}-500 dark:text-${primaryColor}-400 border border-${primaryColor}-100 dark:border-${primaryColor}-800/50`"
            :aria-label="isPaused ? 'Resume autoplay' : 'Pause autoplay'"
          >
            <svg v-if="isPaused" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
            </svg>
            <svg v-else class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
        
        <!-- Flecha izquierda -->
        <div class="absolute top-1/2 -translate-y-1/2 -left-4 md:-left-5 lg:-left-8 z-20">
          <button 
            @click="prevTestimonial"
            class="group w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
            :class="`bg-white dark:bg-gray-800 text-${primaryColor}-500 dark:text-${primaryColor}-400 border border-${primaryColor}-100 dark:border-${primaryColor}-800/50`"
            aria-label="Previous testimonial"
          >
            <svg class="w-5 h-5 md:w-6 md:h-6 transform group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
        </div>
        
        <!-- Flecha derecha -->
        <div class="absolute top-1/2 -translate-y-1/2 -right-4 md:-right-5 lg:-right-8 z-20">
          <button 
            @click="nextTestimonial"
            class="group w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
            :class="`bg-white dark:bg-gray-800 text-${primaryColor}-500 dark:text-${primaryColor}-400 border border-${primaryColor}-100 dark:border-${primaryColor}-800/50`"
            aria-label="Next testimonial"
          >
            <svg class="w-5 h-5 md:w-6 md:h-6 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>
        
        <!-- Indicadores de posición mejorados -->
        <div class="mt-8 flex justify-center space-x-3">
          <button 
            v-for="(_, index) in testimonials" 
            :key="`indicator-${index}`"
            @click="goToTestimonial(index)"
            class="group flex flex-col items-center"
            aria-label="Go to testimonial"
          >
            <span 
              class="block h-2 w-2 md:h-2.5 md:w-2.5 rounded-full transition-all duration-300 mb-1"
              :class="[
                index === activeTestimonialIndex 
                  ? `bg-${primaryColor}-500 dark:bg-${primaryColor}-400 scale-125` 
                  : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400 dark:group-hover:bg-gray-500'
              ]"
            ></span>
            <span 
              v-if="index === activeTestimonialIndex"
              class="absolute mt-5 text-xs font-medium animate-fade-in"
              :class="`text-${primaryColor}-600 dark:text-${primaryColor}-400`"
            >
              {{ index + 1 }}/{{ testimonials.length }}
            </span>
          </button>
        </div>
      </div>
      
      <!-- Testimonios destacados en formato tarjeta (opcional si hay destacados) -->
      <div 
        v-if="featuredTestimonials.length > 0" 
        class="featured-testimonials mt-20 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
      >
        <div 
          v-for="(testimonial, index) in featuredTestimonials" 
          :key="`featured-${testimonial.id}`"
          class="featured-testimonial bg-white dark:bg-gray-800/50 rounded-xl p-6 shadow-md hover:shadow-lg border border-gray-100 dark:border-gray-700 transition-all duration-300 transform hover:-translate-y-1"
        >
          <!-- Cabecera del testimonio con valoración -->
          <div class="flex justify-between items-start mb-4">
            <div class="flex">
              <template v-for="i in 5" :key="`card-star-${testimonial.id}-${i}`">
                <svg 
                  class="w-4 h-4" 
                  :class="i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" 
                  fill="currentColor" 
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </template>
            </div>
            
            <!-- Badge destacado -->
            <div 
              class="px-2 py-1 text-xs font-semibold rounded-full"
              :class="`bg-${primaryColor}-100 text-${primaryColor}-600 dark:bg-${primaryColor}-900/30 dark:text-${primaryColor}-400`"
            >
              Featured
            </div>
          </div>
          
          <!-- Contenido resumido -->
          <p class="text-gray-700 dark:text-gray-300 mb-6 line-clamp-4">
            "{{ testimonial.content }}"
          </p>
          
          <!-- Autor -->
          <div class="flex items-center">
            <img 
              :src="testimonial.image" 
              :alt="testimonial.name"
              class="h-10 w-10 rounded-full object-cover border"
              :class="`border-${primaryColor}-200 dark:border-${primaryColor}-800`"
            />
            <div class="ml-3">
              <p class="font-medium text-gray-900 dark:text-white">{{ testimonial.name }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">{{ testimonial.role }}</p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- CTA final -->
      <div class="mt-16 text-center">
        <a 
          href="#" 
          class="inline-flex items-center px-6 py-3 rounded-xl text-white font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5"
          :class="`bg-gradient-to-r from-${primaryColor}-500 to-${primaryColor}-600 hover:from-${primaryColor}-600 hover:to-${primaryColor}-700`"
        >
          <span>Read More Success Stories</span>
          <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
      </div>
    </div>
    
    <!-- Modal de Video -->
    <transition name="fade">
      <div v-if="videoModal" class="video-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-2xl">
          <!-- Botón de cierre -->
          <button 
            @click="closeVideoModal" 
            class="absolute top-4 right-4 z-20 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <!-- Contenido del modal -->
          <div class="p-0">
            <!-- Video embedido con aspect ratio apropiado -->
            <div class="aspect-video w-full bg-black">
              <iframe 
                v-if="currentVideo"
                class="w-full h-full"
                :src="`https://www.youtube.com/embed/${getYoutubeId(currentVideo)}?autoplay=1`"
                title="Video testimonial"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
              ></iframe>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<style scoped>
/* Animaciones sofisticadas */
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.3s forwards ease-out;
}

/* Limitación de líneas para texto */
.line-clamp-4 {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Mejoras de rendimiento */
.testimonial-active {
  will-change: transform, opacity;
}

.parallax-bg {
  will-change: transform;
}

/* Transiciones suaves */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Soporte para táctil/móvil */
@media (hover: none) {
  /* Estilos específicos para dispositivos táctiles */
  .testimonial-carousel:hover {
    transform: none !important;
  }
}
</style>