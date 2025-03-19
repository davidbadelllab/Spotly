<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import MainHeader from '@/components/MainHeader.vue';
import MainSidebar from '@/components/MainSidebar.vue';

interface Props {
  title?: string;
}

const props = defineProps<Props>();
const page = usePage();
const user = page.props.auth.user;
</script>

<template>
  <div class="min-h-screen bg-background flex">
    <MainSidebar :user="user" />
    
    <div class="flex-1 flex flex-col">
      <MainHeader :title="title" />
      
      <main class="flex-1">
        <slot />
      </main>
    </div>
  </div>
</template>
