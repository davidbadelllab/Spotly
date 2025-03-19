<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppHeader from '../components/AppHeaderv.vue';
import HeroSection from '../components/HeroSection.vue';
import BrandsSection from '../components/BrandsSection.vue';
import FeaturesSection from '../components/FeaturesSection.vue';
import SolutionsSection from '../components/SolutionsSection.vue';
import TestimonialsSection from '../components/TestimonialsSection.vue';
import PricingSection from '../components/PricingSection.vue';
import CtaSection from '../components/CtaSection.vue';
import AppFooter from '../components/AppFooter.vue';

// Datos compartidos entre componentes
import { venueTypes, testimonials, features } from '../../data/landingPageData';
</script>

<template>
  <Head title="Spotly - Your Ultimate Booking Solution" />
  
  <div class="min-h-screen bg-gradient-to-br from-white to-[#F8F8F6] dark:from-[#0A0A0A] dark:to-[#141414] text-[#1b1b18] dark:text-[#EDEDEC]">
    <!-- Componentes de la página de inicio -->
    <AppHeader />
    <HeroSection />
    <BrandsSection />
    <FeaturesSection :features="features" />
    <SolutionsSection :venue-types="venueTypes" />
    <TestimonialsSection :testimonials="testimonials" />
    <PricingSection />
    <CtaSection />
    <AppFooter />
  </div>
</template>