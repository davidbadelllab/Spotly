<script setup lang="ts">
defineProps<{
  stats: {
    totalReservations: number;
    upcomingReservations: number;
    favoriteVenue: string;
    percentageIncrease: number;
  };
}>();
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="bg-white dark:bg-[#1A1A1A] rounded-xl p-5 border border-[#e8e8e6] dark:border-[#222220]">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-[#706F6C] dark:text-[#8A8A88]">Total Reservations</p>
          <p class="text-2xl font-semibold mt-1">{{ stats.totalReservations }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-[#1A1A1A] rounded-xl p-5 border border-[#e8e8e6] dark:border-[#222220]">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-[#706F6C] dark:text-[#8A8A88]">Upcoming Bookings</p>
          <p class="text-2xl font-semibold mt-1">{{ stats.upcomingReservations }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-[#1A1A1A] rounded-xl p-5 border border-[#e8e8e6] dark:border-[#222220]">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-[#706F6C] dark:text-[#8A8A88]">Favorite Venue</p>
          <p class="text-2xl font-semibold mt-1">{{ stats.favoriteVenue }}</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
          </svg>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-[#1A1A1A] rounded-xl p-5 border border-[#e8e8e6] dark:border-[#222220]">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm text-[#706F6C] dark:text-[#8A8A88]">Growth Rate</p>
          <div class="flex items-baseline mt-1">
            <p class="text-2xl font-semibold">{{ stats.percentageIncrease }}%</p>
            <span class="ml-1 text-xs text-emerald-600 dark:text-emerald-400">↑</span>
          </div>
        </div>
        <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>
