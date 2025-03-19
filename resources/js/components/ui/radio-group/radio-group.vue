<template>
    <div class="radio-group">
      <div class="radio-group-label" v-if="label">{{ label }}</div>
      <div class="radio-options">
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'RadioGroup',
    props: {
      label: {
        type: String,
        default: ''
      },
      modelValue: {
        type: [String, Number, Boolean],
        default: null
      }
    },
    emits: ['update:modelValue'],
    provide() {
      return {
        radioGroup: {
          name: this.name,
          modelValue: () => this.modelValue,
          updateModelValue: (value) => {
            this.$emit('update:modelValue', value)
          }
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .radio-group-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    margin-bottom: 0.25rem;
  }
  
  .radio-options {
    display: flex;
    gap: 1rem;
  }
  </style>