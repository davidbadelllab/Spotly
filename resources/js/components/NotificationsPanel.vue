<script setup lang="ts">
interface Notification {
  id: number;
  title: string;
  message: string;
  time: string;
  read: boolean;
}

interface Props {
  notifications: Notification[];
}

defineProps<Props>();
defineEmits<{
  (e: 'close'): void;
}>();
</script>

<template>
  <div class="w-96 bg-white dark:bg-[#1A1A1A] rounded-lg shadow-lg border border-[#e8e8e6] dark:border-[#222220] p-4">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold">Notifications</h3>
      <button 
        @click="$emit('close')"
        class="text-[#1b1b18] dark:text-[#ADADAA] hover:text-primary dark:hover:text-primary"
      >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
          <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
        </svg>
      </button>
    </div>

    <div class="space-y-4">
      <div v-if="notifications.length === 0" class="text-center py-4 text-[#1b1b18] dark:text-[#ADADAA]">
        No new notifications
      </div>
      
      <div 
        v-for="notification in notifications" 
        :key="notification.id"
        class="flex items-start gap-4 p-3 rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#222220]"
        :class="{ 'bg-[#F2F2F0] dark:bg-[#222220]': !notification.read }"
      >
        <div class="flex-1">
          <h4 class="font-medium mb-1">{{ notification.title }}</h4>
          <p class="text-sm text-[#1b1b18] dark:text-[#ADADAA]">{{ notification.message }}</p>
          <span class="text-xs text-[#1b1b18] dark:text-[#ADADAA] mt-2 block">{{ notification.time }}</span>
        </div>
        <div v-if="!notification.read" class="w-2 h-2 bg-primary rounded-full mt-2"></div>
      </div>
    </div>
  </div>
</template>
