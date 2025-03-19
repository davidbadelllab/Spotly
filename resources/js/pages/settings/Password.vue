<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('settings.password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Change Password" />

        <div class="mx-auto max-w-2xl space-y-8 p-6">
            <div>
                <h2 class="text-2xl font-bold">Change Password</h2>
                <p class="text-muted-foreground">Update your account password.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div class="space-y-4">
                    <div>
                        <Label for="current_password">Current Password</Label>
                        <Input
                            id="current_password"
                            v-model="form.current_password"
                            type="password"
                            required
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                        />
                        <InputError :message="form.errors.current_password" />
                    </div>

                    <div>
                        <Label for="password">New Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            required
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div>
                        <Label for="password_confirmation">Confirm Password</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="form.processing">Update Password</Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
