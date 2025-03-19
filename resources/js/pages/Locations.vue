<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

// Sample data - in real app this would come from props
const locations = [
    {
        id: 1,
        name: 'Downtown Sports Complex',
        type: 'Sports Facility',
        address: '123 Main St, Downtown',
        city: 'San Francisco',
        state: 'CA',
        rating: 4.8,
        image: '/images/sports-complex.jpg',
        amenities: ['Indoor Courts', 'Parking', 'Locker Rooms'],
    },
    {
        id: 2,
        name: 'Luxury Hotel & Spa',
        type: 'Hotel',
        address: '456 Park Ave',
        city: 'San Francisco',
        state: 'CA',
        rating: 4.9,
        image: '/images/hotel.jpg',
        amenities: ['Pool', 'Spa', 'Restaurant'],
    },
    {
        id: 3,
        name: 'Gourmet Restaurant',
        type: 'Restaurant',
        address: '789 Food St',
        city: 'San Francisco',
        state: 'CA',
        rating: 4.7,
        image: '/images/restaurant.jpg',
        amenities: ['Private Dining', 'Bar', 'Outdoor Seating'],
    },
];
</script>

<template>
    <AppLayout>
        <Head title="Locations" />

        <div class="mx-auto max-w-7xl p-6">
            <div class="mb-8">
                <h2 class="text-2xl font-bold">Locations</h2>
                <p class="text-muted-foreground">Discover venues near you</p>
            </div>

            <!-- Search and Filters -->
            <div class="mb-8 flex flex-wrap gap-4">
                <div class="flex-1">
                    <Input type="search" placeholder="Search locations..." class="max-w-md" />
                </div>
                <div class="flex gap-2">
                    <Button variant="outline">Filter</Button>
                    <Button variant="outline">Sort</Button>
                </div>
            </div>

            <!-- Locations Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="location in locations" :key="location.id" class="overflow-hidden">
                    <div class="aspect-video w-full bg-muted">
                        <img
                            :src="location.image"
                            :alt="location.name"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <CardTitle>{{ location.name }}</CardTitle>
                            <span class="flex items-center text-sm">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                    class="text-yellow-400"
                                >
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                {{ location.rating }}
                            </span>
                        </div>
                        <CardDescription>{{ location.type }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <p class="text-sm">
                                {{ location.address }}, {{ location.city }}, {{ location.state }}
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="amenity in location.amenities"
                                    :key="amenity"
                                    class="rounded-full bg-muted px-2 py-1 text-xs"
                                >
                                    {{ amenity }}
                                </span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button class="w-full" :href="route('venues.show', location.id)">View Details</Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
