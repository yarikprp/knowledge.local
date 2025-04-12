<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Настройки темы',
        href: '/settings/appearance',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Настройки темы" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall title="Настройки темы" description="Обновите настройки внешнего вида вашей учетной записи" />
                <AppearanceTabs />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
