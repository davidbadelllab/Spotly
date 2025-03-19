<script setup lang="ts">
interface Props {
    type: 'hotel' | 'sports' | 'restaurant' | 'coworking';
    size?: number;
}

const props = withDefaults(defineProps<Props>(), {
    size: 24,
});

const icons = {
    hotel: `
        <path d="M19 19V4a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v15" />
        <path d="M2 19h20" />
        <path d="M12 2v9" />
        <path d="M8 8h8" />
        <path d="M9 13h6" />
    `,
    sports: `
        <circle cx="12" cy="12" r="10" />
        <path d="m4.93 4.93 4.24 4.24" />
        <path d="m14.83 9.17 4.24-4.24" />
        <path d="m14.83 14.83 4.24 4.24" />
        <path d="m9.17 14.83-4.24 4.24" />
        <circle cx="12" cy="12" r="4" />
    `,
    restaurant: `
        <path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2" />
        <path d="M7 2v20" />
        <path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7" />
    `,
    coworking: `
        <rect x="3" y="3" width="18" height="18" rx="2" />
        <path d="M7 7h10" />
        <path d="M7 12h10" />
        <path d="M7 17h10" />
    `,
};
</script>

<template>
    <svg
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        v-html="icons[type]"
    />
</template>
