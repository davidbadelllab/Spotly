<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import MainSidebar from '../components/MainSidebar.vue';
import MainHeader from '../components/MainHeader.vue';
import ReservationCalendar from '../components/ReservationCalendar.vue';
import VenueSelector from '../components/VenueSelector.vue';
import BookingForm from '../components/BookingForm.vue';
import RecentBookings from '../components/RecentBookings.vue';
import CapacityFilter from '../components/CapacityFilter.vue';
import LocationFilter from '../components/LocationFilter.vue';
import AvailabilityIndicator from '../components/AvailabilityIndicator.vue';
import StatsSummary from '../components/StatsSummary.vue';

interface Venue {
  id: number;
  name: string;
  type: string;
  capacity: number;
  location: string;
  image: string;
}

interface TimeSlot {
  id: string;
  time: string;
  available: boolean;
}

interface Notification {
  id: number;
  message: string;
  isRead: boolean;
  time: string;
}

interface Booking {
  id: number;
  venue: string;
  date: string;
  time: string;
  status: 'confirmed' | 'pending' | 'cancelled';
}


type VenueType = 'sports' | 'hotels' | 'restaurants';

// User data
const user = {
  initials: 'JS',
  name: 'John Smith',
  plan: 'Premium Plan'
};

// Reactive states
const activeVenueType = ref<VenueType>('sports');
const selectedVenue = ref<Venue | null>(null);
const selectedDate = ref<Date>(new Date());
const selectedTimeSlot = ref<TimeSlot | null>(null);
const showBookingForm = ref(false);

// Mock notifications data
const notifications = ref<Notification[]>([
  { id: 1, message: 'Your booking for Padel Court 1 is confirmed', isRead: false, time: '2 hours ago' },
  { id: 2, message: 'Upcoming reservation tomorrow at 18:00', isRead: true, time: '1 day ago' },
  { id: 3, message: 'New venue available in your area', isRead: false, time: '3 days ago' },
]);

// Mock data - would come from API in real application
const venues = ref<Record<VenueType, Venue[]>>({
  sports: [
    { id: 1, name: 'Padel Court 1', type: 'padel', capacity: 4, location: 'Downtown', image: '/images/padel-1.jpg' },
    { id: 2, name: 'Padel Court 2', type: 'padel', capacity: 4, location: 'Downtown', image: '/images/padel-2.jpg' },
    { id: 3, name: 'Soccer Field A', type: 'soccer', capacity: 22, location: 'North Campus', image: '/images/soccer-1.jpg' },
    { id: 4, name: 'Basketball Court', type: 'basketball', capacity: 10, location: 'East Side', image: '/images/basketball-1.jpg' },
    { id: 5, name: 'Baseball Diamond', type: 'baseball', capacity: 18, location: 'West Park', image: '/images/baseball-1.jpg' },
  ],
  hotels: [
    { id: 101, name: 'Deluxe Suite 101', type: 'suite', capacity: 2, location: 'Main Building', image: '/images/suite-1.jpg' },
    { id: 102, name: 'Double Room 205', type: 'double', capacity: 2, location: 'East Wing', image: '/images/double-1.jpg' },
    { id: 103, name: 'Family Suite 307', type: 'family', capacity: 4, location: 'West Wing', image: '/images/family-1.jpg' },
  ],
  restaurants: [
    { id: 201, name: 'Table 1 (Window)', type: 'table', capacity: 2, location: 'Main Floor', image: '/images/table-1.jpg' },
    { id: 202, name: 'Table 5 (Booth)', type: 'booth', capacity: 4, location: 'Main Floor', image: '/images/booth-1.jpg' },
    { id: 203, name: 'Private Dining Room', type: 'private', capacity: 10, location: 'Upper Floor', image: '/images/private-1.jpg' },
  ],
});

const recentBookings = ref<Booking[]>([
  { id: 1001, venue: 'Padel Court 1', date: '2025-03-09', time: '18:00-19:00', status: 'confirmed' },
  { id: 1002, venue: 'Table 5 (Booth)', date: '2025-03-15', time: '20:00-22:00', status: 'pending' },
]);


// Computed properties
const filteredVenues = computed(() => {
  return venues.value[activeVenueType.value] || [];
});

const stats = computed(() => {
  return {
    totalReservations: 24,
    upcomingReservations: 3,
    favoriteVenue: 'Padel Court 1',
    percentageIncrease: 15,
  };
});

