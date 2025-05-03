<template>
    <Head title="Список предметов" />
    <AppLayout :breadcrumbs="[{ title: 'Список предметов', href: '/subject' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список предметов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="subject" :search="search" item-value="id" no-data-text="Вопросов нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: true }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                        @goToView="goToView(item as Item)"
                    />
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <SubjectModal v-model="dialog" :SubjectFormItem="selected" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import SubjectModal from '@/components/Modal/Education/SubjectModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps({
    subject: {
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
    { title: 'Описание', key: 'description' },
]);

interface Item {
    id: number;
    name: string;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['subject'],
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
    if (confirm(`Удалить предмет "${item.name}"?`)) {
        Inertia.delete(`/subject/${item.id}`, {
            onSuccess: () => {
                alert('Предмет успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении предмета.');
            },
        });
    }
};

const goToView = (item: Item) => {
    router.visit(`/subject/${item.id}`);
};

const goBack = () => {
    window.history.back();
};
</script>
