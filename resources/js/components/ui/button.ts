import { defineComponent, h } from 'vue';

export const Button = defineComponent({
  props: {
    variant: {
      type: String,
      default: 'default'
    },
    size: {
      type: String,
      default: 'default'
    },
    class: {
      type: String,
      default: ''
    }
  },
  setup(props, { slots }) {
    return () => h(
      'button',
      {
        class: [
          'inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none ring-offset-background',
          props.variant === 'ghost' ? 'hover:bg-accent hover:text-accent-foreground' : 'bg-primary text-primary-foreground hover:bg-primary/90',
          props.size === 'icon' ? 'h-10 w-10' : 'h-10 px-4 py-2',
          props.class
        ]
      },
      slots.default?.()
    );
  }
});
