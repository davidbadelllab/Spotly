<script setup lang="ts">
import { ref, onMounted } from 'vue';

const currentYear = new Date().getFullYear();
const isVisible = ref(false);

// Animación de entrada al cargar
onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 100);
});

// Enlaces para las secciones del footer
const solutions = [
  { name: 'Sports Facilities', href: '#' },
  { name: 'Hotels', href: '#' },
  { name: 'Restaurants', href: '#' },
  { name: 'Event Spaces', href: '#' },
];

const company = [
  { name: 'About', href: '#' },
  { name: 'Careers', href: '#' },
  { name: 'Blog', href: '#' },
  { name: 'Contact', href: '#' },
];

const legalLinks = [
  { name: 'Privacy Policy', href: '#' },
  { name: 'Terms of Service', href: '#' },
  { name: 'Cookies', href: '#' },
];

// Función para hover de los íconos
const hoveredIcon = ref(null);
const setHoveredIcon = (index) => {
  hoveredIcon.value = index;
};
const clearHoveredIcon = () => {
  hoveredIcon.value = null;
};
</script>

<template>
  <footer class="relative bg-white dark:bg-[#0A0A0A] border-t border-[#e8e8e6] dark:border-[#111111] overflow-hidden">
    <!-- Elementos decorativos en el fondo -->
    <div class="absolute inset-0 overflow-hidden opacity-5 dark:opacity-10 pointer-events-none">
      <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 blur-3xl"></div>
      <div class="absolute -left-24 bottom-0 w-80 h-80 rounded-full bg-gradient-to-tr from-indigo-500 to-blue-600 blur-3xl"></div>
    </div>
    
    <!-- Grid de puntos en el fondo -->
    <div class="absolute inset-0 bg-grid-pattern pointer-events-none opacity-10 dark:opacity-20"></div>

    <div 
      :class="['relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 transition-all duration-1000', 
               isVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-6']"
    >
      <div class="grid grid-cols-1 md:grid-cols-12 gap-x-8 gap-y-12">
        <!-- Columna de información de la empresa -->
        <div class="md:col-span-5 space-y-6">
          <div 
            class="flex items-center transform transition-all duration-500"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <div class="relative w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3 shadow-lg group">
              <div class="absolute inset-0 rounded-xl bg-gradient-to-br from-indigo-600 to-purple-700 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white relative z-10">
                <path d="M12 16L6 10H18L12 16Z" fill="currentColor" />
              </svg>
            </div>
            <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300">Spotly</span>
          </div>
          
          <p 
            class="text-[#706F6C] dark:text-[#A1A09A] max-w-md text-base leading-relaxed transition-all duration-700 delay-100"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            The all-in-one reservation system for sports facilities, hotels, and restaurants. 
            Streamline your bookings, maximize occupancy, and deliver exceptional experiences.
          </p>
          
          <div 
            class="flex space-x-5 transition-all duration-700 delay-200"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <a 
              href="#" 
              class="relative group flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 transition-all duration-300 hover:bg-indigo-100 dark:hover:bg-indigo-900"
              @mouseenter="setHoveredIcon(0)"
              @mouseleave="clearHoveredIcon()"
            >
              <span class="absolute inset-0 rounded-full bg-indigo-500 transition-all duration-300 transform scale-0 group-hover:scale-100 opacity-0 group-hover:opacity-20"></span>
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" 
                   class="transition-all duration-300"
                   :class="hoveredIcon === 0 ? 'text-indigo-600 dark:text-indigo-400 scale-110' : 'text-gray-500 dark:text-gray-400'">
                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
              </svg>
            </a>
            <a 
              href="#" 
              class="relative group flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 transition-all duration-300 hover:bg-indigo-100 dark:hover:bg-indigo-900"
              @mouseenter="setHoveredIcon(1)"
              @mouseleave="clearHoveredIcon()"
            >
              <span class="absolute inset-0 rounded-full bg-indigo-500 transition-all duration-300 transform scale-0 group-hover:scale-100 opacity-0 group-hover:opacity-20"></span>
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" 
                   class="transition-all duration-300"
                   :class="hoveredIcon === 1 ? 'text-indigo-600 dark:text-indigo-400 scale-110' : 'text-gray-500 dark:text-gray-400'">
                <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
              </svg>
            </a>
            <a 
              href="#" 
              class="relative group flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 transition-all duration-300 hover:bg-indigo-100 dark:hover:bg-indigo-900"
              @mouseenter="setHoveredIcon(2)"
              @mouseleave="clearHoveredIcon()"
            >
              <span class="absolute inset-0 rounded-full bg-indigo-500 transition-all duration-300 transform scale-0 group-hover:scale-100 opacity-0 group-hover:opacity-20"></span>
              <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" 
                   class="transition-all duration-300"
                   :class="hoveredIcon === 2 ? 'text-indigo-600 dark:text-indigo-400 scale-110' : 'text-gray-500 dark:text-gray-400'">
                <path fill-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.21c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.755zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z" clip-rule="evenodd" />
              </svg>
            </a>
            <a 
              href="#" 
              class="relative group flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 transition-all duration-300 hover:bg-indigo-100 dark:hover:bg-indigo-900"
              @mouseenter="setHoveredIcon(3)"
              @mouseleave="clearHoveredIcon()"
            >
              <span class="absolute inset-0 rounded-full bg-indigo-500 transition-all duration-300 transform scale-0 group-hover:scale-100 opacity-0 group-hover:opacity-20"></span>
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24" 
                   class="transition-all duration-300"
                   :class="hoveredIcon === 3 ? 'text-indigo-600 dark:text-indigo-400 scale-110' : 'text-gray-500 dark:text-gray-400'">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
              </svg>
            </a>
          </div>
          
          <!-- Newsletter signup -->
          <div 
            class="mt-8 transition-all duration-700 delay-300"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Subscribe to our newsletter</h3>
            <div class="flex mt-2 max-w-md">
              <input 
                type="email" 
                placeholder="Enter your email" 
                class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 border border-transparent rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              />
              <button 
                type="submit"
                class="px-4 py-2.5 bg-indigo-600 dark:bg-indigo-700 text-white text-sm font-medium rounded-r-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-300"
              >
                Subscribe
              </button>
            </div>
          </div>
        </div>
        
        <!-- Columnas de enlaces -->
        <div class="md:col-span-7 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            class="transition-all duration-700 delay-400"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900 dark:text-white">Solutions</h3>
            <ul class="space-y-3">
              <li v-for="(item, index) in solutions" :key="`solution-${index}`">
                <a 
                  :href="item.href" 
                  class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300"
                >
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  {{ item.name }}
                </a>
              </li>
            </ul>
          </div>
          
          <div 
            class="transition-all duration-700 delay-500"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900 dark:text-white">Company</h3>
            <ul class="space-y-3">
              <li v-for="(item, index) in company" :key="`company-${index}`">
                <a 
                  :href="item.href" 
                  class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300"
                >
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  {{ item.name }}
                </a>
              </li>
            </ul>
          </div>
          
          <div 
            class="transition-all duration-700 delay-600"
            :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
          >
            <h3 class="text-sm font-semibold uppercase tracking-wider mb-4 text-gray-900 dark:text-white">Resources</h3>
            <ul class="space-y-3">
              <li>
                <a href="#" class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300">
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  Documentation
                </a>
              </li>
              <li>
                <a href="#" class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300">
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  Guides
                </a>
              </li>
              <li>
                <a href="#" class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300">
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  API Status
                </a>
              </li>
              <li>
                <a href="#" class="group text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm flex items-center transition-all duration-300">
                  <span class="w-0 h-px bg-indigo-600 dark:bg-indigo-400 mr-0 transition-all duration-300 group-hover:w-2 group-hover:mr-2"></span>
                  Support
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      
      <!-- Sección de pie de página -->
      <div 
        class="mt-12 pt-8 border-t border-[#e8e8e6] dark:border-[#222220] flex flex-col md:flex-row justify-between items-center transition-all duration-700 delay-700"
        :class="isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0'"
      >
        <div class="flex items-center space-x-2">
          <div class="w-1.5 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
          <p class="text-sm text-[#706F6C] dark:text-[#A1A09A]">
            &copy; {{ currentYear }} Spotly. All rights reserved.
          </p>
        </div>
        
        <div class="mt-4 md:mt-0">
          <div class="flex flex-wrap justify-center md:justify-end gap-6">
            <a 
              v-for="(link, index) in legalLinks" 
              :key="`legal-${index}`" 
              :href="link.href" 
              class="text-[#706F6C] dark:text-[#A1A09A] hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors duration-300"
            >
              {{ link.name }}
            </a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.bg-grid-pattern {
  background-image: linear-gradient(to right, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
  background-size: 30px 30px;
}

@media (prefers-color-scheme: dark) {
  .bg-grid-pattern {
    background-image: linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  }
}
</style>