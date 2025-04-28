<template>
    <Head title="Новости" />
    <AppLayout :breadcrumbs="[{ title: 'Список новостей', href: '/news/newsList' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список новостей"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="news" :search="search" item-value="id" no-data-text="Новостей нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template v-slot:[`item.user`]="{ item }">
                    {{ (item as Item).user.name }}
                </template>

                <template v-slot:[`item.type`]="{ item }">
                    {{ (item as Item).type.name }}
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <NewsModal v-model="dialog" :newsItem="selected" :users="props.users" :newsTypes="props.newsTypes" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import NewsModal from '@/components/Modal/News/NewsModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
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
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Автор', key: 'user' },
    { title: 'Тип', key: 'type' },
    { title: 'Содержание', key: 'content' },
]);

interface Item {
    id: number;
    name: string;
    content: string;
    user: {
        id: number;
        name: string;
    };
    type: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['news'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const addItem = () => {
    selected.value = null;
    dialog.value = true;
};

const updateItem = (item: Item) => {
    selected.value = item;
    dialog.value = true;
};

const deleteItem = (item: Item) => {
    if (confirm(`Удалить новость "${item.name}"?`)) {
        Inertia.delete(`/news/${item.id}`, {
            onSuccess: () => {
                alert('Новость успешно удалена.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении новости.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
