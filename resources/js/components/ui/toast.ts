// @/components/ui/toast.ts
import { computed, ref, h, type Component } from 'vue'
import { cva, type VariantProps } from 'class-variance-authority'

// Definición de las variantes de estilo para el toast usando CVA
const toastVariants = cva(
  'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-4 pr-6 shadow-lg transition-all data-[swipe=cancel]:translate-x-0 data-[swipe=end]:translate-x-[var(--radix-toast-swipe-end-x)] data-[swipe=move]:translate-x-[var(--radix-toast-swipe-move-x)] data-[swipe=move]:transition-none data-[state=open]:animate-in data-[state=closed]:animate-out data-[swipe=end]:animate-out data-[state=closed]:fade-out-80 data-[state=closed]:slide-out-to-right-full data-[state=open]:slide-in-from-top-full',
  {
    variants: {
      variant: {
        default: 'border bg-background',
        destructive: 'destructive group border-destructive bg-destructive text-destructive-foreground',
        success: 'border-green-500 bg-green-50 text-green-700',
        warning: 'border-yellow-500 bg-yellow-50 text-yellow-700',
        info: 'border-blue-500 bg-blue-50 text-blue-700',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  }
)

// Sistema de gestión de toasts
const MAX_TOASTS = 5
const TOAST_DURATION = 5000 // 5 segundos por defecto
const toasts = ref<Toast[]>([])
let toastId = 0

// Interfaz para el toast
export interface Toast {
  id: number
  title: string
  description?: string
  variant?: 'default' | 'destructive' | 'success' | 'warning' | 'info'
  duration?: number
  icon?: Component
  action?: {
    label: string
    onClick: () => void
  }
  onClose?: () => void
}

// Función principal para crear un toast
export function toast(options: Omit<Toast, 'id'>) {
  const id = toastId++
  const newToast: Toast = {
    id,
    ...options,
    duration: options.duration || TOAST_DURATION,
  }
  
  // Añadir el toast a la lista
  toasts.value.push(newToast)
  
  // Asegurar que no excedemos el máximo número de toasts
  if (toasts.value.length > MAX_TOASTS) {
    toasts.value.shift()
  }
  
  // Configurar la eliminación automática del toast
  setTimeout(() => {
    closeToast(id)
    if (options.onClose) {
      options.onClose()
    }
  }, newToast.duration)
  
  return id
}

// Función para cerrar un toast específico
export function closeToast(id: number) {
  const index = toasts.value.findIndex(t => t.id === id)
  if (index !== -1) {
    toasts.value.splice(index, 1)
  }
}

// Función para cerrar todos los toasts
export function clearToasts() {
  toasts.value = []
}

// Componentes exportados
export const Toaster = {
  name: 'Toaster',
  setup() {
    return () => h('div', {
      class: 'fixed top-4 right-4 z-50 flex flex-col gap-2 w-full max-w-sm',
    }, 
    toasts.value.map(toast => 
      h('div', {
        key: toast.id,
        class: toastVariants({ variant: toast.variant }),
      }, [
        h('div', { class: 'flex gap-3 items-center' }, [
          toast.icon && h(toast.icon, { class: 'h-5 w-5' }),
          h('div', { class: 'grid gap-1' }, [
            h('div', { class: 'text-sm font-semibold' }, toast.title),
            toast.description && h('div', { class: 'text-xs text-muted-foreground' }, toast.description)
          ])
        ]),
        h('div', { class: 'flex items-center gap-2' }, [
          toast.action && h('button', {
            class: 'inline-flex items-center justify-center rounded-md text-xs font-medium ring-offset-background transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-3',
            onClick: toast.action.onClick
          }, toast.action.label),
          h('button', {
            class: 'rounded-full p-1 text-foreground/50 opacity-0 transition-opacity hover:text-foreground focus:opacity-100 focus:outline-none focus:ring-1 group-hover:opacity-100',
            onClick: () => closeToast(toast.id)
          }, 'X')
        ])
      ])
    ))
  }
}

// Variantes para TypeScript
export type ToastVariants = VariantProps<typeof toastVariants>

// Acceso a la lista de toasts para componentes
export const useToasts = () => {
  return {
    toasts: computed(() => toasts.value),
    toast,
    closeToast,
    clearToasts
  }
}