<script setup lang="ts">
interface Venue {
  id: number;
  name: string;
  type: string;
  capacity: number;
  location: string;
  image: string;
}

interface TimeSlot {
  id: string;
  time: string;
  available: boolean;
}

interface Props {
  venue: Venue | null;
  date: Date;
  timeSlot: TimeSlot | null;
}

interface Emits {
  (e: 'close'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const formatDate = (date: Date) => {
  return date.toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
};
</script>

<template>
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white dark:bg-[#1A1A1A] rounded-xl shadow-xl w-full max-w-lg mx-4">
      <!-- Header -->
      <div class="p-6 border-b border-[#e8e8e6] dark:border-[#222220]">
        <div class="flex items-center justify-between">
          <h3 class="text-xl font-semibold">Confirm Booking</h3>
          <button 
            @click="$emit('close')"
            class="text-[#706F6C] dark:text-[#8A8A88] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
          >
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      
      <!-- Content -->
      <div class="p-6">
        <div class="space-y-6">
          <!-- Venue Details -->
          <div v-if="venue" class="flex items-start space-x-4">
            <img :src="venue.image" :alt="venue.name" class="w-20 h-20 rounded-lg object-cover" />
            <div>
              <h4 class="font-medium">{{ venue.name }}</h4>
              <div class="mt-1 text-sm text-[#706F6C] dark:text-[#8A8A88] space-y-1">
                <p class="flex items-center">
                  <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  {{ venue.location }}
                </p>
                <p class="flex items-center">
                  <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  Capacity: {{ venue.capacity }}
                </p>
              </div>
            </div>
          </div>
          
          <!-- Date & Time -->
          <div class="bg-[#F8F8F6] dark:bg-[#222220] rounded-lg p-4">
            <div class="flex items-center justify-between text-sm">
              <div>
                <p class="text-[#706F6C] dark:text-[#8A8A88]">Date</p>
                <p class="font-medium mt-1">{{ formatDate(date) }}</p>
              </div>
              <div class="text-right">
                <p class="text-[#706F6C] dark:text-[#8A8A88]">Time</p>
                <p class="font-medium mt-1">{{ timeSlot?.time }}</p>
              </div>
            </div>
          </div>
          
          <!-- Additional Notes -->
          <div>
            <label class="block text-sm font-medium mb-2">Additional Notes</label>
            <textarea
              rows="3"
              class="w-full rounded-lg border border-[#e8e8e6] dark:border-[#222220] bg-white dark:bg-[#1A1A1A] p-3 text-sm focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400"
              placeholder="Any special requests or notes..."
            ></textarea>
          </div>
        </div>
      </div>
      
      <!-- Footer -->
      <div class="p-6 border-t border-[#e8e8e6] dark:border-[#222220] bg-[#F8F8F6] dark:bg-[#1A1A1A] rounded-b-xl">
        <div class="flex items-center justify-end space-x-3">
          <button
            @click="$emit('close')"
            class="px-4 py-2 text-sm font-medium rounded-lg border border-[#e8e8e6] dark:border-[#222220] hover:bg-white dark:hover:bg-[#222220]"
          >
            Cancel
          </button>
          <button
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
          >
            Confirm Booking
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
