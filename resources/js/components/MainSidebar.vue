<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface User {
  initials: string;
  name: string;
  plan?: string;
  user_type: 'business' | 'customer';
}

interface Props {
  user: User;
}

defineProps<Props>();
</script>

<template>
  <aside class="hidden lg:flex flex-col w-64 border-r border-[#e8e8e6] dark:border-[#222220] p-6 space-y-8">
    <div class="flex items-center mb-8">
      <div class="w-8 h-8 rounded bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center mr-3">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
          <path d="M12 16L6 10H18L12 16Z" fill="currentColor" />
        </svg>
      </div>
      <h1 class="text-xl font-semibold">SPOTLY</h1>
    </div>
    
    <nav class="space-y-1">
      <Link 
        :href="route('dashboard')" 
        class="flex items-center p-3 text-sm text-indigo-600 bg-indigo-50 rounded-lg dark:bg-indigo-900/20 dark:text-indigo-400"
      >
        <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h2"></path>
        </svg>
        Dashboard
      </Link>

      <Link 
        :href="route('my-reservations')" 
        class="flex items-center p-3 text-sm text-[#1b1b18] dark:text-[#ADADAA] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A]"
      >
        <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        {{ user.user_type === 'business' ? 'Reservations' : 'My Reservations' }}
      </Link>

      <Link 
        :href="route('locations')" 
        class="flex items-center p-3 text-sm text-[#1b1b18] dark:text-[#ADADAA] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A]"
      >
        <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        {{ user.user_type === 'business' ? 'My Venues' : 'Locations' }}
      </Link>

      <template v-if="user.user_type === 'business'">
        <Link 
          :href="route('plans.index')" 
          class="flex items-center p-3 text-sm text-[#1b1b18] dark:text-[#ADADAA] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A]"
        >
          <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path>
          </svg>
          Plans
        </Link>

        <Link 
          :href="route('settings.billing')" 
          class="flex items-center p-3 text-sm text-[#1b1b18] dark:text-[#ADADAA] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A]"
        >
          <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Billing
        </Link>
      </template>

      <!-- Settings Section -->
      <div class="space-y-1">
        <div class="flex items-center p-3 text-sm text-[#1b1b18] dark:text-[#ADADAA] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A]">
          <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          Settings
        </div>
        
        <!-- Settings Submenu -->
        <div class="ml-8 space-y-1">
          <Link 
            :href="route('settings.profile')"
            class="flex items-center p-2 text-sm text-[#706F6C] dark:text-[#8A8A88] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
          >
            <svg width="16" height="16" fill="none" class="mr-2" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
          </Link>
          
          <Link 
            :href="route('settings.password')"
            class="flex items-center p-2 text-sm text-[#706F6C] dark:text-[#8A8A88] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
          >
            <svg width="16" height="16" fill="none" class="mr-2" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            Password
          </Link>
          
          <Link 
            :href="route('settings.theme')"
            class="flex items-center p-2 text-sm text-[#706F6C] dark:text-[#8A8A88] rounded-lg hover:bg-[#F2F2F0] dark:hover:bg-[#1A1A1A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]"
          >
            <svg width="16" height="16" fill="none" class="mr-2" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
            </svg>
            Theme
          </Link>
        </div>
      </div>
    </nav>
    
    <!-- User Profile -->
    <div class="mt-auto space-y-4">
      <div class="p-4 bg-[#F0F0EE] dark:bg-[#1A1A1A] rounded-lg">
        <div class="flex items-center">
          <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center mr-3">
            <span class="text-sm font-semibold">{{ user.initials }}</span>
          </div>
          <div>
            <p class="text-sm font-medium">{{ user.name }}</p>
            <p v-if="user.plan" class="text-xs text-[#706F6C] dark:text-[#8A8A88]">{{ user.plan }}</p>
          </div>
        </div>
      </div>
      
      <!-- Logout Button -->
      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="w-full flex items-center p-3 text-sm text-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20"
      >
        <svg width="20" height="20" fill="none" class="mr-3" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Log out
      </Link>
    </div>
  </aside>
</template>
