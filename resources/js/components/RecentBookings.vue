<script setup lang="ts">
interface Booking {
  id: number;
  venue: string;
  date: string;
  time: string;
  status: 'confirmed' | 'pending';
}

interface Props {
  bookings: Booking[];
}

defineProps<Props>();

const formatDate = (dateStr: string) => {
  return new Date(dateStr).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
  });
};
</script>

<template>
  <div class="bg-white dark:bg-[#1A1A1A] rounded-xl shadow-sm p-5 border border-[#e8e8e6] dark:border-[#222220]">
    <h3 class="text-lg font-medium mb-4">Recent Bookings</h3>
    
    <div class="space-y-4">
      <div
        v-for="booking in bookings"
        :key="booking.id"
        class="flex items-center justify-between p-3 rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#222220]"
      >
        <div>
          <h4 class="font-medium text-sm">{{ booking.venue }}</h4>
          <div class="flex items-center mt-1 space-x-2 text-sm text-[#706F6C] dark:text-[#8A8A88]">
            <span>{{ formatDate(booking.date) }}</span>
            <span>•</span>
            <span>{{ booking.time }}</span>
          </div>
        </div>
        
        <div
          :class="[
            'px-2 py-1 text-xs rounded-full',
            booking.status === 'confirmed'
              ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'
              : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'
          ]"
        >
          {{ booking.status.charAt(0).toUpperCase() + booking.status.slice(1) }}
        </div>
      </div>
    </div>
    
    <button class="w-full mt-4 py-2 text-sm text-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">
      View all bookings
    </button>
  </div>
</template>
