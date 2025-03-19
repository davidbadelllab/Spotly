// @/composables/useDebouncedRef.ts
import { ref, watch, type Ref } from 'vue'

/**
 * Crea una referencia debounced que solo cambia su valor después de que haya
 * pasado un tiempo determinado sin nuevos cambios en el valor original.
 * 
 * @param value - El valor inicial o referencia a observar
 * @param delay - El tiempo de espera en milisegundos (por defecto: 300ms)
 * @returns Una referencia reactiva con el valor debounced
 */
export function useDebouncedRef<T>(value: T | Ref<T>, delay = 300): Ref<T> {
  // Si el valor es una referencia, usamos su valor, si no, usamos el valor directamente
  const isRef = (val: any): val is Ref => val && typeof val === 'object' && '_value' in val
  const sourceValue = isRef(value) ? value : ref(value)
  
  // Crear la referencia debounced con el valor inicial
  const debouncedValue = ref(sourceValue.value) as Ref<T>
  
  // Variable para almacenar el temporizador
  let timeout: ReturnType<typeof setTimeout> | null = null
  
  // Observar cambios en el valor fuente
  watch(sourceValue, (newValue) => {
    // Limpiar el temporizador anterior si existe
    if (timeout) {
      clearTimeout(timeout)
      timeout = null
    }
    
    // Configurar un nuevo temporizador
    timeout = setTimeout(() => {
      debouncedValue.value = newValue
    }, delay)
  }, { immediate: false })
  
  return debouncedValue
}

/**
 * Versión avanzada que devuelve tanto el valor debounced como funciones
 * para controlar el comportamiento.
 */
export function useDebouncedRefAdvanced<T>(value: T | Ref<T>, delay = 300) {
  const isRef = (val: any): val is Ref => val && typeof val === 'object' && '_value' in val
  const sourceValue = isRef(value) ? value : ref(value)
  const debouncedValue = ref(sourceValue.value) as Ref<T>
  
  let timeout: ReturnType<typeof setTimeout> | null = null
  let isPending = ref(false)
  
  const update = (newValue: T) => {
    // Si es una referencia, actualizamos su valor
    if (isRef(value)) {
      value.value = newValue
    }
    // Si no es una referencia, simplemente actualizamos el valor debounced
    else {
      debouncedValue.value = newValue
    }
  }
  
  const cancelPending = () => {
    if (timeout) {
      clearTimeout(timeout)
      timeout = null
      isPending.value = false
    }
  }
  
  const flushNow = () => {
    if (timeout) {
      clearTimeout(timeout)
      timeout = null
      debouncedValue.value = sourceValue.value
      isPending.value = false
    }
  }
  
  watch(sourceValue, (newValue) => {
    isPending.value = true
    
    if (timeout) {
      clearTimeout(timeout)
    }
    
    timeout = setTimeout(() => {
      debouncedValue.value = newValue
      timeout = null
      isPending.value = false
    }, delay)
  })
  
  return {
    value: debouncedValue,
    pending: isPending,
    cancel: cancelPending,
    flush: flushNow,
    update
  }
}