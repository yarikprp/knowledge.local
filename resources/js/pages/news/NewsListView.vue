<template>
    <Head title="Новости" />
    <AppLayout :breadcrumbs="[{ title: 'Список новостей', href: '/news/listView' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список новостей"
                :isParentLoading="isLoading"
                :showSearch="true"
                :hasAdd="false"
                v-model="search"
                @refresh="refreshItems"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="news" :search="search" item-value="id" no-data-text="Новостей нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: false, isDelete: false, isGoToView: true }"
                        @goToView="goToView(item as Item)"
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
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps({
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

const goToView = (item: Item) => {
    router.visit(`/news/${item.id}`);
};

const goBack = () => {
    window.history.back();
};
</script>