// Methods
const selectVenue = (venue: Venue) => {
  selectedVenue.value = venue;
  showBookingForm.value = true;
};

const selectTimeSlot = (slot: TimeSlot) => {
  selectedTimeSlot.value = slot;
  showBookingForm.value = true;
};

const closeBookingForm = () => {
  showBookingForm.value = false;
  selectedTimeSlot.value = null;
};


const changeVenueType = (type: VenueType) => {
  activeVenueType.value = type;
  selectedVenue.value = null;
  showBookingForm.value = false;
};
</script>

<template>
  <Head title="ReserveHub - Your One-Stop Booking Solution" />
  
  <div class="flex min-h-screen bg-gradient-to-br from-[#FCFCFA] to-[#F8F8F6] dark:from-[#0E0E0E] dark:to-[#121212] text-[#1b1b18] dark:text-[#EDEDEC]">
    <MainSidebar :user="user" />
    
    <!-- Main Content -->
    <main class="flex-grow p-6 pt-16 lg:p-8 lg:pt-8 overflow-y-auto">
      <div class="max-w-7xl mx-auto">
        <MainHeader 
          title="Make a Reservation"
          subtitle="Book your favorite venue in just a few clicks"
          :notifications="notifications"
        />
        
        <!-- Stats Summary Cards -->
        <StatsSummary :stats="stats" class="mb-8" />
        
        <!-- Venue Type Selector -->
        <div class="mb-8">
          <div class="flex flex-wrap gap-3 bg-white dark:bg-[#1A1A1A] rounded-lg p-1 inline-block">
            <button 
              @click="changeVenueType('sports')" 
              :class="[
                'px-4 py-2 text-sm rounded-md font-medium', 
                activeVenueType === 'sports' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-[#706F6C] dark:text-[#8A8A88] hover:bg-[#F2F2F0] dark:hover:bg-[#222220]'
              ]"
            >
              Sports Facilities
            </button>
            <button 
              @click="changeVenueType('hotels')" 
              :class="[
                'px-4 py-2 text-sm rounded-md font-medium', 
                activeVenueType === 'hotels' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-[#706F6C] dark:text-[#8A8A88] hover:bg-[#F2F2F0] dark:hover:bg-[#222220]'
              ]"
            >
              Hotels
            </button>
            <button 
              @click="changeVenueType('restaurants')" 
              :class="[
                'px-4 py-2 text-sm rounded-md font-medium', 
                activeVenueType === 'restaurants' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-[#706F6C] dark:text-[#8A8A88] hover:bg-[#F2F2F0] dark:hover:bg-[#222220]'
              ]"
            >
              Restaurants
            </button>
          </div>
        </div>
        
        <!-- Filters and Calendar View -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
          <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-[#1A1A1A] rounded-xl shadow-sm p-5 border border-[#e8e8e6] dark:border-[#222220]">
              <h3 class="text-lg font-medium mb-4">Filters</h3>
              
              <LocationFilter class="mb-6" />
              <CapacityFilter class="mb-6" />
              
              <div class="pt-4 border-t border-[#e8e8e6] dark:border-[#222220]">
                <h4 class="text-sm font-medium mb-3">Availability</h4>
                <AvailabilityIndicator />
              </div>
            </div>
            
            <RecentBookings :bookings="recentBookings" />
          </div>
          
          <div class="lg:col-span-2">
            <div class="bg-white dark:bg-[#1A1A1A] rounded-xl shadow-sm p-5 border border-[#e8e8e6] dark:border-[#222220]">
              <h3 class="text-lg font-medium mb-4">Select Date & Time</h3>
              <ReservationCalendar 
                :selected-date="selectedDate" 
                @update:selected-date="selectedDate = $event" 
                @select-slot="selectTimeSlot"
              />
            </div>
          </div>
        </div>
        
        <!-- Venue Selection -->
        <div class="mb-8">
          <h3 class="text-xl font-semibold mb-4">Available Venues</h3>
          <VenueSelector 
            :venues="filteredVenues" 
            :selected-venue="selectedVenue" 
            @select-venue="selectVenue"
          />
        </div>
      </div>
    </main>
    
    <!-- Booking Form Modal -->
    <BookingForm 
      v-if="showBookingForm"
      :venue="selectedVenue"
      :date="selectedDate"
      :time-slot="selectedTimeSlot"
      @close="closeBookingForm"
    />
  </div>
</template>
