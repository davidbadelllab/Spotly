import { defineComponent, h } from 'vue';
import { cn } from '@/lib/utils';

const Progress = defineComponent({
  name: 'Progress',
  props: {
    value: {
      type: Number,
      default: 0,
    },
    max: {
      type: Number,
      default: 100,
    },
    class: {
      type: String,
      default: '',
    },
  },
  setup(props) {
    return () => h('div', {
      role: 'progressbar',
      'aria-valuemin': 0,
      'aria-valuemax': props.max,
      'aria-valuenow': props.value,
      class: cn(
        'relative h-2 w-full overflow-hidden rounded-full bg-primary/20',
        props.class
      ),
    }, [
      h('div', {
        class: 'h-full w-full flex-1 bg-primary transition-all',
        style: { transform: `translateX(-${100 - (props.value / props.max * 100)}%)` },
      }),
    ]);
  },
});

export { Progress };
