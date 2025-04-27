<script setup lang="ts">
import NewsModal from '@/components/Modal/News/NewsModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineProps, ref, onMounted } from 'vue';
import { useDisplay } from 'vuetify';

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
    }
});


const search = ref('');

const { mdAndUp } = useDisplay();

const headers = ref([
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Автор', key: 'user' },
    { title: 'Тип', key: 'type' },
    { title: 'Содержание', key: 'content' },
]);

interface NewsItem {
    id: number;
    name: string;
    content: string;
    user: { name: string };
    type: { name: string };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Список новостей',
        href: '/news/newsList',
    },
];

const dialog = ref(false);
const selectedNews = ref(null);
const users = ref([]);
const newsTypes = ref([]);

onMounted(() => {
  users.value = props.users;
  newsTypes.value = props.newsTypes;
});

const refreshNews = () => {
    window.location.reload();
};

const addNews = () => {
    selectedNews.value = null;
    dialog.value = true;
};

const updateNews = (item: NewsItem) => {
    selectedNews.value = item;
    dialog.value = true;
};
</script>

<template>
    <Head title="Новости" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="pa-4">
            <VToolbar>
                <VToolbarTitle>
                    <span class="text-h6">Список новостей</span>
                </VToolbarTitle>

                <VTextField
                    v-model="search"
                    label="Поиск"
                    prepend-inner-icon="mdi-magnify"
                    class="ml-4"
                    variant="outlined"
                    clearable
                    density="compact"
                    single-line
                    hide-details
                    style="max-width: 300px"
                />

                <div class="flex-grow-1"></div>

                <v-menu :close-on-content-click="false" transition="slide-y-transition" offset-y bottom>
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" color="primary" class="white--text ml-1">
                            <v-icon :left="mdAndUp ? '' : 'mdi-menu'">mdi-dots-vertical</v-icon>
                            <span class="d-none d-md-flex">Действия</span>
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item @click="refreshNews">
                            <v-list-item-title>Обновить</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="addNews">
                            <v-list-item-title>Добавить</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </VToolbar>

            <VDataTable :headers="headers" :items="news" :search="search" item-value="id" no-data-text="Новостей нет">
                <template #item.user="{ item }">
                    {{ (item as NewsItem).user.name }}
                </template>

                <template #item.type="{ item }">
                    {{ (item as NewsItem).type.name }}
                </template>
            </VDataTable>

            <NewsModal v-model="dialog" :newsItem="selectedNews"
    :users="users"
    :newsTypes="newsTypes" @saved="refreshNews" />
        </div>
    </AppLayout>
</template>
