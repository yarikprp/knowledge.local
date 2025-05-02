<template>
    <Head title="Список материалов" />
    <AppLayout :breadcrumbs="[{ title: 'Список материалов', href: '/material' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список материалов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="materials" :search="search" item-value="id" no-data-text="Материалов нет" :loading="isLoading">
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

            <MaterialModal v-model="dialog" :MaterialItem="selected" :subjects="props.subjects" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import MaterialModal from '@/components/Modal/Education/MaterialModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps<{
    materials: any[];
    subjects: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Контент', key: 'content' },
    { title: 'Предмет', key: 'subject.name' },
]);

interface Item {
    id: number;
    name: string;
    content: string;
    subject_id: number;
    subjects: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['materials'],
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
    if (confirm(`Удалить материал "${item.name}"?`)) {
        Inertia.delete(`/material/${item.id}`, {
            onSuccess: () => {
                alert('Материал успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при материала.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
