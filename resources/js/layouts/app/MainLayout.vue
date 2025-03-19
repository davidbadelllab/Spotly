<script setup lang="ts">
import AppContent from '../../components/AppContent.vue';
import AppShell from '../../components/AppShell.vue';
import MainSidebar from '../../components/MainSidebar.vue';
import AppSidebarHeader from '../../components/AppSidebarHeader.vue';
import { usePage } from '@inertiajs/vue3';
import type { BreadcrumbItemType } from '../../types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

const page = usePage();
const user = {
    initials: page.props.auth.user.name.split(' ').map((n: string) => n[0]).join(''),
    name: page.props.auth.user.name,
    plan: 'Premium Plan',
};

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppShell variant="sidebar">
        <MainSidebar :user="user" />
        <AppContent variant="sidebar">
            <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            <slot />
        </AppContent>
    </AppShell>
</template>
