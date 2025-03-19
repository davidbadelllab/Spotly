<script setup lang="ts">
import { Button } from './ui/button';
import { Input } from './ui/input';
import NotificationsPanel from './NotificationsPanel.vue';
import { ref } from 'vue';

interface Props {
  title?: string;
}

defineProps<Props>();
const showNotifications = ref(false);
</script>

<template>
  <header class="flex items-center justify-between h-16 px-6 border-b border-[#e8e8e6] dark:border-[#222220] bg-background shrink-0">
    <div class="flex items-center gap-4">
      <h1 class="text-xl font-semibold">{{ title }}</h1>
    </div>

    <div class="flex items-center gap-6">
      <div class="relative w-96">
        <Input 
          type="search" 
          placeholder="Search..." 
          class="pl-10"
        />
        <svg 
          class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground"
          xmlns="http://www.w3.org/2000/svg" 
          viewBox="0 0 20 20" 
          fill="currentColor"
        >
          <path 
            fill-rule="evenodd" 
            d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" 
            clip-rule="evenodd" 
          />
        </svg>
      </div>

      <div class="relative">
        <Button 
          variant="ghost" 
          size="icon"
          class="relative"
          @click="showNotifications = !showNotifications"
        >
          <svg 
            xmlns="http://www.w3.org/2000/svg" 
            viewBox="0 0 20 20" 
            fill="currentColor" 
            class="w-5 h-5"
          >
            <path 
              fill-rule="evenodd" 
              d="M10 2a6 6 0 00-6 6c0 1.887-.454 3.665-1.257 5.234a.75.75 0 00.515 1.076 32.91 32.91 0 003.256.508 3.5 3.5 0 006.972 0 32.903 32.903 0 003.256-.508.75.75 0 00.515-1.076A11.448 11.448 0 0116 8a6 6 0 00-6-6zM8.05 14.943a33.54 33.54 0 003.9 0 2 2 0 01-3.9 0z" 
              clip-rule="evenodd" 
            />
          </svg>
          <span class="absolute -top-1 -right-1 w-2 h-2 bg-primary rounded-full"></span>
        </Button>
        <NotificationsPanel 
          v-if="showNotifications" 
          :notifications="[]"
          @close="showNotifications = false"
          class="absolute right-0 mt-2"
        />
      </div>
    </div>
  </header>
</template>
