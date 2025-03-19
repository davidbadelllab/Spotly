import { defineComponent, h } from 'vue';
import { cn } from '../../lib/utils';

const Alert = defineComponent({
  name: 'Alert',
  props: {
    variant: {
      type: String,
      default: 'default',
      validator: (value: string) => ['default', 'destructive', 'success'].includes(value),
    },
  },
  setup(props, { slots }) {
    return () => h('div', {
      role: 'alert',
      class: cn(
        'relative w-full rounded-lg border p-4',
        {
          'bg-background text-foreground': props.variant === 'default',
          'border-destructive/50 text-destructive dark:border-destructive': props.variant === 'destructive',
          'border-green-200 bg-green-50 text-green-800': props.variant === 'success',
        }
      ),
    }, slots.default?.());
  },
});

const AlertTitle = defineComponent({
  name: 'AlertTitle',
  setup(_, { slots }) {
    return () => h('h5', {
      class: 'mb-1 font-medium leading-none tracking-tight',
    }, slots.default?.());
  },
});

const AlertDescription = defineComponent({
  name: 'AlertDescription',
  setup(_, { slots }) {
    return () => h('div', {
      class: 'text-sm [&_p]:leading-relaxed',
    }, slots.default?.());
  },
});

export { Alert, AlertTitle, AlertDescription };
