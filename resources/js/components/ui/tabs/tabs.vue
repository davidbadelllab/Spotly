<template>
  <div class="tabs">
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { provide, ref, watch } from 'vue'

const props = defineProps({
  defaultValue: {
    type: String,
    required: true
  }
})

const selectedTab = ref(props.defaultValue)
provide('selectedTab', selectedTab)

// Watch for defaultValue changes
watch(() => props.defaultValue, (newValue) => {
  selectedTab.value = newValue
})
</script>

<style scoped>
.tabs {
  width: 100%;
}
</style>
