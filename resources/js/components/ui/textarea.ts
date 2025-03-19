import { defineComponent, h } from 'vue';

export const Textarea = defineComponent({
  props: {
    modelValue: {
      type: String,
      default: '',
    },
    rows: {
      type: Number,
      default: 4,
    },
    required: {
      type: Boolean,
      default: false,
    },
  },
  emits: ['update:modelValue'],
  setup(props, { emit }) {
    return () =>
      h('textarea', {
        value: props.modelValue,
        rows: props.rows,
        required: props.required,
        class: 'w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
        onInput: (event: Event) => {
          emit('update:modelValue', (event.target as HTMLTextAreaElement).value);
        },
      });
  },
});
