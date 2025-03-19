<template>
  <button
    class="tabs-trigger inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
    :class="{
      'bg-background text-foreground shadow-sm': isSelected,
      'hover:bg-muted hover:text-foreground': !isSelected
    }"
    @click="handleClick"
  >
    <slot></slot>
  </button>
</template>

<script setup lang="ts">
import { inject, computed } from 'vue'

const props = defineProps({
  value: {
    type: String,
    required: true
  }
})

const selectedTab = inject('selectedTab') as { value: string }

const isSelected = computed(() => {
  return selectedTab.value === props.value
})

const handleClick = () => {
  selectedTab.value = props.value
}
</script>

<style scoped>
.tabs-trigger {
  position: relative;
}
</style>
