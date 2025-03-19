<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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
    plans: Plan[];
    currentPlan?: Plan;
    company: {
        id: number;
        name: string;
    };
}

const props = defineProps<Props>();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};
</script>

<template>
    <AppLayout>
        <Head title="Subscription Plans" />

        <div class="mx-auto max-w-7xl space-y-8 p-6">
            <div class="text-center">
                <h1 class="text-3xl font-bold">Choose Your Plan</h1>
                <p class="mt-2 text-muted-foreground">Select the plan that best fits your business needs</p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <Card
                    v-for="plan in plans"
                    :key="plan.id"
                    :class="[
                        'relative',
                        currentPlan?.id === plan.id ? 'border-primary ring-2 ring-primary' : '',
                    ]"
                >
                    <CardHeader>
                        <CardTitle>{{ plan.name }}</CardTitle>
                        <CardDescription>
                            <div class="flex items-baseline">
                                <span class="text-2xl font-bold">{{ formatPrice(plan.price) }}</span>
                                <span class="text-muted-foreground">/{{ plan.billing_period }}</span>
                            </div>
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <ul class="space-y-2">
                            <li v-for="(feature, index) in plan.features" :key="index" class="flex items-center">
                                <svg class="h-4 w-4 text-primary mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                {{ feature }}
                            </li>
                        </ul>
                    </CardContent>
                    <CardFooter>
                        <div class="w-full">
                            <Button
                                v-if="currentPlan?.id === plan.id"
                                variant="outline"
                                class="w-full"
                                :href="route('plans.show', plan.id)"
                            >
                                Current Plan
                            </Button>
                            <Button
                                v-else
                                class="w-full"
                                :href="route('plans.show', plan.id)"
                            >
                                Select Plan
                            </Button>
                        </div>
                    </CardFooter>
                </Card>
            </div>

            <div v-if="currentPlan" class="text-center">
                <p class="text-sm text-muted-foreground">
                    You are currently on the {{ currentPlan.name }} plan.
                    <Link
                        :href="route('plans.show', currentPlan.id)"
                        class="text-primary hover:underline"
                    >
                        View plan details
                    </Link>
                </p>
            </div>
        </div>
    </AppLayout>
</template>
