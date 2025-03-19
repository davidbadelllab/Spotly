<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, setAppearance } = useAppearance();

const themes = [
    { value: 'light', label: 'Light', description: 'Light mode for bright environments' },
    { value: 'dark', label: 'Dark', description: 'Dark mode for low-light environments' },
    { value: 'system', label: 'System', description: 'Follow your system preferences' },
];
</script>

<template>
    <AppLayout>
        <Head title="Theme Settings" />

        <div class="mx-auto max-w-2xl space-y-8 p-6">
            <div>
                <h2 class="text-2xl font-bold">Theme Settings</h2>
                <p class="text-muted-foreground">Customize your interface appearance.</p>
            </div>

            <div class="space-y-6">
                <div>
                    <Label>Theme Preference</Label>
                    <RadioGroup v-model="appearance" class="mt-3 grid gap-4">
                        <div
                            v-for="theme in themes"
                            :key="theme.value"
                            class="relative flex cursor-pointer rounded-lg border p-4 hover:border-primary [&:has(:checked)]:border-primary"
                        >
                            <RadioGroupItem :value="theme.value" class="sr-only" />
                            <div class="grid gap-1.5">
                                <Label class="font-medium">{{ theme.label }}</Label>
                                <p class="text-sm text-muted-foreground">{{ theme.description }}</p>
                            </div>
                        </div>
                    </RadioGroup>
                </div>

                <div class="flex items-center gap-4">
                    <Button @click="setAppearance(appearance)">Save Preferences</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
