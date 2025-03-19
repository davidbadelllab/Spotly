<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button, Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui';

interface Plan {
    id: number;
    name: string;
    slug: string;
    price: number;
    billing_period: 'monthly' | 'yearly';
    features: string[];
    max_venues: number;
    max_users: number;
}

interface Props {
    plan: Plan;
    company: {
        id: number;
        name: string;
        subscription?: {
            id: number;
            status: string;
            ends_at: string;
        };
    };
}

const props = defineProps<Props>();

const form = useForm({});

const subscribe = () => {
    form.post(route('plans.subscribe', props.plan.id));
};

const cancel = () => {
    if (confirm('Are you sure you want to cancel your subscription?')) {
        form.post(route('plans.cancel'));
    }
};

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const isCurrentPlan = () => {
    return props.company.subscription?.status === 'active';
};

const subscriptionEndsAt = () => {
    if (!props.company.subscription?.ends_at) return null;
    return new Date(props.company.subscription.ends_at).toLocaleDateString();
};
</script>

<template>
    <AppLayout>
        <Head :title="plan.name" />

        <div class="mx-auto max-w-4xl space-y-8 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">{{ plan.name }}</h1>
                    <p class="mt-2 text-muted-foreground">Plan Details</p>
                </div>
                <Button variant="outline" :href="route('plans.index')">
                    Back to Plans
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>
                        <div class="flex items-baseline justify-between">
                            <span>{{ plan.name }}</span>
                            <div class="flex items-baseline">
                                <span class="text-2xl font-bold">{{ formatPrice(plan.price) }}</span>
                                <span class="text-muted-foreground">/{{ plan.billing_period }}</span>
                            </div>
                        </div>
                    </CardTitle>
                    <CardDescription>
                        {{ plan.billing_period === 'yearly' ? 'Billed annually' : 'Billed monthly' }}
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-6">
                        <div>
                            <h3 class="font-medium mb-2">Features</h3>
                            <ul class="space-y-2">
                                <li v-for="(feature, index) in plan.features" :key="index" class="flex items-center">
                                    <svg class="h-4 w-4 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-medium mb-2">Limits</h3>
                            <ul class="space-y-2">
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ plan.max_venues < 0 ? 'Unlimited venues' : `Up to ${plan.max_venues} venues` }}
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-4 w-4 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    {{ plan.max_users < 0 ? 'Unlimited users' : `Up to ${plan.max_users} users` }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </CardContent>
                <CardFooter>
                    <div class="w-full space-y-4">
                        <div v-if="isCurrentPlan()" class="text-center">
                            <p class="text-sm text-muted-foreground mb-4">
                                You are currently on this plan.
                                <span v-if="subscriptionEndsAt()">
                                    Your subscription will end on {{ subscriptionEndsAt() }}.
                                </span>
                            </p>
                            <Button
                                variant="outline"
                                class="text-red-600 hover:bg-red-50"
                                @click="cancel"
                                :disabled="form.processing"
                            >
                                Cancel Subscription
                            </Button>
                        </div>
                        <Button
                            v-else
                            class="w-full"
                            @click="subscribe"
                            :disabled="form.processing"
                        >
                            Subscribe to {{ plan.name }}
                        </Button>
                    </div>
                </CardFooter>
            </Card>
        </div>
    </AppLayout>
</template>
