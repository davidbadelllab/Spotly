<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const isVisible = ref(false);

onMounted(() => {
  setTimeout(() => {
    isVisible.value = true;
  }, 100);
});
</script>

<template>
  <section class="relative py-20 md:py-28 overflow-hidden bg-gradient-to-br from-indigo-700 via-indigo-600 to-purple-600 dark:from-indigo-900 dark:via-indigo-800 dark:to-purple-800">
    <!-- Elementos decorativos de fondo -->
    <div class="absolute inset-0 overflow-hidden opacity-10">
      <div class="absolute -left-10 -top-10 w-40 h-40 rounded-full bg-white blur-3xl"></div>
      <div class="absolute right-0 top-1/4 w-80 h-80 rounded-full bg-indigo-300 blur-3xl"></div>
      <div class="absolute bottom-0 left-1/4 w-60 h-60 rounded-full bg-purple-300 blur-3xl"></div>
    </div>

    <!-- Patrón de malla en el fondo -->
    <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:30px_30px]"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
      <!-- Título con efecto de transición -->
      <div :class="['transform transition-all duration-700 delay-100', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6 leading-tight">
          Revoluciona tu experiencia <br class="hidden md:block" />
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-white to-indigo-200">de reservas ahora</span>
        </h2>
      </div>

      <!-- Descripción con efecto de transición -->
      <div :class="['transform transition-all duration-700 delay-200', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
        <p class="text-indigo-100 text-lg md:text-xl max-w-3xl mx-auto mb-10 leading-relaxed">
          Únete a miles de empresas que utilizan <span class="font-semibold text-white">ReserveHub</span> para optimizar sus reservas y expandir su base de clientes con nuestra plataforma líder en el mercado.
        </p>
      </div>

      <!-- Botones de acción con efecto de transición -->
      <div :class="['flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 transform transition-all duration-700 delay-300', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
        <Link 
          :href="route('register')" 
          class="group px-8 py-4 bg-white text-indigo-600 font-medium rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 relative overflow-hidden"
        >
          <span class="absolute inset-0 w-0 bg-gradient-to-r from-indigo-100 to-white transition-all duration-300 ease-out group-hover:w-full"></span>
          <span class="relative flex items-center justify-center">
            <span>Comienza Gratis</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
          </span>
        </Link>
        
        <a 
          href="#" 
          class="group px-8 py-4 bg-indigo-800 text-white font-medium rounded-xl border border-indigo-500 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 backdrop-blur-sm bg-opacity-70 hover:bg-opacity-90"
        >
          <span class="relative flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span>Agenda una Demo</span>
          </span>
        </a>
      </div>

      <!-- Texto adicional con efecto de transición -->
      <div :class="['transform transition-all duration-700 delay-400', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
        <div class="flex flex-col sm:flex-row items-center justify-center mt-10 text-indigo-200 space-y-2 sm:space-y-0 sm:space-x-6">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>Sin tarjeta de crédito</span>
          </div>
          <div class="hidden sm:block h-4 w-px bg-indigo-400/30"></div>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>Prueba gratuita de 14 días</span>
          </div>
          <div class="hidden sm:block h-4 w-px bg-indigo-400/30"></div>
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            <span>Cancelación instantánea</span>
          </div>
        </div>
      </div>

      <!-- Logos de clientes satisfechos -->
      <div :class="['mt-16 transform transition-all duration-700 delay-500', isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
        <p class="text-indigo-200 text-sm font-medium mb-6">EMPRESAS QUE CONFÍAN EN NOSOTROS</p>
        <div class="flex flex-wrap justify-center gap-x-12 gap-y-8">
          <div class="h-8 w-auto opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
            <div class="w-24 h-8 bg-white/20 rounded-md blur-sm"></div>
          </div>
          <div class="h-8 w-auto opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
            <div class="w-24 h-8 bg-white/20 rounded-md blur-sm"></div>
          </div>
          <div class="h-8 w-auto opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
            <div class="w-24 h-8 bg-white/20 rounded-md blur-sm"></div>
          </div>
          <div class="h-8 w-auto opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
            <div class="w-24 h-8 bg-white/20 rounded-md blur-sm"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.bg-grid-white {
  background-image: linear-gradient(to right, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
}
</style>