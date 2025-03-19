<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button, Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle, Input, Label, Textarea } from '@/components/ui';
import InputError from '@/components/InputError.vue';
import VenueTypeIcon from '@/components/VenueTypeIcon.vue';

interface Props {
    company: {
        name?: string;
        type?: string;
        address?: string;
        city?: string;
        state?: string;
        country?: string;
        phone?: string;
        website?: string;
        description?: string;
    };
}

const props = defineProps<Props>();

const form = useForm({
    name: props.company?.name || '',
    type: props.company?.type || '',
    address: props.company?.address || '',
    city: props.company?.city || '',
    state: props.company?.state || '',
    country: props.company?.country || '',
    phone: props.company?.phone || '',
    website: props.company?.website || '',
    description: props.company?.description || '',
});

const submit = () => {
    form.put(route('company.update'));
};

const deleteCompany = () => {
    if (confirm('Are you sure you want to delete your company? This action cannot be undone.')) {
        form.delete(route('company.delete'));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Manage Company" />

        <div class="mx-auto max-w-4xl space-y-8 p-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold">Manage Company</h1>
                <Button variant="outline" class="text-red-600 hover:bg-red-50" @click="deleteCompany">
                    Delete Company
                </Button>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Company Type Selection -->
                <Card>
                    <CardHeader>
                        <CardTitle>Company Type</CardTitle>
                        <CardDescription>Select your business type</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div
                                v-for="type in ['hotel', 'sports', 'restaurant', 'coworking']"
                                :key="type"
                                :class="[
                                    'cursor-pointer rounded-lg border p-4 transition-colors hover:border-primary',
                                    form.type === type ? 'border-primary bg-primary/5' : '',
                                ]"
                                @click="form.type = type"
                            >
                                <div class="flex items-center gap-3">
                                    <VenueTypeIcon :type="type" :size="32" />
                                    <div>
                                        <h3 class="font-medium capitalize">{{ type }}</h3>
                                        <p class="text-sm text-muted-foreground">
                                            {{ type === 'hotel' ? 'Manage hotel rooms and amenities' :
                                               type === 'sports' ? 'Manage sports courts and facilities' :
                                               type === 'restaurant' ? 'Manage restaurant tables and reservations' :
                                               'Manage office spaces and meeting rooms' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InputError :message="form.errors.type" class="mt-2" />
                    </CardContent>
                </Card>

                <!-- Company Details -->
                <Card>
                    <CardHeader>
                        <CardTitle>Company Details</CardTitle>
                        <CardDescription>Update your company information</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="name">Company Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="phone">Phone Number</Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    required
                                />
                                <InputError :message="form.errors.phone" />
                            </div>

                            <div class="space-y-2">
                                <Label for="website">Website</Label>
                                <Input
                                    id="website"
                                    v-model="form.website"
                                    type="url"
                                    placeholder="https://"
                                />
                                <InputError :message="form.errors.website" />
                            </div>

                            <div class="space-y-2">
                                <Label for="address">Address</Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div class="space-y-2">
                                <Label for="city">City</Label>
                                <Input
                                    id="city"
                                    v-model="form.city"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.city" />
                            </div>

                            <div class="space-y-2">
                                <Label for="state">State</Label>
                                <Input
                                    id="state"
                                    v-model="form.state"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.state" />
                            </div>

                            <div class="space-y-2">
                                <Label for="country">Country</Label>
                                <Input
                                    id="country"
                                    v-model="form.country"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.country" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                required
                                rows="4"
                            />
                            <InputError :message="form.errors.description" />
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button
                            type="submit"
                            :disabled="form.processing"
                        >
                            Save Changes
                        </Button>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
