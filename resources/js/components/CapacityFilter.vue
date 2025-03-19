<script setup lang="ts">
import { ref, computed } from 'vue';

const minCapacity = ref(1);
const maxCapacity = ref(25);
const selectedCapacity = ref(4);

const percentage = computed(() => {
  return ((selectedCapacity.value - minCapacity.value) / (maxCapacity.value - minCapacity.value)) * 100;
});

const handleInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  selectedCapacity.value = parseInt(target.value);
};
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-3">
      <h4 class="text-sm font-medium">Capacity</h4>
      <span class="text-sm text-[#706F6C] dark:text-[#8A8A88]">{{ selectedCapacity }} people</span>
    </div>
    
    <div class="relative">
      <input
        type="range"
        :min="minCapacity"
        :max="maxCapacity"
        v-model="selectedCapacity"
        @input="handleInput"
        class="w-full h-2 bg-[#F2F2F0] dark:bg-[#222220] rounded-lg appearance-none cursor-pointer"
      />
      
      <!-- Custom Range Slider Styling -->
      <div
        class="absolute left-0 top-0 h-2 bg-indigo-600 dark:bg-indigo-500 rounded-l-lg"
        :style="{ width: `${percentage}%` }"
      ></div>
      
      <div
        class="absolute w-4 h-4 bg-white dark:bg-[#1A1A1A] border-2 border-indigo-600 dark:border-indigo-500 rounded-full -mt-1.5 transform -translate-x-1/2"
        :style="{ left: `${percentage}%` }"
      ></div>
    </div>
    
    <div class="flex justify-between mt-2">
      <span class="text-xs text-[#706F6C] dark:text-[#8A8A88]">{{ minCapacity }}</span>
      <span class="text-xs text-[#706F6C] dark:text-[#8A8A88]">{{ maxCapacity }}</span>
    </div>
  </div>
</template>

<style scoped>
/* Hide default range slider styling */
input[type="range"] {
  -webkit-appearance: none;
  appearance: none;
  background: transparent;
}

input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 16px;
  height: 16px;
  background: transparent;
  cursor: pointer;
  position: relative;
  z-index: 1;
}

input[type="range"]::-moz-range-thumb {
  width: 16px;
  height: 16px;
  background: transparent;
  cursor: pointer;
  position: relative;
  z-index: 1;
  border: none;
}
</style>
