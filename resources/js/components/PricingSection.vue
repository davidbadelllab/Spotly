<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Registrar plugins de GSAP
gsap.registerPlugin(ScrollTrigger);

// Interfaces para tipado estricto
interface PlanFeature {
  text: string;
  included: boolean;
  isNew?: boolean;
  tooltip?: string;
}

interface PricingPlan {
  id: string;
  name: string;
  description: string;
  monthlyPrice: number;
  yearlyPrice: number;
  features: PlanFeature[];
  cta: string;
  ctaLink: string;
  featured?: boolean;
  badge?: string;
  discount?: number;
  popular?: boolean;
  color?: string;
  customFeatures?: string[];
  recommended?: boolean;
  maxUsers?: number;
  storageLimit?: string;
  icon?: string;
}

// Estado para controlar la visualización de facturación anual vs mensual
const isYearly = ref(false);
const activeTab = ref('monthly');
const selectedPlan = ref<string | null>(null);
const showAllFeatures = ref<Record<string, boolean>>({});
const hoverStates = reactive<Record<string, boolean>>({});
const isComparisonOpen = ref(false);
const highlightedPlan = ref<string | null>(null);

// Hook para efecto al inicializar
onMounted(() => {
  // Animaciones de entrada
  setupEntryAnimations();
  
  // Inicializar estados
  pricingPlans.value.forEach(plan => {
    showAllFeatures.value[plan.id] = false;
  });
  
  // Seleccionar plan destacado por defecto
  const featuredPlan = pricingPlans.value.find(plan => plan.featured);
  if (featuredPlan) {
    selectedPlan.value = featuredPlan.id;
  }
});

// Planes de precios con opciones avanzadas
const pricingPlans = ref<PricingPlan[]>([
  {
    id: 'starter',
    name: 'Starter',
    description: 'Perfect for small businesses and startups',
    monthlyPrice: 29,
    yearlyPrice: 290, 
    features: [
      { text: 'Up to 3 venues', included: true },
      { text: 'Basic booking management', included: true },
      { text: 'Email notifications', included: true },
      { text: '5GB storage', included: true },
      { text: 'Advanced analytics', included: false, tooltip: 'Track performance metrics and user engagement' },
      { text: 'Priority support', included: false },
      { text: 'Custom branding', included: false },
      { text: 'API access', included: false }
    ],
    cta: 'Start Free Trial',
    ctaLink: '#',
    featured: false,
    discount: 17,
    maxUsers: 5,
    storageLimit: '5GB',
    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
    color: 'indigo'
  },
  {
    id: 'pro',
    name: 'Pro',
    description: 'For growing businesses with advanced needs',
    monthlyPrice: 79,
    yearlyPrice: 790,
    features: [
      { text: 'Up to 10 venues', included: true },
      { text: 'Advanced booking management', included: true },
      { text: 'Email & SMS notifications', included: true, isNew: true },
      { text: '20GB storage', included: true },
      { text: 'Advanced analytics', included: true },
      { text: 'Priority support', included: true },
      { text: 'Custom branding', included: true },
      { text: 'API access', included: false, tooltip: 'Integrate with your existing tools and software' }
    ],
    cta: 'Start Free Trial',
    ctaLink: '#',
    featured: true,
    badge: 'Most Popular',
    discount: 17,
    popular: true,
    maxUsers: 15,
    storageLimit: '20GB',
    icon: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    color: 'purple',
    recommended: true
  },
  {
    id: 'enterprise',
    name: 'Enterprise',
    description: 'For large businesses with complex requirements',
    monthlyPrice: 199,
    yearlyPrice: 1990,
    features: [
      { text: 'Unlimited venues', included: true },
      { text: 'Custom booking workflows', included: true, isNew: true },
      { text: 'Advanced notifications', included: true },
      { text: '100GB storage', included: true },
      { text: 'Advanced analytics & reporting', included: true },
      { text: 'Priority support 24/7', included: true },
      { text: 'Custom branding', included: true },
      { text: 'API access', included: true }
    ],
    cta: 'Contact Sales',
    ctaLink: '#',
    featured: false,
    discount: 17,
    maxUsers: 'Unlimited',
    storageLimit: '100GB',
    icon: 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
    color: 'teal',
    customFeatures: ['Custom onboarding', 'Dedicated account manager', 'SLA guarantees', 'Custom integrations']
  }
]);

