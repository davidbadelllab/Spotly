<script setup lang="ts">
import { ref, reactive, onMounted, onUnmounted, computed, watch } from 'vue';
import { Link } from '@inertiajs/vue3';
import gsap from 'gsap';

// Definición de tipos para una arquitectura más robusta
interface NavigationItem {
  id: string;
  label: string;
  href: string;
  description: string;
  isNew?: boolean;
  icon: string;
  badgeText?: string;
  badgeColor?: string;
}

// Estado global y configuración
const theme = ref(localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));
const mobileMenuOpen = ref(false);
const isScrolled = ref(false);
const isSearchOpen = ref(false);
const searchQuery = ref('');
const mousePosition = reactive({ x: 0, y: 0, xVelocity: 0, yVelocity: 0, lastX: 0, lastY: 0 });
const hoveredItem = ref<string | null>(null);
const activeSection = ref<string | null>(null);
const notificationCount = ref(3);
const showMegaMenu = ref<string | null>(null);

// Aplicación de tema
watch(theme, (newTheme) => {
  if (newTheme === 'dark') {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  localStorage.setItem('theme', newTheme);
});

// Items de navegación con metadatos enriquecidos
const navigationItems: NavigationItem[] = [
  {
    id: 'features',
    label: 'Features',
    href: '#features',
    description: 'Discover all our advanced platform capabilities',
    isNew: true,
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
    badgeText: 'New',
    badgeColor: 'from-emerald-400 to-teal-500'
  },
  {
    id: 'solutions',
    label: 'Solutions',
    href: '#solutions',
    description: 'Tailored options for businesses of all sizes',
    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
    badgeText: 'Popular',
    badgeColor: 'from-amber-400 to-orange-500'
  },
  {
    id: 'testimonials',
    label: 'Testimonials',
    href: '#testimonials',
    description: 'Success stories from our satisfied customers',
    icon: 'M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  },
  {
    id: 'pricing',
    label: 'Pricing',
    href: '#pricing',
    description: 'Flexible plans that grow with your business',
    icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
  }
];

// Event handlers
const toggleTheme = () => {
  // Animación sofisticada de cambio de tema
  const darkModeElements = document.querySelectorAll('.dark-mode-transition');
  
  if (theme.value === 'light') {
    gsap.to(darkModeElements, {
      backgroundColor: '#0f172a',
      color: '#f8fafc',
      duration: 0.5,
      stagger: 0.05,
      ease: 'power2.inOut'
    });
    theme.value = 'dark';
  } else {
    gsap.to(darkModeElements, {
      backgroundColor: '#ffffff',
      color: '#0f172a',
      duration: 0.5,
      stagger: 0.05,
      ease: 'power2.inOut'
    });
    theme.value = 'light';
  }
};

const toggleMobileMenu = () => {
  if (!mobileMenuOpen.value) {
    // Abrir menú con animación
    mobileMenuOpen.value = true;
    gsap.fromTo(
      '.mobile-menu-container',
      { opacity: 0, y: -20 },
      { opacity: 1, y: 0, duration: 0.4, ease: 'power3.out' }
    );
    
    // Animar elementos del menú secuencialmente
    gsap.fromTo(
      '.mobile-menu-item',
      { opacity: 0, x: -20 },
      { opacity: 1, x: 0, duration: 0.3, stagger: 0.05, ease: 'power2.out', delay: 0.2 }
    );
    
    // Bloquear scroll de página
    document.body.style.overflow = 'hidden';
  } else {
    // Cerrar menú con animación
    gsap.to('.mobile-menu-container', {
      opacity: 0,
      y: -20,
      duration: 0.3,
      ease: 'power3.in',
      onComplete: () => {
        mobileMenuOpen.value = false;
        document.body.style.overflow = '';
      }
    });
  }
};

const toggleSearch = () => {
  isSearchOpen.value = !isSearchOpen.value;
  
  if (isSearchOpen.value) {
    // Expandir barra de búsqueda
    gsap.fromTo(
      '.search-container',
      { width: 0, opacity: 0 },
      { width: 'auto', opacity: 1, duration: 0.4, ease: 'power3.out', onComplete: () => {
        document.querySelector('.search-input')?.focus();
      }}
    );
  } else {
    // Contraer barra de búsqueda
    gsap.to('.search-container', {
      width: 0,
      opacity: 0,
      duration: 0.3,
      ease: 'power3.in'
    });
  }
};

const handleMouseMove = (e: MouseEvent) => {
  // Guardar posición anterior
  mousePosition.lastX = mousePosition.x;
  mousePosition.lastY = mousePosition.y;
  
  // Actualizar posición actual
  mousePosition.x = e.clientX;
  mousePosition.y = e.clientY;
  
  // Calcular velocidad
  mousePosition.xVelocity = mousePosition.x - mousePosition.lastX;
  mousePosition.yVelocity = mousePosition.y - mousePosition.lastY;
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
  
  // Detectar sección activa
  const sections = document.querySelectorAll('section[id]');
  const scrollPosition = window.scrollY + 100;
  
  sections.forEach(section => {
    const sectionTop = (section as HTMLElement).offsetTop;
    const sectionHeight = (section as HTMLElement).offsetHeight;
    
    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
      activeSection.value = section.getAttribute('id');
    }
  });
};

// Establecer sección activa al hover
const setHoveredItem = (id: string | null) => {
  hoveredItem.value = id;
  
  if (id) {
    gsap.to(`.nav-indicator-${id}`, {
      width: '100%',
      duration: 0.3,
      ease: 'power2.out'
    });
  }
};

// Mostrar/ocultar mega menú
const toggleMegaMenu = (id: string | null) => {
  // Si ya está abierto el mismo menú, cerrarlo
  if (showMegaMenu.value === id) {
    gsap.to(`.mega-menu-${id}`, {
      opacity: 0,
      y: -10,
      duration: 0.3,
      ease: 'power3.in',
      onComplete: () => {
        showMegaMenu.value = null;
      }
    });
    return;
  }
  
  // Si hay otro menú abierto, cerrarlo primero
  if (showMegaMenu.value) {
    gsap.to(`.mega-menu-${showMegaMenu.value}`, {
      opacity: 0,
      y: -10,
      duration: 0.2,
      ease: 'power3.in',
      onComplete: () => {
        // Luego abrir el nuevo
        showMegaMenu.value = id;
        if (id) {
          gsap.fromTo(
            `.mega-menu-${id}`,
            { opacity: 0, y: -10 },
            { opacity: 1, y: 0, duration: 0.3, ease: 'power3.out' }
          );
        }
      }
    });
  } else {
    // No hay menú abierto, abrir el nuevo directamente
    showMegaMenu.value = id;
    if (id) {
      gsap.fromTo(
        `.mega-menu-${id}`,
        { opacity: 0, y: -10 },
        { opacity: 1, y: 0, duration: 0.3, ease: 'power3.out' }
      );
    }
  }
};

// Animaciones iniciales y efectos de inercia para logo
let logoRotationX = 0;
let logoRotationY = 0;

const updateLogoEffect = () => {
  // Efecto de inercia para una experiencia más natural
  logoRotationX += (mousePosition.yVelocity * 0.2 - logoRotationX) * 0.1;
  logoRotationY += (mousePosition.xVelocity * 0.2 - logoRotationY) * 0.1;
  
  // Limitar la rotación a un rango razonable
  logoRotationX = Math.max(-10, Math.min(10, logoRotationX));
  logoRotationY = Math.max(-10, Math.min(10, logoRotationY));
  
  const logoElement = document.querySelector('.logo-3d');
  if (logoElement) {
    logoElement.setAttribute('style', `transform: perspective(1000px) rotateX(${-logoRotationX}deg) rotateY(${logoRotationY}deg)`);
  }
};

// Efectos de paralaje
const getParallaxStyle = (factor: number) => {
  const centerX = window.innerWidth / 2;
  const centerY = window.innerHeight / 2;
  
  const deltaX = (mousePosition.x - centerX) / centerX * factor;
  const deltaY = (mousePosition.y - centerY) / centerY * factor;
  
  return {
    transform: `translate3d(${deltaX}px, ${deltaY}px, 0)`,
    transition: 'transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1)'
  };
};

// Filtrado de items de navegación
const filteredNavigationItems = computed(() => {
  if (!searchQuery.value) return navigationItems;
  
  const query = searchQuery.value.toLowerCase();
  return navigationItems.filter(item => 
    item.label.toLowerCase().includes(query) ||
    item.description.toLowerCase().includes(query)
  );
});

// Estilos CSS dinámicos
const headerClasses = computed(() => {
  return {
    'bg-white/90 dark:bg-slate-900/95 shadow-lg backdrop-blur-xl': isScrolled.value,
    'bg-white/40 dark:bg-slate-900/20 backdrop-blur-sm': !isScrolled.value,
    'border-b border-slate-200/50 dark:border-slate-800/50': isScrolled.value
  };
});

// Hook de ciclo de vida
onMounted(() => {
  // Aplicar tema inicial
  if (theme.value === 'dark') {
    document.documentElement.classList.add('dark');
  }
  
  // Agregar event listeners
  window.addEventListener('scroll', handleScroll);
  window.addEventListener('mousemove', handleMouseMove);
  
  // Animar entrada de elementos al cargar
  gsap.fromTo(
    '.header-logo', 
    { y: -30, opacity: 0 }, 
    { y: 0, opacity: 1, duration: 0.8, ease: 'elastic.out(1, 0.5)' }
  );
  
  gsap.fromTo(
    '.nav-item', 
    { y: -20, opacity: 0 }, 
    { y: 0, opacity: 1, stagger: 0.1, duration: 0.5, ease: 'power3.out', delay: 0.2 }
  );
  
  gsap.fromTo(
    '.auth-buttons', 
    { x: 20, opacity: 0 }, 
    { x: 0, opacity: 1, duration: 0.5, ease: 'power3.out', delay: 0.6 }
  );
  
  // Iniciar ticker para animación continua
  gsap.ticker.add(updateLogoEffect);
});

onUnmounted(() => {
  // Limpiar event listeners
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('mousemove', handleMouseMove);
  gsap.ticker.remove(updateLogoEffect);
});
</script>

<template>
  <header 
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 ease-out dark-mode-transition"
    :class="headerClasses"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20 relative">
        <!-- Logo 3D con Efectos Avanzados -->
        <div class="flex items-center header-logo">
          <!-- Logo con efecto 3D interactivo -->
          <div 
            class="logo-3d w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 
                  flex items-center justify-center mr-3 shadow-lg relative overflow-hidden
                  hover:shadow-indigo-500/20 dark:hover:shadow-indigo-400/20 
                  transition-all duration-300 transform group"
          >
            <!-- Fondo con gradiente animado -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-100 
                        group-hover:from-indigo-400 group-hover:to-purple-500
                        transition-all duration-500"></div>
            
            <!-- Efecto de brillo -->
            <div class="absolute inset-0 bg-gradient-to-tr from-white/20 to-transparent opacity-0 
                       group-hover:opacity-100 transition-opacity duration-300 blur-sm"></div>
            
            <!-- Partículas decorativas -->
            <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
              <div class="absolute top-1/4 left-1/4 w-1 h-1 rounded-full bg-white/60 
                         opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              <div class="absolute bottom-1/3 right-1/4 w-1.5 h-1.5 rounded-full bg-white/70 
                         opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100"></div>
              <div class="absolute top-1/2 right-1/3 w-1 h-1 rounded-full bg-white/60 
                         opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200"></div>
            </div>
            
            <!-- Logo SVG con animación -->
            <svg 
              width="28" 
              height="28" 
              viewBox="0 0 24 24" 
              fill="none" 
              xmlns="http://www.w3.org/2000/svg" 
              class="text-white z-10 relative transform transition-transform duration-700 
                     group-hover:rotate-180 group-hover:scale-110"
            >
              <path d="M12 3L21 18H3L12 3Z" fill="currentColor" />
              <circle 
                cx="12" 
                cy="14" 
                r="2" 
                fill="white" 
                class="transform scale-0 group-hover:scale-100 transition-transform duration-500"
              />
            </svg>
            
            <!-- Halo externo animado -->
            <div class="absolute inset-0 -m-1 rounded-xl bg-indigo-500/20 opacity-0 
                       group-hover:opacity-100 group-hover:animate-pulse-ring"></div>
          </div>
          
          <!-- Texto del Logo con Degradado -->
          <div class="flex flex-col">
            <span 
              class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r 
                     from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400 
                     transition-colors duration-500 tracking-tight"
            >
              Spotly
            </span>
            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium -mt-1 tracking-wide">
              RESEVATION SYSTEM
            </span>
          </div>
        </div>
        
        <!-- Navegación Desktop Avanzada -->
        <nav class="hidden lg:flex items-center space-x-8">
          <div v-for="item in navigationItems" :key="item.id" class="relative group">
            <!-- Botón de navegación con hover effect -->
            <button 
              @click="() => toggleMegaMenu(item.id)"
              @mouseenter="setHoveredItem(item.id)"
              @mouseleave="setHoveredItem(null)"
              class="nav-item flex items-center py-2 space-x-1.5 group"
              :class="{
                'text-indigo-600 dark:text-indigo-400': activeSection === item.id || showMegaMenu === item.id,
                'text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400': 
                  activeSection !== item.id && showMegaMenu !== item.id
              }"
            >
              <!-- Icono -->
              <div class="relative">
                <svg 
                  class="w-5 h-5 transition-colors duration-300" 
                  :class="{
                    'text-indigo-600 dark:text-indigo-400': activeSection === item.id || hoveredItem === item.id || showMegaMenu === item.id,
                    'text-gray-500 dark:text-gray-400': activeSection !== item.id && hoveredItem !== item.id && showMegaMenu !== item.id
                  }"
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24" 
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                </svg>
              </div>
              
              <!-- Etiqueta del menú -->
              <span class="text-sm font-medium transition-colors duration-300">{{ item.label }}</span>
              
              <!-- Badge condicional -->
              <span 
                v-if="item.badgeText"
                class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium
                       text-white shadow-sm"
                :class="`bg-gradient-to-r ${item.badgeColor}`"
              >
                {{ item.badgeText }}
              </span>
              
              <!-- Flecha de dropdown -->
              <svg 
                class="w-4 h-4 transition-transform duration-300"
                :class="{ 'rotate-180': showMegaMenu === item.id }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24" 
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
              
              <!-- Indicador de sección activa -->
              <div 
                :class="`nav-indicator-${item.id} absolute bottom-0 left-0 h-0.5 bg-indigo-500 
                         dark:bg-indigo-400 transition-all duration-300`"
                :style="{
                  width: activeSection === item.id || showMegaMenu === item.id ? '100%' : '0%'
                }"
              ></div>
            </button>
            
            <!-- Mega Menu Avanzado -->
            <div 
              v-if="showMegaMenu === item.id"
              :class="`mega-menu-${item.id} absolute top-full left-1/2 transform -translate-x-1/2 
                      w-screen max-w-3xl p-6 mt-2 rounded-xl
                      bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl shadow-2xl
                      border border-slate-200/60 dark:border-slate-700/60
                      grid grid-cols-2 gap-6 z-50`"
            >
              <!-- Sección Izquierda -->
              <div class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                  {{ item.label }}
                </h3>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                  {{ item.description }}
                </p>
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/30 
                           dark:to-purple-900/30 rounded-lg p-4">
                  <ul class="space-y-3">
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 mt-0.5 mr-2"
                           fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm text-slate-700 dark:text-slate-300">
                        Premium enterprise support
                      </span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 mt-0.5 mr-2"
                           fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm text-slate-700 dark:text-slate-300">
                        Advanced analytics dashboard
                      </span>
                    </li>
                    <li class="flex items-start">
                      <svg class="w-5 h-5 text-indigo-500 dark:text-indigo-400 mt-0.5 mr-2"
                           fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span class="text-sm text-slate-700 dark:text-slate-300">
                        Dedicated account manager
                      </span>
                    </li>
                  </ul>
                </div>
              </div>
              
              <!-- Sección Derecha -->
              <div class="space-y-4">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                  Popular Resources
                </h3>
                <div class="space-y-3">
                  <a href="#" class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 
                                    hover:bg-indigo-50 dark:hover:bg-indigo-900/20 
                                    transition-colors duration-300 group">
                    <div class="flex-shrink-0 mr-3 w-10 h-10 rounded-md bg-indigo-100 dark:bg-indigo-900/50 
                               flex items-center justify-center">
                      <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" 
                           stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                      </svg>
                    </div>
                    <div>
                      <h4 class="text-sm font-medium text-slate-900 dark:text-slate-100 
                                group-hover:text-indigo-600 dark:group-hover:text-indigo-400 
                                transition-colors duration-300">
                        Documentation
                      </h4>
                      <p class="text-xs text-slate-500 dark:text-slate-400">
                        Learn how to integrate our platform
                      </p>
                    </div>
                  </a>
                  
                  <a href="#" class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-800/60 
                                    hover:bg-indigo-50 dark:hover:bg-indigo-900/20 
                                    transition-colors duration-300 group">
                    <div class="flex-shrink-0 mr-3 w-10 h-10 rounded-md bg-indigo-100 dark:bg-indigo-900/50 
                               flex items-center justify-center">
                      <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" 
                           stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                      </svg>
                    </div>
                    <div>
                      <h4 class="text-sm font-medium text-slate-900 dark:text-slate-100 
                                group-hover:text-indigo-600 dark:group-hover:text-indigo-400 
                                transition-colors duration-300">
                        Video Tutorials
                      </h4>
                      <p class="text-xs text-slate-500 dark:text-slate-400">
                        Watch step-by-step tutorials
                      </p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </nav>
        
        <!-- Panel de Control -->
        <div class="hidden md:flex items-center space-x-3 mr-4">
          <!-- Búsqueda Expandible -->
          <div class="relative">
            <button 
              @click="toggleSearch"
              class="p-2 rounded-full text-slate-500 hover:text-slate-700 dark:text-slate-400 
                     dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 
                     transition-colors duration-300 focus:outline-none"
              aria-label="Search"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" 
                />
              </svg>
            </button>
            
            <!-- Contenedor de Búsqueda Expandible -->
            <div 
              v-if="isSearchOpen"
              class="search-container absolute right-0 mt-2 w-64 bg-white dark:bg-slate-800 
                     rounded-lg shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700
                     z-50"
            >
              <div class="flex items-center px-3 py-2 border-b border-slate-200 dark:border-slate-700">
                <svg 
                  class="w-5 h-5 text-slate-400" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24" 
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2" 
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" 
                  />
                </svg>
                <input
                  v-model="searchQuery"
                  class="search-input w-full px-2 py-1 text-sm text-slate-900 dark:text-slate-200 
                         bg-transparent focus:outline-none"
                  placeholder="Search..."
                  autocomplete="off"
                />
              </div>
              
              <!-- Resultados de Búsqueda -->
              <div 
                v-if="searchQuery && filteredNavigationItems.length > 0"
                class="max-h-60 overflow-y-auto"
              >
                <div 
                  v-for="item in filteredNavigationItems" 
                  :key="item.id"
                  class="px-4 py-2 hover:bg-slate-50 dark:hover:bg-slate-700 
                         transition-colors duration-200 cursor-pointer"
                  @click="isSearchOpen = false"
                >
                  <div class="font-medium text-slate-900 dark:text-slate-200">{{ item.label }}</div>
                  <div class="text-xs text-slate-500 dark:text-slate-400">{{ item.description }}</div>
                </div>
              </div>
              
              <div 
                v-else-if="searchQuery" 
                class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400"
              >
                No results found
              </div>
            </div>
          </div>
          
          <!-- Toggle de Tema con Animación -->
          <button 
            @click="toggleTheme"
            class="p-2 rounded-full text-slate-500 hover:text-slate-700 dark:text-slate-400 
                   dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 
                   transition-colors duration-300 focus:outline-none"
            aria-label="Toggle theme"
          >
            <svg 
              v-if="theme === 'light'" 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
              />
            </svg>
            <svg 
              v-else 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" 
              />
            </svg>
          </button>
          
          <!-- Notificaciones con Badge -->
          <div class="relative">
            <button 
              class="p-2 rounded-full text-slate-500 hover:text-slate-700 dark:text-slate-400 
                     dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 
                     transition-colors duration-300 focus:outline-none"
              aria-label="Notifications"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" 
                />
              </svg>
              
              <!-- Badge de Notificaciones Animado -->
              <span 
                v-if="notificationCount > 0"
                class="absolute top-0 right-0 -mt-1 -mr-1 flex h-4 w-4 items-center justify-center 
                       rounded-full bg-rose-500 text-[10px] font-bold text-white
                       animate-pulse"
              >
                {{ notificationCount }}
              </span>
            </button>
          </div>
        </div>
        
        <!-- Authentication Links Mejorados -->
        <div class="auth-buttons flex items-center space-x-3">
          <Link
            :href="route('login')"
            class="hidden sm:flex text-sm font-medium px-4 py-2 rounded-lg 
                   text-slate-600 dark:text-slate-300
                   hover:bg-slate-100 dark:hover:bg-slate-800 
                   border border-transparent hover:border-slate-200 dark:hover:border-slate-700
                   transition-all duration-300 group"
          >
            <span class="group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-300">
              Sign In
            </span>
          </Link>
          
          <!-- Botón CTA Principal con Efectos Avanzados -->
          <Link
            :href="route('register')"
            class="relative overflow-hidden text-sm font-medium px-5 py-2.5 
                   bg-gradient-to-br from-indigo-500 to-purple-600 
                   hover:from-indigo-600 hover:to-purple-700
                   dark:from-indigo-600 dark:to-purple-700
                   dark:hover:from-indigo-500 dark:hover:to-purple-600
                   text-white rounded-lg shadow-lg
                   transform hover:-translate-y-0.5 
                   hover:shadow-indigo-500/20 dark:hover:shadow-indigo-400/20
                   transition-all duration-300 group"
          >
            <!-- Texto del Botón -->
            <span class="relative z-10">Get Started</span>
            
            <!-- Efecto de Brillo -->
            <div 
              class="absolute inset-0 -translate-x-full group-hover:translate-x-full 
                     bg-gradient-to-r from-transparent via-white/20 to-transparent
                     transition-transform duration-1000 ease-in-out"
            ></div>
            
            <!-- Efecto de Ondas -->
            <div 
              class="absolute inset-0 opacity-0 
                     bg-gradient-to-br from-white/10 via-white/5 to-transparent 
                     scale-0 group-hover:scale-150 group-hover:opacity-100 
                     transition-all duration-700 rounded-full"
            ></div>
          </Link>
        </div>
        
        <!-- Mobile Menu Button Mejorado -->
        <button 
          @click="toggleMobileMenu" 
          aria-label="Toggle mobile menu"
          class="lg:hidden p-2 rounded-lg 
                 hover:bg-slate-100 dark:hover:bg-slate-800 
                 transition-colors duration-300 
                 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50"
        >
          <div class="w-6 h-6 relative flex items-center justify-center">
            <!-- Líneas Animadas del Menú Hamburguesa -->
            <span 
              class="absolute w-6 h-0.5 bg-slate-900 dark:bg-slate-100 transition-all duration-300 ease-out"
              :class="mobileMenuOpen ? 'rotate-45' : '-translate-y-1.5'"
            ></span>
            <span 
              class="absolute w-6 h-0.5 bg-slate-900 dark:bg-slate-100 transition-all duration-300 ease-out"
              :class="mobileMenuOpen ? 'opacity-0' : 'opacity-100'"
            ></span>
            <span 
              class="absolute w-6 h-0.5 bg-slate-900 dark:bg-slate-100 transition-all duration-300 ease-out"
              :class="mobileMenuOpen ? '-rotate-45' : 'translate-y-1.5'"
            ></span>
          </div>
        </button>
      </div>
    </div>
    
    <!-- Mobile Menu Avanzado -->
    <div 
      v-if="mobileMenuOpen" 
      class="mobile-menu-container lg:hidden absolute inset-x-0 top-full 
             bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl
             border-b border-slate-200 dark:border-slate-800 
             shadow-2xl z-50"
    >
      <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Barra de búsqueda móvil -->
        <div class="relative mb-6">
          <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden bg-slate-50 dark:bg-slate-800/50">
            <svg 
              xmlns="http://www.w3.org/2000/svg" 
              class="h-5 w-5 mx-3 text-slate-400" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" 
              />
            </svg>
            <input
              v-model="searchQuery"
              class="w-full p-3 text-sm text-slate-900 dark:text-slate-200 
                     bg-transparent focus:outline-none"
              placeholder="Search features, solutions, etc..."
              autocomplete="off"
            />
          </div>
        </div>
        
        <!-- Navegación Móvil Moderna -->
        <nav class="grid gap-3">
          <a 
            v-for="(item, index) in filteredNavigationItems" 
            :key="item.id"
            :href="item.href" 
            @click="toggleMobileMenu"
            class="mobile-menu-item flex items-center space-x-4 p-4 rounded-xl 
                   bg-white dark:bg-slate-800/30
                   hover:bg-indigo-50 dark:hover:bg-indigo-900/20 
                   border border-slate-100 dark:border-slate-800
                   shadow-sm hover:shadow
                   transform hover:translate-x-1 
                   transition-all duration-300"
            :style="`animation-delay: ${index * 0.05}s`"
            role="menuitem"
          >
            <div 
              class="w-12 h-12 rounded-xl flex items-center justify-center
                     bg-indigo-100 dark:bg-indigo-900/30"
            >
              <svg 
                class="w-6 h-6 text-indigo-600 dark:text-indigo-400" 
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24" 
                xmlns="http://www.w3.org/2000/svg"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  :d="item.icon" 
                />
              </svg>
            </div>
            <div class="flex-1">
              <div class="flex items-center">
                <p 
                  class="text-base font-medium text-slate-900 dark:text-slate-100"
                >
                  {{ item.label }}
                </p>
                <!-- Badge condicional -->
                <span 
                  v-if="item.badgeText"
                  class="ml-2 px-2 py-0.5 text-xs font-medium text-white rounded-full"
                  :class="`bg-gradient-to-r ${item.badgeColor}`"
                >
                  {{ item.badgeText }}
                </span>
              </div>
              <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                {{ item.description }}
              </p>
            </div>
            <svg 
              class="w-5 h-5 text-slate-400" 
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24" 
              xmlns="http://www.w3.org/2000/svg"
            >
              <path 
                stroke-linecap="round" 
                stroke-linejoin="round" 
                stroke-width="2" 
                d="M9 5l7 7-7 7" 
              />
            </svg>
          </a>
        </nav>
        
        <!-- Footer del menú móvil -->
        <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-800">
          <div class="flex items-center justify-between">
            <!-- Control de tema móvil -->
            <button 
              @click="toggleTheme"
              class="flex items-center space-x-2 px-4 py-2 rounded-lg
                     text-slate-600 dark:text-slate-300
                     hover:bg-slate-100 dark:hover:bg-slate-800 
                     transition-colors duration-300 focus:outline-none"
            >
              <svg 
                v-if="theme === 'light'" 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 text-amber-500" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" 
                />
              </svg>
              <svg 
                v-else 
                xmlns="http://www.w3.org/2000/svg" 
                class="h-5 w-5 text-indigo-400" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor"
              >
                <path 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  stroke-width="2" 
                  d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" 
                />
              </svg>
              <span>{{ theme === 'light' ? 'Dark Mode' : 'Light Mode' }}</span>
            </button>
            
            <!-- Botones de autenticación móvil -->
            <div class="flex space-x-3">
              <Link
                :href="route('login')"
                class="text-sm font-medium px-4 py-2 rounded-lg 
                       text-slate-600 dark:text-slate-300
                       hover:bg-slate-100 dark:hover:bg-slate-800 
                       transition-colors duration-300"
              >
                Sign In
              </Link>
              <Link
                :href="route('register')"
                class="text-sm font-medium px-4 py-2 
                       bg-gradient-to-br from-indigo-500 to-purple-600
                       text-white rounded-lg shadow
                       hover:shadow-md
                       transition-all duration-300"
              >
                Get Started
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Decoraciones de Fondo con Efectos de Paralaje -->
    <div 
      class="absolute inset-0 pointer-events-none overflow-hidden"
      aria-hidden="true"
    >
      <!-- Elementos decorativos de fondo con paralaje -->
      <div 
        class="absolute -top-20 right-0 w-72 h-72 bg-indigo-400/5 dark:bg-indigo-400/10 rounded-full blur-3xl"
        :style="getParallaxStyle(5)"
      ></div>
      <div 
        class="absolute -left-10 top-10 w-72 h-72 bg-purple-400/5 dark:bg-purple-400/10 rounded-full blur-3xl"
        :style="getParallaxStyle(3)"
      ></div>
    </div>
  </header>
</template>

<style scoped>
/* Base de animaciones con optimización de rendimiento */
@keyframes fade-in-down {
  from {
    opacity: 0;
    transform: translate3d(0, -20px, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

@keyframes slide-in-right {
  from {
    opacity: 0;
    transform: translate3d(20px, 0, 0);
  }
  to {
    opacity: 1;
    transform: translate3d(0, 0, 0);
  }
}

@keyframes pulse-ring {
  0% {
    transform: scale(0.8);
    opacity: 0.5;
  }
  50% {
    opacity: 0.2;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}

@keyframes shimmer {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(100%);
  }
}

/* Aplicación de animaciones con optimización hardware */
.mobile-menu-item {
  animation: slide-in-right 0.4s ease forwards;
  opacity: 0;
  will-change: transform, opacity;
}

.animate-pulse-ring {
  animation: pulse-ring 2s cubic-bezier(0.21, 0.61, 0.36, 1) infinite;
}

/* Mejoras de rendimiento y transiciones suaves */
.search-container {
  transform: translateZ(0);
  backface-visibility: hidden;
  will-change: transform, opacity, width;
}

.logo-3d {
  transform: perspective(1000px);
  will-change: transform;
}

/* Optimizaciones para interfaces táctiles */
@media (hover: hover) {
  .hover-effect:hover {
    transform: translateY(-2px) scale(1.01);
  }
}
</style>