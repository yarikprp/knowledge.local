<template>
    <Head title="Аттестации" />
    <AppLayout :breadcrumbs="[{ title: 'Список аттестаций', href: '/test' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список аттестаций"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="tests" :search="search" item-value="id" no-data-text="Аттестаций нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template v-slot:[`item.test_type`]="{ item }">
                    {{ (item as Item).test_type.name }}
                </template>

                <template v-slot:[`item.is_active`]="{ item }">
                    <v-icon v-if="(item as Item).is_active">mdi-check-circle</v-icon>
                    <v-icon v-else>mdi-circle-outline</v-icon>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <TestModal v-model="dialog" :testItem="selected" :testTypes="props.testTypes" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import TestModal from '@/components/Modal/Test/TestModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps<{
    tests: any[];
    testTypes: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Название', key: 'name' },
    { title: 'Слаг', key: 'slug' },
    { title: 'Описание', key: 'description' },
    { title: 'Тип теста', key: 'test_type' },
    { title: 'Начало', key: 'start_date' },
    { title: 'Конец', key: 'end_date' },
    { title: 'Попытки', key: 'attempt_limit' },
    { title: 'Ограничение', key: 'time_limit' },
    { title: 'Активность', key: 'is_active' },
]);

interface Item {
    id: number;
    name: string;
    description: string;
    slug: string;
    test_type: {
        id: number;
        name: string;
    };
    start_date: string;
    end_date: string;
    attempt_limit: number;
    time_limit: number;
    is_active: boolean;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['tests'],
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
    if (confirm(`Удалить тест "${item.name}"?`)) {
        Inertia.delete(`/test/${item.id}`, {
            onSuccess: () => {
                alert('Тест успешно удалён.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении теста.');
            },
        });
    }
};


const goBack = () => {
    window.history.back();
};
</script>