// Lista de características para comparación
const allFeaturesForComparison = computed(() => {
  const allFeatures = new Set<string>();
  pricingPlans.value.forEach(plan => {
    plan.features.forEach(feature => {
      allFeatures.add(feature.text);
    });
  });
  return Array.from(allFeatures);
});

// Comprobar si una característica está incluida en un plan
const isFeatureIncluded = (planId: string, featureText: string) => {
  const plan = pricingPlans.value.find(p => p.id === planId);
  if (!plan) return false;
  
  const feature = plan.features.find(f => f.text === featureText);
  return feature?.included || false;
};

// Calcular el ahorro anual
const calculateSavings = (plan: PricingPlan) => {
  const monthlyCost = plan.monthlyPrice * 12;
  return monthlyCost - plan.yearlyPrice;
};

// Calcular porcentaje de ahorro
const calculateSavingsPercentage = (plan: PricingPlan) => {
  const monthlyCost = plan.monthlyPrice * 12;
  return Math.round(((monthlyCost - plan.yearlyPrice) / monthlyCost) * 100);
};

// Computar el precio por mes para plan anual
const getMonthlyEquivalent = (yearlyPrice: number) => {
  return Math.round(yearlyPrice / 12);
};

// Toggle entre facturación anual y mensual
const toggleBilling = () => {
  isYearly.value = !isYearly.value;
  activeTab.value = isYearly.value ? 'yearly' : 'monthly';
  
  // Animar cambio de precios
  gsap.to('.price-amount', {
    y: -20,
    opacity: 0,
    duration: 0.3,
    stagger: 0.1,
    onComplete: () => {
      gsap.to('.price-amount', {
        y: 0,
        opacity: 1,
        duration: 0.3,
        stagger: 0.1
      });
    }
  });
};

// Seleccionar un plan específico
const selectPlan = (planId: string) => {
  selectedPlan.value = planId;
  
  // Destacar el plan seleccionado
  gsap.to('.pricing-card', {
    scale: 1,
    duration: 0.3
  });
  
  gsap.to(`.pricing-card-${planId}`, {
    scale: 1.02,
    duration: 0.3
  });
};

// Mostrar/ocultar todas las características
const toggleFeatures = (planId: string) => {
  showAllFeatures.value[planId] = !showAllFeatures.value[planId];
};

// Establecer estado de hover
const setHoverState = (planId: string, state: boolean) => {
  hoverStates[planId] = state;
};

// Destacar un plan
const highlightPlan = (planId: string) => {
  highlightedPlan.value = planId;
};

// Quitar destacado
const unhighlightPlan = () => {
  highlightedPlan.value = null;
};

// Abrir diálogo de comparación
const openComparison = () => {
  isComparisonOpen.value = true;
  document.body.style.overflow = 'hidden';
  
  // Animar apertura
  gsap.fromTo('.comparison-modal',
    { opacity: 0, y: 20 },
    { opacity: 1, y: 0, duration: 0.4 }
  );
};

// Cerrar diálogo de comparación
const closeComparison = () => {
  gsap.to('.comparison-modal', {
    opacity: 0,
    y: 20,
    duration: 0.3,
    onComplete: () => {
      isComparisonOpen.value = false;
      document.body.style.overflow = '';
    }
  });
};

// Animaciones en carga de página
const setupEntryAnimations = () => {
  // Animar encabezado
  gsap.from('.pricing-header h2', {
    opacity: 0,
    y: 30,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.pricing-header',
      start: 'top 80%'
    }
  });
  
  gsap.from('.pricing-header p', {
    opacity: 0,
    y: 20,
    duration: 0.8,
    delay: 0.2,
    scrollTrigger: {
      trigger: '.pricing-header',
      start: 'top 80%'
    }
  });
  
  // Animar toggle
  gsap.from('.billing-toggle', {
    opacity: 0,
    y: 20,
    duration: 0.5,
    delay: 0.4,
    scrollTrigger: {
      trigger: '.pricing-header',
      start: 'top 80%'
    }
  });
  
  // Animar tarjetas de precios
  gsap.from('.pricing-card', {
    opacity: 0,
    y: 50,
    stagger: 0.2,
    duration: 0.8,
    scrollTrigger: {
      trigger: '.pricing-grid',
      start: 'top 80%'
    }
  });
  
  // Animar decoraciones
  gsap.from('.pricing-decoration', {
    opacity: 0,
    scale: 0.8,
    duration: 1,
    stagger: 0.3,
    scrollTrigger: {
      trigger: '.pricing-grid',
      start: 'top 80%'
    }
  });
};
</script>

