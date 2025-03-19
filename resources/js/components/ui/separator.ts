import { defineComponent, h } from 'vue';
import { cn } from '../../lib/utils';

const Separator = defineComponent({
  name: 'Separator',
  props: {
    orientation: {
      type: String,
      default: 'horizontal',
      validator: (value: string) => ['horizontal', 'vertical'].includes(value),
    },
    class: {
      type: String,
      default: '',
    },
  },
  setup(props) {
    return () => h('div', {
      role: 'separator',
      'aria-orientation': props.orientation,
      class: cn(
        'shrink-0 bg-border',
        props.orientation === 'horizontal' ? 'h-[1px] w-full' : 'h-full w-[1px]',
        props.class
      ),
    });
  },
});

export { Separator };
