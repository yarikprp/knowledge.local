<template>
    <Head title="Новости" />
    <AppLayout :breadcrumbs="[{ title: 'Список новостей', href: '/news/newsList' }]">
        <div class="pa-4">
            <ToolbarDataTable title="Список новостей" :showSearch="true" v-model="search" @refreshNews="refreshNews" @addNews="addNews" />

            <VDataTable :headers="headers" :items="news" :search="search" item-value="id" no-data-text="Новостей нет">
                <template v-slot:item.user="{ item }">
                    {{ item.user.name }}
                </template>

                <template v-slot:item.type="{ item }">
                    {{ item.type.name }}
                </template>
            </VDataTable>

            <NewsModal v-model="dialog" :newsItem="selectedNews" :users="props.users" :newsTypes="props.newsTypes" @saved="refreshNews" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import NewsModal from '@/components/Modal/News/NewsModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps({
    news: {
        type: Array,
        required: true,
    },
    users: {
        type: Array,
        required: true,
    },
    newsTypes: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const selectedNews = ref(null);
const dialog = ref(false);

const headers = ref([
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Автор', key: 'user' },
    { title: 'Тип', key: 'type' },
    { title: 'Содержание', key: 'content' },
]);

const refreshNews = () => {
    window.location.reload();
};

const addNews = () => {
    selectedNews.value = null;
    dialog.value = true;
};
</script>