<template>
  <section id="pricing" class="relative py-20 md:py-32 overflow-hidden">
    <!-- Fondo decorativo -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-50 to-white dark:from-slate-950 dark:to-slate-900 pointer-events-none"></div>
    
    <!-- Patrón de puntos decorativo -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.07]" 
         style="background-image: radial-gradient(circle at 1px 1px, #666 1px, transparent 0); background-size: 24px 24px;">
    </div>
    
    <!-- Formas decorativas -->
    <div class="pricing-decoration absolute -top-20 right-10 w-96 h-96 bg-indigo-200/30 dark:bg-indigo-500/10 rounded-full blur-3xl"></div>
    <div class="pricing-decoration absolute -bottom-20 -left-20 w-96 h-96 bg-purple-200/30 dark:bg-purple-500/10 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <!-- Encabezado de la sección -->
      <div class="pricing-header text-center max-w-3xl mx-auto mb-16">
        <div class="inline-flex items-center mb-4 px-4 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-800/50">
          <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Flexible Pricing
        </div>
        
        <h2 class="text-4xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300">
          Choose Your Perfect Plan
        </h2>
        
        <p class="mt-4 text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
          Transparent pricing designed to scale with your business. All plans include a 14-day free trial.
        </p>
        
        <!-- Tabs de período de facturación -->
        <div class="mt-10 billing-toggle relative z-10">
          <div class="inline-flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
            <button 
              @click="activeTab = 'monthly'; isYearly = false" 
              class="relative px-5 py-2 text-sm font-medium rounded-lg transition-all duration-300"
              :class="activeTab === 'monthly' ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-700 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
            >
              Monthly
            </button>
            <button 
              @click="activeTab = 'yearly'; isYearly = true" 
              class="relative px-5 py-2 text-sm font-medium rounded-lg transition-all duration-300"
              :class="activeTab === 'yearly' ? 'text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-700 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300'"
            >
              Yearly 
              <span class="absolute -top-2 -right-2 px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-500 text-white">-17%</span>
            </button>
          </div>
          
          <!-- Información sobre ahorro anual -->
          <div v-if="isYearly" class="mt-4 text-sm text-green-600 dark:text-green-400 animate-fade-in">
            <span class="inline-flex items-center">
              <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Save up to $310 per year with annual billing
            </span>
          </div>
        </div>
      </div>
      
      <!-- Tarjetas de precios -->
      <div class="pricing-grid grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <!-- Iteración a través de planes de precios -->
        <div 
          v-for="plan in pricingPlans" 
          :key="plan.id"
          :class="[
            'pricing-card',
            `pricing-card-${plan.id}`,
            'relative rounded-2xl overflow-hidden transition-all duration-500',
            plan.id === selectedPlan ? 'transform scale-[1.02] z-10' : '',
            plan.featured ? 'bg-white dark:bg-slate-800 shadow-xl border-2 border-indigo-500/30 dark:border-indigo-500/20' : 
            'bg-white dark:bg-slate-800/70 shadow-lg border border-slate-200 dark:border-slate-700',
            highlightedPlan && plan.id !== highlightedPlan ? 'opacity-70' : 'opacity-100'
          ]"
          @mouseenter="setHoverState(plan.id, true); highlightPlan(plan.id)"
          @mouseleave="setHoverState(plan.id, false); unhighlightPlan()"
        >
          <!-- Etiqueta destacada si existe -->
          <div v-if="plan.badge" class="absolute top-0 inset-x-0 transform -translate-y-1/2 flex justify-center">
            <span 
              class="px-4 py-1 rounded-full text-xs font-semibold uppercase tracking-wider shadow-sm"
              :class="`bg-${plan.color || 'indigo'}-600 text-white dark:bg-${plan.color || 'indigo'}-500`"
            >
              {{ plan.badge }}
            </span>
          </div>
          
          <!-- Cabecera del plan -->
          <div 
            class="pt-8 pb-4 px-8 border-b"
            :class="[
              plan.featured ? 
                `border-${plan.color || 'indigo'}-100 dark:border-${plan.color || 'indigo'}-900/20` : 
                'border-slate-100 dark:border-slate-700'
            ]"
          >
            <!-- Icono y nombre del plan -->
            <div class="flex items-center mb-2">
              <div 
                class="mr-3 flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
                :class="`bg-${plan.color || 'indigo'}-100 dark:bg-${plan.color || 'indigo'}-900/30 text-${plan.color || 'indigo'}-600 dark:text-${plan.color || 'indigo'}-400`"
              >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="plan.icon" />
                </svg>
              </div>
              <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ plan.name }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ plan.description }}</p>
              </div>
            </div>
            
            <!-- Precio con animación -->
            <div class="price-amount mt-6 flex items-baseline">
              <span class="text-5xl font-extrabold text-slate-900 dark:text-white">${{ isYearly ? plan.yearlyPrice : plan.monthlyPrice }}</span>
              <span class="ml-2 text-lg text-slate-600 dark:text-slate-400">/{{ isYearly ? 'year' : 'month' }}</span>
            </div>
            
            <!-- Conversión mensual para planes anuales -->
            <div v-if="isYearly" class="mt-1 text-sm text-slate-500 dark:text-slate-400">
              Just ${{ getMonthlyEquivalent(plan.yearlyPrice) }}/mo, billed annually
            </div>
            
            <div v-if="isYearly" class="mt-3 inline-flex items-center text-sm text-green-600 dark:text-green-400">
              <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
              </svg>
              Save ${{ calculateSavings(plan) }} ({{ calculateSavingsPercentage(plan) }}%)
            </div>
          </div>
          
          <!-- Límites básicos -->
          <div class="px-8 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-700">
            <div class="flex justify-between items-center">
              <div class="text-sm text-slate-500 dark:text-slate-400">
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ plan.maxUsers }}</span> users
              </div>
              <div class="text-sm text-slate-500 dark:text-slate-400">
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ plan.storageLimit }}</span> storage
              </div>
            </div>
          </div>
          
          <!-- Lista de características -->
          <div class="p-8">
            <ul class="space-y-4">
              <li 
                v-for="(feature, index) in plan.features.slice(0, showAllFeatures[plan.id] ? plan.features.length : 4)" 
                :key="`${plan.id}-${feature.text}`" 
                class="flex items-start"
                :class="[
                  !feature.included ? 'text-slate-400 dark:text-slate-500' : 
                  'text-slate-700 dark:text-slate-300'
                ]"
              >
                <div class="flex-shrink-0 mt-1 relative">
                  <svg 
                    width="20" 
                    height="20" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    :class="feature.included ? `text-${plan.color || 'indigo'}-500 dark:text-${plan.color || 'indigo'}-400` : 'text-slate-400 dark:text-slate-600'"
                  >
                    <path 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2" 
                      :d="feature.included ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'" 
                    />
                  </svg>
                  
                  <!-- Badge para características nuevas -->
                  <span 
                    v-if="feature.isNew" 
                    class="absolute -top-2 -right-2 px-1.5 py-0.5 text-[8px] font-bold rounded-full bg-green-500 text-white"
                  >
                    NEW
                  </span>
                </div>
                <div class="ml-3">
                  <span>{{ feature.text }}</span>
                  
                  <!-- Tooltip para características con explicación -->
                  <div v-if="feature.tooltip" class="relative group">
                    <svg class="inline-block ml-1 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="absolute left-0 bottom-full mb-2 w-48 rounded-md bg-slate-900 text-white text-xs p-2 opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                      {{ feature.tooltip }}
                      <div class="absolute top-full left-4 transform -translate-x-1/2 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
                    </div>
                  </div>
                </div>
              </li>
              
              <!-- Características personalizadas para Enterprise -->
              <template v-if="plan.customFeatures && plan.customFeatures.length > 0">
                <li 
                  v-for="feature in plan.customFeatures" 
                  :key="`custom-${feature}`" 
                  class="flex items-start text-slate-700 dark:text-slate-300"
                >
                  <svg 
                    width="20" 
                    height="20" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    class="mt-1 mr-3"
                    :class="`text-${plan.color || 'indigo'}-500 dark:text-${plan.color || 'indigo'}-400`"
                  >
                    <path 
                      stroke-linecap="round" 
                      stroke-linejoin="round" 
                      stroke-width="2" 
                      d="M5 13l4 4L19 7" 
                    />
                  </svg>
                  <span>{{ feature }}</span>
                </li>
              </template>
              
              <!-- Mostrar más/menos características -->
              <li v-if="plan.features.length > 4">
                <button 
                  @click="toggleFeatures(plan.id)" 
                  class="mt-2 text-sm font-medium inline-flex items-center"
                  :class="`text-${plan.color || 'indigo'}-600 dark:text-${plan.color || 'indigo'}-400 hover:text-${plan.color || 'indigo'}-800 dark:hover:text-${plan.color || 'indigo'}-300`"
                >
                  <span>{{ showAllFeatures[plan.id] ? 'Show less' : 'Show all features' }}</span>
                  <svg 
                    class="ml-1 w-4 h-4 transition-transform duration-200" 
                    :class="{ 'rotate-180': showAllFeatures[plan.id] }"
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              </li>
            </ul>
          </div>
          
          <!-- Footer con CTA -->
          <div class="px-8 pb-8 pt-4">
            <!-- Botón CTA con efecto hover avanzado -->
            <a
              :href="plan.ctaLink"
              class="block w-full py-3.5 px-4 rounded-xl text-center transition-all duration-300 font-medium shadow-sm group relative overflow-hidden"
              :class="[
                plan.featured || hoverStates[plan.id] ? 
                  `bg-${plan.color || 'indigo'}-600 text-white hover:bg-${plan.color || 'indigo'}-700 shadow-${plan.color || 'indigo'}-500/10` : 
                  `border-2 border-${plan.color || 'indigo'}-500 text-${plan.color || 'indigo'}-600 hover:bg-${plan.color || 'indigo'}-50 dark:text-${plan.color || 'indigo'}-400 dark:border-${plan.color || 'indigo'}-500/50 dark:hover:bg-${plan.color || 'indigo'}-900/10`
              ]"
            >
              <span class="relative z-10">{{ plan.cta }}</span>
              
              <!-- Efecto de brillo en hover -->
              <div 
                class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-all duration-1000 ease-out bg-gradient-to-r from-transparent via-white/20 to-transparent"
              ></div>
            </a>
            
            <!-- Garantía bajo el botón -->
            <p v-if="plan.featured" class="mt-3 text-xs text-center text-slate-500 dark:text-slate-400">
              No credit card required to start trial
            </p>
          </div>
          
          <!-- Etiqueta de recomendado -->
          <div 
            v-if="plan.recommended" 
            class="absolute top-2 right-2 px-2 py-1 text-xs font-medium rounded-md"
            :class="`bg-${plan.color || 'indigo'}-100 text-${plan.color || 'indigo'}-600 dark:bg-${plan.color || 'indigo'}-900/30 dark:text-${plan.color || 'indigo'}-400`"
          >
            Recommended
          </div>
        </div>
      </div>
      
      <!-- Botón para comparar planes -->
      <div class="mt-6 mb-12 text-center">
        <button 
          @click="openComparison" 
          class="inline-flex items-center px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors duration-300"
        >
          <svg class="mr-2 w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          Compare All Features
        </button>
      </div>
      
      <!-- FAQs de precios -->
      <div class="max-w-3xl mx-auto bg-white dark:bg-slate-800/70 rounded-2xl shadow-lg p-8 border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Frequently Asked Questions</h3>
        
        <div class="space-y-6">
          <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
            <h4 class="font-medium text-slate-900 dark:text-white mb-2">Do you offer a free trial?</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Yes, all plans come with a 14-day free trial. No credit card required to start.</p>
          </div>
          
          <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
            <h4 class="font-medium text-slate-900 dark:text-white mb-2">Can I change plans later?</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">Absolutely! You can upgrade, downgrade, or cancel your plan at any time from your account dashboard.</p>
          </div>
          
          <div class="border-b border-slate-100 dark:border-slate-700 pb-4">
            <h4 class="font-medium text-slate-900 dark:text-white mb-2">What payment methods do you accept?</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">We accept all major credit cards, PayPal, and for annual plans, we can provide invoicing options for bank transfers.</p>
          </div>
          
          <div>
            <h4 class="font-medium text-slate-900 dark:text-white mb-2">Do you offer any discounts?</h4>
            <p class="text-slate-600 dark:text-slate-400 text-sm">We offer a 17% discount on all annual plans. For educational institutions and non-profits, please contact our sales team for special pricing.</p>
          </div>
        </div>
      </div>
      
      <!-- Garantía de devolución -->
      <div class="mt-10 text-center">
        <div class="inline-flex items-center py-2 px-4 rounded-full bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900/30">
          <svg class="w-5 h-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          <p class="text-sm font-medium text-green-800 dark:text-green-400">
            100% money-back guarantee for 14 days. No questions asked.
          </p>
        </div>
      </div>
    </div>
    
    <!-- Modal de comparación de características -->
    <transition name="fade">
      <div v-if="isComparisonOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div class="comparison-modal w-full max-w-5xl bg-white dark:bg-slate-900 rounded-2xl shadow-2xl overflow-hidden">
          <!-- Cabecera del modal -->
          <div class="flex justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Compare All Features</h3>
            <button 
              @click="closeComparison" 
              class="p-2 rounded-full text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
            >
              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Tabla de comparación -->
          <div class="max-h-[70vh] overflow-y-auto p-6">
            <table class="w-full border-collapse">
              <thead class="bg-slate-50 dark:bg-slate-800 sticky top-0 z-10">
                <tr>
                  <th class="text-left p-4 font-medium text-slate-500 dark:text-slate-400 border-b border-slate-200 dark:border-slate-700">Feature</th>
                  <th 
                    v-for="plan in pricingPlans" 
                    :key="`comp-${plan.id}`" 
                    class="p-4 font-medium border-b border-slate-200 dark:border-slate-700 min-w-[120px]"
                    :class="`text-${plan.color || 'indigo'}-600 dark:text-${plan.color || 'indigo'}-400`"
                  >
                    {{ plan.name }}
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="feature in allFeaturesForComparison" :key="`feat-${feature}`">
                  <td class="border-b border-slate-200 dark:border-slate-700 p-4 text-slate-700 dark:text-slate-300">
                    {{ feature }}
                  </td>
                  <td 
                    v-for="plan in pricingPlans" 
                    :key="`${plan.id}-${feature}`" 
                    class="border-b border-slate-200 dark:border-slate-700 p-4 text-center"
                  >
                    <svg 
                      v-if="isFeatureIncluded(plan.id, feature)"
                      class="mx-auto w-5 h-5" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                      :class="`text-${plan.color || 'indigo'}-500 dark:text-${plan.color || 'indigo'}-400`"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg 
                      v-else
                      class="mx-auto w-5 h-5 text-slate-400 dark:text-slate-600" 
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                  </td>
                </tr>
                
                <!-- Fila de precios -->
                <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                  <td class="border-b border-slate-200 dark:border-slate-700 p-4 font-medium text-slate-900 dark:text-white">
                    Price
                  </td>
                  <td 
                    v-for="plan in pricingPlans" 
                    :key="`price-${plan.id}`" 
                    class="border-b border-slate-200 dark:border-slate-700 p-4 text-center font-bold"
                    :class="`text-${plan.color || 'indigo'}-600 dark:text-${plan.color || 'indigo'}-400`"
                  >
                    ${{ isYearly ? plan.yearlyPrice : plan.monthlyPrice }}/{{ isYearly ? 'year' : 'month' }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <!-- Footer con CTAs -->
          <div class="p-6 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/70 flex flex-wrap gap-4 justify-end">
            <button 
              @click="closeComparison" 
              class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-300 dark:border-slate-600 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors"
            >
              Close
            </button>
            <a 
              href="#contact" 
              class="px-5 py-2.5 text-sm font-medium rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors"
            >
              Contact Sales
            </a>
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
  animation: fade-in 0.3s ease-out forwards;
}

/* Transiciones para el modal */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Mejoras de rendimiento */
.price-amount {
  will-change: transform, opacity;
}

/* Estilos para tabla de comparación en dispositivos pequeños */
@media (max-width: 640px) {
  .comparison-modal {
    max-width: 100%;
    margin: 0 10px;
  }
}
</style>