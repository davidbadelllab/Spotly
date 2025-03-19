import { defineComponent, h } from 'vue';

export const Card = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('div', {
        class: 'rounded-lg border bg-card text-card-foreground shadow-sm',
      }, slots.default?.());
  },
});

export const CardHeader = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('div', {
        class: 'flex flex-col space-y-1.5 p-6',
      }, slots.default?.());
  },
});

export const CardTitle = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('h3', {
        class: 'text-lg font-semibold leading-none tracking-tight',
      }, slots.default?.());
  },
});

export const CardDescription = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('p', {
        class: 'text-sm text-muted-foreground',
      }, slots.default?.());
  },
});

export const CardContent = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('div', {
        class: 'p-6 pt-0',
      }, slots.default?.());
  },
});

export const CardFooter = defineComponent({
  setup(props, { slots }) {
    return () =>
      h('div', {
        class: 'flex items-center p-6 pt-0',
      }, slots.default?.());
  },
});
