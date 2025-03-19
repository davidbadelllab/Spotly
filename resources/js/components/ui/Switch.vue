<template>
    <button
      type="button"
      role="switch"
      :aria-checked="modelValue"
      :data-state="modelValue ? 'checked' : 'unchecked'"
      :class="[
        'peer inline-flex h-[24px] w-[44px] shrink-0 cursor-pointer items-center rounded-full border-2 border-transparent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background disabled:cursor-not-allowed disabled:opacity-50 data-[state=checked]:bg-primary data-[state=unchecked]:bg-input',
        className
      ]"
      @click="updateModelValue(!modelValue)"
    >
      <span
        :data-state="modelValue ? 'checked' : 'unchecked'"
        class="pointer-events-none block h-5 w-5 rounded-full bg-background shadow-lg ring-0 transition-transform data-[state=checked]:translate-x-5 data-[state=unchecked]:translate-x-0"
      />
    </button>
  </template>
  
  <script setup lang="ts">
  import { computed } from 'vue';
  
  interface Props {
    modelValue: boolean;
    className?: string;
    disabled?: boolean;
  }
  
  const props = withDefaults(defineProps<Props>(), {
    className: '',
    disabled: false
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  const updateModelValue = (value: boolean) => {
    if (!props.disabled) {
      emit('update:modelValue', value);
    }
  };
  </script>