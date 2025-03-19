<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLogo from '../components/AppLogo.vue';

defineProps<{
  title: string;
  description?: string;
}>();
</script>

<template>
  <div class="min-h-screen bg-background">
    <Head :title="title" />

    <div class="flex min-h-screen flex-col">
      <header class="border-b bg-card px-4 py-3">
        <div class="mx-auto flex max-w-7xl items-center justify-between">
          <!-- <AppLogo /> -->
        </div>
      </header>

      <main class="flex-1">
        <div class="mx-auto max-w-7xl px-4 py-8">
          <div class="mx-auto max-w-xl">
            <div class="mb-8 text-center">
              <h1 class="text-2xl font-semibold tracking-tight">{{ title }}</h1>
              <p v-if="description" class="mt-2 text-sm text-muted-foreground">
                {{ description }}
              </p>
            </div>

            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
