<script setup lang="ts">
import { ref, onMounted, reactive, computed, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar plugins de GSAP
gsap.registerPlugin(ScrollTrigger);

// Mouse tracking para efectos de paralaje
const mousePosition = reactive({ x: 0, y: 0 });
const isVideoPlaying = ref(false);
const videoModal = ref(false);
const statsVisible = ref(false);
const testimonialsIndex = ref(0);

// Estadísticas animadas
const stats = reactive([
  { label: 'Bookings Increase', value: 0, target: 40, suffix: '%', color: 'from-green-400 to-emerald-500' },
  { label: 'Time Saved', value: 0, target: 68, suffix: '%', color: 'from-indigo-400 to-indigo-600' },
  { label: 'Client Satisfaction', value: 0, target: 98, suffix: '%', color: 'from-amber-400 to-orange-500' }
]);

// Testiminonios
const testimonials = [
  {
    text: "Spotly transformed our booking process. We've seen a 40% increase in reservations since implementation.",
    author: "Sarah Johnson",
    role: "Hotel Manager",
    avatar: "/images/avatar-1.jpg"
  },
  {
    text: "The real-time dashboard gives us instant visibility into our occupancy rates and helps optimize our schedule.",
    author: "Michael Chen",
    role: "Restaurant Owner",
    avatar: "/images/avatar-2.jpg"
  },
  {
    text: "Customer service is exceptional. The team was responsive and tailored the solution to our specific needs.",
    author: "Jessica Williams",
    role: "Fitness Studio Director",
    avatar: "/images/avatar-3.jpg"
  }
];

// Empresas que confían
const companies = [
  { name: 'Airbnb', logo: '/images/airbnb.svg' },
  { name: 'Spotify', logo: '/images/spotify.svg' },
  { name: 'Slack', logo: '/images/slack.svg' },
  { name: 'Microsoft', logo: '/images/microsoft.svg' },
  { name: 'Uber', logo: '/images/uber.svg' }
];

// Características de flotación
const floatingFeatures = [
  { 
    id: 'bookings',
    title: 'Bookings increased by 40%', 
    icon: 'M5 13l4 4L19 7',
    color: 'green',
    position: { x: -6, y: -6, side: 'left-bottom' }
  },
  { 
    id: 'realtime',
    title: 'Real-time availability updates', 
    icon: 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
    color: 'indigo',
    position: { x: -4, y: -6, side: 'right-top' }
  },
  { 
    id: 'mobile',
    title: 'Mobile-friendly interface', 
    icon: 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    color: 'purple',
    position: { x: 10, y: 20, side: 'right-bottom' }
  }
];

// Animación de contador para estadísticas
const animateStats = () => {
  stats.forEach((stat, index) => {
    gsap.to(stat, {
      value: stat.target,
      duration: 2,
      delay: index * 0.2,
      ease: "power2.out",
      onUpdate: function() {
        stat.value = Math.round(this.targets()[0].value);
      }
    });
  });
};

// Mostrar modal de video
const showVideoModal = () => {
  videoModal.value = true;
  document.body.style.overflow = 'hidden';
  
  // Animación del modal
  gsap.fromTo(".video-modal-backdrop", 
    { opacity: 0 }, 
    { opacity: 1, duration: 0.3 }
  );
  
  gsap.fromTo(".video-modal-content", 
    { opacity: 0, y: 20 }, 
    { opacity: 1, y: 0, duration: 0.4, delay: 0.2 }
  );
};

// Cerrar modal de video
const closeVideoModal = () => {
  gsap.to(".video-modal-content", { 
    opacity: 0, 
    y: 20, 
    duration: 0.3,
    onComplete: () => {
      gsap.to(".video-modal-backdrop", { 
        opacity: 0, 
        duration: 0.3,
        onComplete: () => {
          videoModal.value = false;
          isVideoPlaying.value = false;
          document.body.style.overflow = '';
        }
      });
    }
  });
};

// Rotar testimonios automáticamente
const startTestimonialsRotation = () => {
  setInterval(() => {
    testimonialsIndex.value = (testimonialsIndex.value + 1) % testimonials.length;
  }, 5000);
};

// Efecto de paralaje basado en movimiento del ratón
const handleMouseMove = (e: MouseEvent) => {
  mousePosition.x = e.clientX;
  mousePosition.y = e.clientY;
  
  // Actualizar elementos con paralaje
  const moveElements = document.querySelectorAll('.parallax-element');
  moveElements.forEach((el) => {
    const element = el as HTMLElement;
    const speedX = element.dataset.speedX || "0.05";
    const speedY = element.dataset.speedY || "0.05";
    
    // Calcular desplazamiento
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2;
    
    const deltaX = (mousePosition.x - centerX) * parseFloat(speedX);
    const deltaY = (mousePosition.y - centerY) * parseFloat(speedY);
    
    gsap.to(element, {
      x: deltaX,
      y: deltaY,
      duration: 1,
      ease: "power2.out"
    });
  });
};

// Manejar scroll para animaciones
const setupScrollAnimations = () => {
  // Seleccionar elementos del título para animar
  const titleWords = document.querySelectorAll('.hero-title .word');
  
  // Animación de entrada del título palabra por palabra
  gsap.from(titleWords, {
    opacity: 0,
    y: 20,
    rotationX: -45,
    stagger: 0.1,
    duration: 1,
    ease: "back.out"
  });
  
  // Animación de la descripción
  gsap.from(".hero-description", {
    opacity: 0,
    y: 30,
    duration: 1,
    delay: 0.5
  });
  
  // Animación de los botones CTA
  gsap.from(".cta-button", {
    opacity: 0,
    y: 20,
    stagger: 0.2,
    duration: 0.8,
    delay: 0.8
  });
  
  // Animación de la imagen dashboard
  gsap.from(".dashboard-image", {
    opacity: 0,
    scale: 0.9,
    duration: 1.2,
    delay: 0.5,
    ease: "power2.out"
  });
  
  // Animación de las tarjetas flotantes
  gsap.from(".floating-card", {
    opacity: 0,
    scale: 0.8,
    stagger: 0.2,
    duration: 0.8,
    delay: 1.2
  });
  
  // Revelar estadísticas al hacer scroll
  ScrollTrigger.create({
    trigger: ".stats-section",
    start: "top 80%",
    onEnter: () => {
      statsVisible.value = true;
      animateStats();
    }
  });
  
  // Revelar logotipos de empresas
  gsap.from(".company-logo", {
    scrollTrigger: {
      trigger: ".companies-section",
      start: "top 90%"
    },
    opacity: 0,
    y: 20,
    stagger: 0.1,
    duration: 0.6
  });
};

// Calcular estilo de paralaje para elementos
const getParallaxStyle = (depth: number) => {
  const centerX = window.innerWidth / 2;
  const centerY = window.innerHeight / 2;
  
  const deltaX = (mousePosition.x - centerX) / centerX * depth;
  const deltaY = (mousePosition.y - centerY) / centerY * depth;
  
  return {
    transform: `translate3d(${deltaX}px, ${deltaY}px, 0)`,
    transition: 'transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1)'
  };
};

// Lifecycle hooks
onMounted(() => {
  window.addEventListener('mousemove', handleMouseMove);
  setupScrollAnimations();
  startTestimonialsRotation();
});

onUnmounted(() => {
  window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<template>
  <section class="relative pt-16 pb-24 md:pt-24 md:pb-32 overflow-hidden">
    <!-- Decoraciones de fondo con efecto paralaje -->
    <div class="absolute inset-0 pointer-events-none">
      <div 
        class="parallax-element absolute top-20 right-20 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"
        data-speed-x="0.03" 
        data-speed-y="0.02"
      ></div>
      <div 
        class="parallax-element absolute -bottom-20 -left-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl"
        data-speed-x="0.02" 
        data-speed-y="0.03"
      ></div>
      <div 
        class="parallax-element absolute top-40 left-1/4 w-40 h-40 bg-amber-500/10 rounded-full blur-3xl"
        data-speed-x="-0.02" 
        data-speed-y="-0.01"
      ></div>
      
      <!-- Partículas decorativas -->
      <div class="absolute top-1/4 right-1/4 w-2 h-2 bg-indigo-400 rounded-full opacity-30"></div>
      <div class="absolute top-1/3 left-1/3 w-3 h-3 bg-purple-400 rounded-full opacity-40"></div>
      <div class="absolute bottom-1/4 right-1/3 w-2 h-2 bg-amber-400 rounded-full opacity-30"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center">
        <!-- Columna izquierda: Contenido de texto -->
        <div class="lg:w-1/2 lg:pr-12 z-10">
          <div class="relative">
            <!-- Badge de marca -->
            <div class="inline-flex items-center mb-4 px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
              <span class="flex h-2 w-2 mr-2">
                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-indigo-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
              </span>
              New v2.0 Released
            </div>
            
            <!-- Título principal con animación -->
            <h1 class="hero-title text-5xl md:text-6xl lg:text-7xl font-bold leading-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300">
              <span class="word">Simplify</span> <span class="word">Your</span> <br/>
              <span class="word bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">
                Booking
              </span> <span class="word">Experience</span>
            </h1>
            
            <!-- Descripción principal -->
            <p class="hero-description mt-6 text-xl md:text-2xl text-slate-600 dark:text-slate-400 max-w-xl leading-relaxed">
              The all-in-one reservation system with <span class="font-semibold text-indigo-600 dark:text-indigo-400">AI-powered</span> optimization that transforms how you manage bookings.
            </p>
            
            <!-- Botones de CTA -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
              <!-- Botón principal -->
              <Link
                :href="route('register')"
                class="cta-button group relative px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-center font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden"
              >
                <span class="relative z-10 flex items-center justify-center">
                  Start Free Trial
                  <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                  </svg>
                </span>
                <!-- Efecto de brillo -->
                <span class="absolute inset-0 flex translate-x-[-100%] group-hover:translate-x-[100%] transition-all duration-1000">
                  <span class="w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent"></span>
                </span>
              </Link>
              
              <!-- Botón secundario para demo -->
              <button
                @click="showVideoModal"
                class="cta-button group px-8 py-4 bg-white dark:bg-slate-800 text-center font-medium rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 shadow hover:shadow-md transition-all duration-300 flex items-center justify-center"
              >
                <span class="flex items-center text-slate-800 dark:text-white">
                  <span class="relative flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50 mr-2">
                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z" />
                    </svg>
                    <!-- Onda animada -->
                    <span class="absolute inset-0 rounded-full bg-indigo-200 dark:bg-indigo-700/50 animate-ping opacity-75 group-hover:opacity-100"></span>
                  </span>
                  Watch Demo
                </span>
              </button>
            </div>
            
            <!-- Estadísticas animadas -->
            <div class="stats-section mt-12 grid grid-cols-1 md:grid-cols-3 gap-4">
              <div v-for="(stat, index) in stats" :key="index" class="bg-white dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ stat.label }}</h3>
                <div class="mt-1 flex items-baseline">
                  <p class="text-2xl font-semibold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r" :class="stat.color">
                      {{ stat.value }}{{ stat.suffix }}
                    </span>
                  </p>
                </div>
                <div class="mt-2 w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                  <div 
                    class="h-full rounded-full bg-gradient-to-r" 
                    :class="stat.color"
                    :style="`width: ${statsVisible ? stat.value : 0}%`"
                  ></div>
                </div>
              </div>
            </div>
            
            <!-- Testimonio destacado con rotación -->
            <div class="mt-8 relative overflow-hidden">
              <div class="relative bg-white dark:bg-slate-800/50 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-md">
                <!-- Comillas decorativas -->
                <div class="absolute top-4 left-4 text-4xl text-indigo-200 dark:text-indigo-900 opacity-50 font-serif">
                  "
                </div>
                
                <!-- Contenedor con altura fija para testimonios -->
                <div class="relative min-h-[120px] flex items-center justify-center px-6">
                  <transition 
                    name="testimonial-fade" 
                    mode="out-in"
                  >
                    <div :key="testimonialsIndex" class="text-center">
                      <p class="text-slate-700 dark:text-slate-300 italic">
                        "{{ testimonials[testimonialsIndex].text }}"
                      </p>
                      
                      <div class="mt-4 flex items-center justify-center">
                        <img 
                          :src="testimonials[testimonialsIndex].avatar" 
                          :alt="testimonials[testimonialsIndex].author" 
                          class="w-10 h-10 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900"
                        />
                        <div class="ml-3 text-left">
                          <p class="text-sm font-medium text-slate-800 dark:text-white">
                            {{ testimonials[testimonialsIndex].author }}
                          </p>
                          <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ testimonials[testimonialsIndex].role }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </transition>
                </div>
                
                <!-- Indicadores -->
                <div class="flex justify-center mt-4 space-x-2">
                  <button 
                    v-for="(_, index) in testimonials" 
                    :key="index"
                    @click="testimonialsIndex = index"
                    class="w-2 h-2 rounded-full transition-colors duration-200"
                    :class="testimonialsIndex === index ? 'bg-indigo-500' : 'bg-slate-300 dark:bg-slate-700'"
                  ></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Columna derecha: Previsualización del dashboard -->
        <div class="lg:w-1/2 mt-16 lg:mt-0 relative z-10">
          <div class="relative">
            <!-- Fondo con gradiente -->
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl blur-sm opacity-50 animate-pulse"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-3xl transform rotate-2 scale-105 opacity-10 dark:opacity-20 parallax-element" data-speed-x="0.01" data-speed-y="0.01"></div>
            
            <!-- Imagen del Dashboard -->
            <div class="dashboard-container relative bg-white dark:bg-slate-900 rounded-2xl p-1 border border-slate-200 dark:border-slate-700 shadow-2xl transform hover:scale-[1.01] transition-transform duration-300">
              <img 
                src="/images/dashboard-preview.png" 
                alt="Spotly Dashboard" 
                class="dashboard-image rounded-xl w-full"
              />
              
              <!-- Marco de navegador -->
              <div class="absolute top-0 inset-x-0 h-7 bg-slate-100 dark:bg-slate-800 rounded-t-xl border-b border-slate-200 dark:border-slate-700 flex items-center px-3 space-x-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                <div class="ml-3 flex-1 h-3.5 rounded-full bg-slate-200 dark:bg-slate-700"></div>
              </div>
              
              <!-- Brillo del cristal -->
              <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-transparent rounded-xl pointer-events-none"></div>
            </div>
            
            <!-- Tarjetas de características flotantes -->
            <div 
              v-for="feature in floatingFeatures" 
              :key="feature.id"
              class="floating-card absolute hidden lg:flex bg-white dark:bg-slate-800 rounded-xl p-3 shadow-xl border border-slate-200 dark:border-slate-700 max-w-[200px] transform transition-all duration-300 hover:scale-105 hover:shadow-2xl"
              :class="{
                'left-0 -bottom-6': feature.position.side === 'left-bottom',
                'right-0 -top-6': feature.position.side === 'right-top',
                'right-20 bottom-20': feature.position.side === 'right-bottom',
              }"
              :style="{
                transform: `translate(${feature.position.x}%, ${feature.position.y}%)`
              }"
            >
              <div class="flex items-center">
                <div 
                  class="w-9 h-9 rounded-full flex items-center justify-center mr-3"
                  :class="`bg-${feature.color}-100 dark:bg-${feature.color}-900/30 text-${feature.color}-600 dark:text-${feature.color}-400`"
                >
                  <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                  </svg>
                </div>
                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ feature.title }}</p>
              </div>
            </div>
            
            <!-- Notificación animada de nueva reserva -->
            <div class="absolute -right-4 top-1/2 hidden lg:block transform translate-y-[-50%] animate-float-slow">
              <div class="bg-white dark:bg-slate-800 rounded-xl p-4 shadow-xl border border-slate-200 dark:border-slate-700 max-w-[220px]">
                <div class="flex items-center mb-2">
                  <div class="w-3 h-3 rounded-full bg-green-500 mr-2"></div>
                  <p class="text-sm font-semibold text-slate-800 dark:text-white">New Booking</p>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-300">Tennis Court #3 booked for tomorrow at 3:00 PM</p>
                <div class="mt-2 flex justify-end">
                  <button class="text-xs font-medium text-indigo-600 dark:text-indigo-400">View Details</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Sección de empresas que confían -->
      <div class="companies-section mt-20 md:mt-32">
        <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-400 mb-6">
          TRUSTED BY LEADING COMPANIES
        </p>
        <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
          <div v-for="(company, index) in companies" :key="index" class="company-logo grayscale opacity-50 hover:grayscale-0 hover:opacity-100 transition-all duration-300">
            <img :src="company.logo" :alt="company.name" class="h-6 md:h-8" />
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal de Video -->
    <transition name="fade">
      <div v-if="videoModal" class="video-modal-backdrop fixed inset-0 z-50 bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="video-modal-content relative w-full max-w-4xl bg-slate-900 rounded-2xl overflow-hidden shadow-2xl">
          <!-- Cerrar botón -->
          <button 
            @click="closeVideoModal" 
            class="absolute top-4 right-4 z-10 p-2 rounded-full bg-black/50 text-white hover:bg-black/70 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
          
          <!-- Reproductor de video -->
          <div class="aspect-video bg-slate-800">
            <div v-if="!isVideoPlaying" class="w-full h-full flex flex-col items-center justify-center">
              <button 
                @click="isVideoPlaying = true" 
                class="w-20 h-20 rounded-full bg-indigo-600 flex items-center justify-center text-white hover:bg-indigo-700 transition-colors"
              >
                <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z" />
                </svg>
              </button>
              <p class="mt-4 text-white text-lg font-medium">See how Spotly works</p>
            </div>
            <iframe 
              v-else
              class="w-full h-full"
              src="https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1"
              title="Demo Video"
              frameborder="0"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen
            ></iframe>
          </div>
        </div>
      </div>
    </transition>
  </section>
</template>

<style scoped>
/* Animaciones avanzadas */
@keyframes float-slow {
  0% { transform: translate(0, -50%) translateY(-10px); }
  50% { transform: translate(0, -50%) translateY(10px); }
  100% { transform: translate(0, -50%) translateY(-10px); }
}

.animate-float-slow {
  animation: float-slow 7s ease-in-out infinite;
}

/* Transiciones de componentes */
.testimonial-fade-enter-active,
.testimonial-fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.testimonial-fade-enter-from,
.testimonial-fade-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Mejoras de rendimiento para animaciones */
.dashboard-container {
  will-change: transform;
  transform: translateZ(0);
}

.parallax-element {
  will-change: transform;
}
</style>