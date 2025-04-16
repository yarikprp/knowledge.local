<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { ref } from 'vue';
import { type BreadcrumbItem } from '@/types';
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    news: {
        type: Array,
        required: true,
    },
});

const headers = ref([
    { title: 'ID', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Автор', key: 'user' },
    { title: 'Тип', key: 'type' },
    { title: 'Содержание', key: 'content' },
]);

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Список новостей',
    href: '/news/newsList',
  },
];
</script>

<template>
    <Head title="Новости" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="pa-4">
            <h2 class="text-h5 mb-4">Список новостей</h2>

            <VDataTable :headers="headers" :items="news" class="elevation-1" item-value="id" no-data-text="Новостей нет">
                <template #item.user="{ item }">
                    {{ item.user.name }}
                </template>

                <template #item.type="{ item }">
                    {{ item.type.name }}
                </template>
            </VDataTable>
        </div>
    </AppLayout>
</template>
