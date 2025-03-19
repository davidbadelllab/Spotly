import { defineComponent, h, ref, onMounted, onBeforeUnmount } from 'vue';
import { createPopper } from '@popperjs/core';
import { cn } from '@/lib/utils';

const Tooltip = defineComponent({
  name: 'Tooltip',
  props: {
    content: {
      type: String,
      required: true,
    },
    placement: {
      type: String,
      default: 'top',
      validator: (value: string) => ['top', 'bottom', 'left', 'right'].includes(value),
    },
    class: {
      type: String,
      default: '',
    },
  },
  setup(props, { slots }) {
    const tooltipRef = ref<HTMLElement | null>(null);
    const triggerRef = ref<HTMLElement | null>(null);
    let popperInstance: any = null;

    const showTooltip = () => {
      if (tooltipRef.value) {
        tooltipRef.value.style.display = 'block';
        popperInstance?.update();
      }
    };

    const hideTooltip = () => {
      if (tooltipRef.value) {
        tooltipRef.value.style.display = 'none';
      }
    };

    onMounted(() => {
      if (triggerRef.value && tooltipRef.value) {
        popperInstance = createPopper(triggerRef.value, tooltipRef.value, {
          placement: props.placement,
          modifiers: [
            {
              name: 'offset',
              options: {
                offset: [0, 8],
              },
            },
          ],
        });
      }
    });

    onBeforeUnmount(() => {
      popperInstance?.destroy();
    });

    return () => [
      h('div', {
        ref: triggerRef,
        onMouseenter: showTooltip,
        onMouseleave: hideTooltip,
        class: 'inline-block',
      }, slots.default?.()),
      h('div', {
        ref: tooltipRef,
        class: cn(
          'z-50 hidden rounded-md bg-primary px-3 py-1.5 text-xs text-primary-foreground animate-in fade-in-0 zoom-in-95',
          props.class
        ),
      }, props.content),
    ];
  },
});

export { Tooltip };
