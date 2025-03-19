<script setup lang="ts">
import { Button } from '../../components/ui/button';
import OnboardingLayout from '../../layouts/OnboardingLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  user_type: '',
});

const selectType = (type: 'business' | 'customer') => {
  form.user_type = type;
  form.post(route('auth.user-type.store'), {
    onSuccess: () => {
      if (type === 'business') {
        window.location.href = route('onboarding.show');
      } else {
        window.location.href = route('dashboard');
      }
    },
  });
};
</script>

<template>
  <OnboardingLayout
    title="How will you use Spotly?"
    description="Choose how you want to use our platform"
  >
    <Head title="Choose User Type" />

    <div class="grid gap-6">
      <Button
        type="button"
        variant="outline"
        class="flex h-32 flex-col items-center justify-center gap-2 p-8"
        :disabled="form.processing"
        @click="selectType('business')"
      >
        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <span class="text-lg font-medium">I'm a Business Owner</span>
        <span class="text-sm text-muted-foreground">I want to manage my venues and reservations</span>
      </Button>

      <Button
        type="button"
        variant="outline"
        class="flex h-32 flex-col items-center justify-center gap-2 p-8"
        :disabled="form.processing"
        @click="selectType('customer')"
      >
        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="text-lg font-medium">I'm a Customer</span>
        <span class="text-sm text-muted-foreground">I want to make reservations at venues</span>
      </Button>
    </div>
  </OnboardingLayout>
</template>
