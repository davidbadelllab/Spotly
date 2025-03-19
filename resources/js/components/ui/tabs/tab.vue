<template>
    <div v-if="isActive" class="tab-panel">
      <slot></slot>
    </div>
  </template>
  
  <script>
  export default {
    name: 'Tab',
    props: {
      name: {
        type: String,
        required: true
      },
      index: {
        type: Number,
        required: true
      }
    },
    inject: ['tabs'],
    computed: {
      isActive() {
        return this.tabs.activeTab() === this.index
      }
    }
  }
  </script>
  
  <style scoped>
  .tab-panel {
    width: 100%;
  }
  </style>