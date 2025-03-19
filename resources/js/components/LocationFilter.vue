<script setup lang="ts">
import { ref } from 'vue';

const locations = ref([
  { id: 'downtown', name: 'Downtown', count: 5 },
  { id: 'north-campus', name: 'North Campus', count: 3 },
  { id: 'east-side', name: 'East Side', count: 2 },
  { id: 'west-park', name: 'West Park', count: 4 },
  { id: 'main-building', name: 'Main Building', count: 6 },
]);

const selectedLocations = ref<string[]>([]);

const toggleLocation = (locationId: string) => {
  const index = selectedLocations.value.indexOf(locationId);
  if (index === -1) {
    selectedLocations.value.push(locationId);
  } else {
    selectedLocations.value.splice(index, 1);
  }
};
</script>

<template>
  <div>
    <h4 class="text-sm font-medium mb-3">Location</h4>
    <div class="space-y-2">
      <label
        v-for="location in locations"
        :key="location.id"
        class="flex items-center justify-between group cursor-pointer p-2 rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#222220]"
      >
        <div class="flex items-center">
          <input
            type="checkbox"
            :value="location.id"
            v-model="selectedLocations"
            class="w-4 h-4 rounded border-[#e8e8e6] dark:border-[#222220] text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400"
          />
          <span class="ml-3 text-sm">{{ location.name }}</span>
        </div>
        <span class="text-xs text-[#706F6C] dark:text-[#8A8A88] group-hover:text-[#1b1b18] dark:group-hover:text-[#EDEDEC]">
          {{ location.count }}
        </span>
      </label>
    </div>
  </div>
</template>
