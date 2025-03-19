<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';

// Sample data - in real app this would come from props
const upcomingReservations = [
    {
        id: 1,
        venue: 'Downtown Sports Complex',
        type: 'Tennis Court',
        date: '2024-03-15',
        time: '14:00 - 15:00',
        status: 'confirmed',
        price: '$30.00',
        image: '/images/tennis-court.jpg',
    },
    {
        id: 2,
        venue: 'Luxury Hotel & Spa',
        type: 'Deluxe Room',
        date: '2024-03-20',
        time: 'Check-in: 15:00',
        status: 'pending',
        price: '$250.00',
        image: '/images/hotel-room.jpg',
    },
];

const pastReservations = [
    {
        id: 3,
        venue: 'Gourmet Restaurant',
        type: 'Table for 4',
        date: '2024-03-01',
        time: '19:00 - 21:00',
        status: 'completed',
        price: '$180.00',
        image: '/images/restaurant-table.jpg',
    },
    {
        id: 4,
        venue: 'Downtown Sports Complex',
        type: 'Basketball Court',
        date: '2024-02-28',
        time: '16:00 - 17:00',
        status: 'completed',
        price: '$40.00',
        image: '/images/basketball-court.jpg',
    },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'confirmed':
            return 'text-green-600';
        case 'pending':
            return 'text-yellow-600';
        case 'completed':
            return 'text-blue-600';
        default:
            return 'text-gray-600';
    }
};
</script>

<template>
    <AppLayout>
        <Head title="My Reservations" />

        <div class="mx-auto max-w-7xl p-6">
            <div class="mb-8">
                <h2 class="text-2xl font-bold">My Reservations</h2>
                <p class="text-muted-foreground">Manage your bookings and reservations</p>
            </div>

            <Tabs defaultValue="upcoming" class="space-y-6">
                <TabsList>
                    <TabsTrigger value="upcoming">Upcoming</TabsTrigger>
                    <TabsTrigger value="past">Past</TabsTrigger>
                </TabsList>

                <TabsContent value="upcoming">
                    <div class="grid gap-6 md:grid-cols-2">
                        <Card v-for="reservation in upcomingReservations" :key="reservation.id">
                            <div class="aspect-video w-full bg-muted">
                                <img
                                    :src="reservation.image"
                                    :alt="reservation.venue"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <CardHeader>
                                <CardTitle>{{ reservation.venue }}</CardTitle>
                                <CardDescription>{{ reservation.type }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Date</span>
                                        <span class="text-sm">{{ reservation.date }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Time</span>
                                        <span class="text-sm">{{ reservation.time }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Price</span>
                                        <span class="text-sm">{{ reservation.price }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Status</span>
                                        <span :class="['text-sm font-medium', getStatusColor(reservation.status)]">
                                            {{ reservation.status }}
                                        </span>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter class="flex gap-2">
                                <Button variant="outline" class="flex-1">Modify</Button>
                                <Button variant="destructive" class="flex-1">Cancel</Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>

                <TabsContent value="past">
                    <div class="grid gap-6 md:grid-cols-2">
                        <Card v-for="reservation in pastReservations" :key="reservation.id">
                            <div class="aspect-video w-full bg-muted">
                                <img
                                    :src="reservation.image"
                                    :alt="reservation.venue"
                                    class="h-full w-full object-cover"
                                />
                            </div>
                            <CardHeader>
                                <CardTitle>{{ reservation.venue }}</CardTitle>
                                <CardDescription>{{ reservation.type }}</CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Date</span>
                                        <span class="text-sm">{{ reservation.date }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Time</span>
                                        <span class="text-sm">{{ reservation.time }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Price</span>
                                        <span class="text-sm">{{ reservation.price }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-muted-foreground">Status</span>
                                        <span :class="['text-sm font-medium', getStatusColor(reservation.status)]">
                                            {{ reservation.status }}
                                        </span>
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button variant="outline" class="w-full">Book Again</Button>
                            </CardFooter>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
