<script setup lang="ts">
const availabilityTypes = [
  { label: 'Available', color: 'bg-green-500' },
  { label: 'Partially Available', color: 'bg-yellow-500' },
  { label: 'Fully Booked', color: 'bg-red-500' }
];
</script>

<template>
  <div class="space-y-2">
    <div v-for="type in availabilityTypes" :key="type.label" class="flex items-center space-x-2">
      <div :class="[type.color, 'w-3 h-3 rounded-full']"></div>
      <span class="text-sm text-[#706F6C] dark:text-[#8A8A88]">{{ type.label }}</span>
    </div>
  </div>
</template>
