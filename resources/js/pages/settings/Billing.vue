<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button, Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui';

interface Subscription {
    id: number;
    status: string;
    ends_at: string | null;
}

interface Plan {
    id: number;
    name: string;
    price: number;
    billing_period: 'monthly' | 'yearly';
}

interface Props {
    company: {
        id: number;
        name: string;
        subscription?: Subscription;
    };
    currentPlan?: Plan;
}

const props = defineProps<Props>();

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(price);
};

const subscriptionEndsAt = () => {
    if (!props.company.subscription?.ends_at) return null;
    return new Date(props.company.subscription.ends_at).toLocaleDateString();
};
</script>

<template>
    <AppLayout>
        <Head title="Billing" />

        <div class="mx-auto max-w-4xl space-y-8 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Billing</h1>
                    <p class="mt-2 text-muted-foreground">Manage your subscription and billing information</p>
                </div>
                <Button variant="outline" :href="route('plans.index')">
                    View All Plans
                </Button>
            </div>

            <!-- Current Plan -->
            <Card>
                <CardHeader>
                    <CardTitle>Current Plan</CardTitle>
                    <CardDescription>
                        Your current subscription plan and status
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="currentPlan" class="space-y-4">
                        <div class="flex justify-between items-baseline">
                            <div>
                                <h3 class="text-lg font-semibold">{{ currentPlan.name }}</h3>
                                <p class="text-sm text-muted-foreground">
                                    {{ formatPrice(currentPlan.price) }}/{{ currentPlan.billing_period }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium" :class="{
                                    'bg-green-100 text-green-800': company.subscription?.status === 'active',
                                    'bg-yellow-100 text-yellow-800': company.subscription?.status === 'canceled',
                                }">
                                    {{ company.subscription?.status === 'active' ? 'Active' : 'Canceled' }}
                                </span>
                                <p v-if="subscriptionEndsAt()" class="mt-1 text-sm text-muted-foreground">
                                    Ends on {{ subscriptionEndsAt() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-6">
                        <p class="text-muted-foreground">You don't have an active subscription</p>
                        <Button class="mt-4" :href="route('plans.index')">
                            Choose a Plan
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Payment Method -->
            <Card>
                <CardHeader>
                    <CardTitle>Payment Method</CardTitle>
                    <CardDescription>
                        Manage your payment methods
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <p class="text-center text-muted-foreground py-6">
                        Payment methods will be implemented with Stripe integration
                    </p>
                </CardContent>
            </Card>

            <!-- Billing History -->
            <Card>
                <CardHeader>
                    <CardTitle>Billing History</CardTitle>
                    <CardDescription>
                        View your past invoices and payments
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <p class="text-center text-muted-foreground py-6">
                        Billing history will be available once Stripe is integrated
                    </p>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
