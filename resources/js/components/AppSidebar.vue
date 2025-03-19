<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, MapPin, Calendar, Settings, CreditCard, Palette, User, Key } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Locations',
        href: route('locations'),
        icon: MapPin,
    },
    {
        title: 'My Reservations',
        href: route('my-reservations'),
        icon: Calendar,
    },
];

const settingsNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: route('settings.profile'),
        icon: User,
    },
    {
        title: 'Password',
        href: route('settings.password'),
        icon: Key,
    },
    {
        title: 'Theme',
        href: route('settings.theme'),
        icon: Palette,
    },
    {
        title: 'Billing',
        href: route('settings.billing'),
        icon: CreditCard,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <div class="mt-4 px-2 py-2">
                <h2 class="mb-2 px-4 text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                    Settings
                </h2>
                <NavMain :items="settingsNavItems" />
            </div>
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
