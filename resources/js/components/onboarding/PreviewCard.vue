<script setup lang="ts">
import { computed } from 'vue';
import { Button, Card, CardContent, CardHeader, CardTitle, CardDescription } from '../ui';
import { Globe, Map, Phone, Check, Eye } from 'lucide-vue-next';
import VenueTypeIcon from '../VenueTypeIcon.vue';
import type { OnboardingData } from '../../composables/useOnboarding';

interface Props {
  data: OnboardingData;
  venueTypes: Record<'hotel' | 'sports' | 'restaurant' | 'coworking', {
    name: string;
    description: string;
    icon: string;
    benefits: string[];
  }>;
  onClose: () => void;
}

const props = defineProps<Props>();

const formattedAddress = computed(() => {
  return [props.data.address, props.data.city, props.data.state, props.data.country]
    .filter(Boolean)
    .join(', ');
});
</script>

<template>
  <Card class="relative overflow-hidden">
    <div class="absolute top-2 right-2">
      <Button variant="outline" size="sm" @click="onClose">
        Salir de vista previa
      </Button>
    </div>

    <CardHeader class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
      <div class="flex items-center gap-3">
        <div class="flex-shrink-0 w-16 h-16 rounded-lg bg-primary/10 flex items-center justify-center overflow-hidden">
          <Transition name="fade" mode="out-in">
            <template v-if="data.logo">
              <img :src="data.logo" :alt="data.name" class="w-full h-full object-contain" />
            </template>
            <template v-else>
              <VenueTypeIcon :type="data.type || 'hotel'" :size="32" />
            </template>
          </Transition>
        </div>
        <div>
          <CardTitle class="text-2xl">{{ data.name || 'Tu Negocio' }}</CardTitle>
          <CardDescription>{{ data.type ? venueTypes[data.type].name : 'Selecciona un tipo' }}</CardDescription>
        </div>
      </div>
    </CardHeader>

    <CardContent>
      <div class="grid gap-6 md:grid-cols-2">
        <div>
          <h3 class="text-lg font-medium mb-2">Sobre nosotros</h3>
          <p class="text-muted-foreground">{{ data.description || 'Descripción de tu negocio' }}</p>

          <div class="mt-4 space-y-2">
            <div class="flex items-center gap-2">
              <Map class="w-4 h-4 text-muted-foreground" />
              <span>{{ formattedAddress }}</span>
            </div>
            <div class="flex items-center gap-2">
              <Phone class="w-4 h-4 text-muted-foreground" />
              <span>{{ data.phone || 'Sin teléfono' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <Globe class="w-4 h-4 text-muted-foreground" />
              <span>{{ data.website || 'Sin sitio web' }}</span>
            </div>
          </div>
        </div>

        <div>
          <h3 class="text-lg font-medium mb-2">Características</h3>
          <ul class="space-y-1">
            <TransitionGroup name="list" tag="div">
              <li v-for="(benefit, i) in (data.type ? venueTypes[data.type].benefits : [])" :key="i"
                  class="flex items-center gap-2">
                <Check class="w-4 h-4 text-green-500" />
                <span>{{ benefit }}</span>
              </li>
            </TransitionGroup>
          </ul>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}
</style>
