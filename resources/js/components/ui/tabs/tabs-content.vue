<template>
  <div v-show="isSelected" class="tabs-content">
    <slot></slot>
  </div>
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
</script>

<style scoped>
.tabs-content {
  width: 100%;
}
</style>
