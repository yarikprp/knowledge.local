<template>
    <Head title="Список типов аттестации" />
    <AppLayout :breadcrumbs="[{ title: 'Список типов аттестации', href: '/test/type' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список типов теста"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="testType" :search="search" item-value="id" no-data-text="Аттестаций нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <TestTypeModal v-model="dialog" :TestTypeItem="selected" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import TestTypeModal from '@/components/Modal/Test/TestTypeModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps({
    testType: {
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
]);

interface Item {
    id: number;
    name: string;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['testType'],
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
    if (confirm(`Удалить тип теста "${item.name}"?`)) {
        Inertia.delete(`/test/type/${item.id}`, {
            onSuccess: () => {
                alert('Тип теста успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении типа теста.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
