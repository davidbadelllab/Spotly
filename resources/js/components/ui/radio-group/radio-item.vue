<template>
    <label class="radio-item" :class="{ 'radio-item-checked': isChecked }">
      <input
        type="radio"
        :value="value"
        :checked="isChecked"
        @change="onChanged"
        class="radio-input"
      />
      <div class="radio-control">
        <div class="radio-indicator"></div>
      </div>
      <div class="radio-label" v-if="$slots.default">
        <slot></slot>
      </div>
    </label>
  </template>
  
  <script>
  export default {
    name: 'RadioItem',
    props: {
      value: {
        type: [String, Number, Boolean],
        required: true
      }
    },
    inject: ['radioGroup'],
    computed: {
      isChecked() {
        return this.radioGroup.modelValue() === this.value
      }
    },
    methods: {
      onChanged() {
        this.radioGroup.updateModelValue(this.value)
      }
    }
  }
  </script>
  
  <style scoped>
  .radio-item {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
  }
  
  .radio-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .radio-control {
    position: relative;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    background-color: #fff;
    margin-right: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
  }
  
  .radio-indicator {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background-color: transparent;
    transition: all 0.2s ease;
  }
  
  .radio-item-checked .radio-control {
    border-color: #3b82f6;
  }
  
  .radio-item-checked .radio-indicator {
    background-color: #3b82f6;
  }
  
  .radio-label {
    font-size: 0.875rem;
    color: #4b5563;
  }
  </style>