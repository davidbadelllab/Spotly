<script setup lang="ts">
import { ref, computed } from 'vue';

interface Props {
  selectedDate: Date;
}

const props = defineProps<Props>();
const emit = defineEmits<{
  'update:selected-date': [date: Date];
  'select-slot': [slot: { id: string; time: string; available: boolean }];
}>();

const currentDate = ref(new Date());

// Calendar navigation
const previousMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() - 1, 1);
};

const nextMonth = () => {
  currentDate.value = new Date(currentDate.value.getFullYear(), currentDate.value.getMonth() + 1, 1);
};

// Calendar display helpers
const monthName = computed(() => {
  return currentDate.value.toLocaleString('default', { month: 'long' });
});

const yearDisplay = computed(() => {
  return currentDate.value.getFullYear();
});

// Generate calendar days
const days = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const daysInMonth = lastDay.getDate();
  const startingDay = firstDay.getDay();
  
  const days = [];
  
  // Add empty slots for days before the first of the month
  for (let i = 0; i < startingDay; i++) {
    days.push(null);
  }
  
  // Add the days of the month
  for (let i = 1; i <= daysInMonth; i++) {
    days.push(new Date(year, month, i));
  }
  
  return days;
});

// Time slots
const timeSlots = computed(() => {
  return [
    { id: '1', time: '09:00-10:00', available: true },
    { id: '2', time: '10:00-11:00', available: true },
    { id: '3', time: '11:00-12:00', available: false },
    { id: '4', time: '12:00-13:00', available: true },
    { id: '5', time: '13:00-14:00', available: true },
    { id: '6', time: '14:00-15:00', available: false },
    { id: '7', time: '15:00-16:00', available: true },
    { id: '8', time: '16:00-17:00', available: true },
  ];
});

const selectDate = (date: Date) => {
  if (!date) return;
  emit('update:selected-date', date);
};

const formattedSelectedDate = computed(() => {
  return props.selectedDate.toLocaleDateString('default', { 
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
});

const isSelectedDate = (date: Date | null) => {
  if (!date) return false;
  return date.toDateString() === props.selectedDate.toDateString();
};

const isToday = (date: Date | null) => {
  if (!date) return false;
  const today = new Date();
  return date.toDateString() === today.toDateString();
};
</script>

<template>
  <div class="space-y-6">
    <!-- Calendar Navigation -->
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <button @click="previousMonth" class="p-2 hover:bg-[#F2F2F0] dark:hover:bg-[#222220] rounded-lg">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <span class="text-lg font-medium">{{ monthName }} {{ yearDisplay }}</span>
        <button @click="nextMonth" class="p-2 hover:bg-[#F2F2F0] dark:hover:bg-[#222220] rounded-lg">
          <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Calendar Grid -->
    <div class="grid grid-cols-7 gap-1">
      <!-- Day headers -->
      <div v-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day" 
        class="text-center text-sm font-medium text-[#706F6C] dark:text-[#8A8A88] py-2">
        {{ day }}
      </div>
      
      <!-- Calendar days -->
      <div v-for="(day, index) in days" :key="index" 
        class="aspect-square p-1">
        <button v-if="day"
          @click="selectDate(day)"
          :class="[
            'w-full h-full rounded-lg flex items-center justify-center text-sm transition-colors',
            isSelectedDate(day) ? 'bg-indigo-600 text-white' : 
            isToday(day) ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400' :
            'hover:bg-[#F2F2F0] dark:hover:bg-[#222220]'
          ]"
        >
          {{ day.getDate() }}
        </button>
      </div>
    </div>

    <!-- Time Slots -->
    <div class="mt-6">
      <h4 class="text-sm font-medium mb-3">Available Time Slots for {{ formattedSelectedDate }}</h4>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <button v-for="slot in timeSlots" :key="slot.id"
          @click="emit('select-slot', slot)"
          :class="[
            'p-2 rounded-lg text-sm text-center transition-colors',
            slot.available ? 
              'bg-[#F2F2F0] dark:bg-[#222220] hover:bg-[#E8E8E6] dark:hover:bg-[#2A2A28]' : 
              'bg-[#F2F2F0] dark:bg-[#222220] opacity-50 cursor-not-allowed'
          ]"
          :disabled="!slot.available"
        >
          {{ slot.time }}
        </button>
      </div>
    </div>
  </div>
</template>
