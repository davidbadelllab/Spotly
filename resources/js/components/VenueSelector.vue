<script setup lang="ts">
interface Venue {
  id: number;
  name: string;
  type: string;
  capacity: number;
  location: string;
  image: string;
}

interface Props {
  venues: Venue[];
  selectedVenue: Venue | null;
}

interface Emits {
  (e: 'select-venue', venue: Venue): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const selectVenue = (venue: Venue) => {
  emit('select-venue', venue);
};
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <div
      v-for="venue in venues"
      :key="venue.id"
      @click="selectVenue(venue)"
      :class="[
        'bg-white dark:bg-[#1A1A1A] rounded-xl overflow-hidden cursor-pointer border transition-all',
        selectedVenue?.id === venue.id
          ? 'border-indigo-500 dark:border-indigo-400 shadow-md'
          : 'border-[#e8e8e6] dark:border-[#222220] hover:border-indigo-500/50 dark:hover:border-indigo-400/50'
      ]"
    >
      <div class="aspect-[4/3] relative overflow-hidden">
        <img
          :src="venue.image"
          :alt="venue.name"
          class="w-full h-full object-cover"
        />
      </div>
      <div class="p-4">
        <h4 class="font-medium">{{ venue.name }}</h4>
        <div class="mt-2 flex items-center text-sm text-[#706F6C] dark:text-[#8A8A88] space-x-4">
          <div class="flex items-center">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            {{ venue.location }}
          </div>
          <div class="flex items-center">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            {{ venue.capacity }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
