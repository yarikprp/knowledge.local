<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import { formatDate } from '@/utils/formatDate';

defineProps({
    user: {
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
    { title: 'ФИО', key: 'name' },
    { title: 'Почта', key: 'email' },
    { title: 'Верификация', key: 'email_verified_at' },
    { title: 'Создан', key: 'created_at' },
    { title: 'Обновлен', key: 'updated_at' },
]);

interface Item {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    created_at: string;
    updated_at: string;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['user'],
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
        Inertia.delete(`/user/${item.id}`, {
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

const goToView = (item: Item) => {
    router.visit(`/user/${item.id}`);
};

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head title="Пользователи" />
    <AppLayout :breadcrumbs="[{ title: 'Список пользователей', href: '/user/userList' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список пользователей"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="user" :search="search" item-value="id" no-data-text="Нет данных" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: true }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                        @goToView="goToView(item as Item)"
                    />
                </template>

                <template v-slot:[`item.email_verified_at`]="{ item }">
                    <span>{{ formatDate(item.email_verified_at) }}</span>
                </template>
                <template v-slot:[`item.created_at`]="{ item }">
                    <span>{{ formatDate(item.created_at) }}</span>
                </template>
                <template v-slot:[`item.updated_at`]="{ item }">
                    <span>{{ formatDate(item.updated_at) }}</span>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>
        </div>
    </AppLayout>
</template>
