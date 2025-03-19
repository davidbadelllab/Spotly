<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    user: {
        name: string;
        email: string;
    };
}>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
});

const submit = () => {
    form.patch(route('settings.profile.update'));
};
</script>

<template>
    <AppLayout>
        <Head title="Profile Settings" />

        <div class="mx-auto max-w-2xl space-y-8 p-6">
            <div>
                <h2 class="text-2xl font-bold">Profile Settings</h2>
                <p class="text-muted-foreground">Update your account profile information.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            type="text"
                            required
                            class="mt-1 block w-full"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            required
                            class="mt-1 block w-full"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing">Save Changes</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
