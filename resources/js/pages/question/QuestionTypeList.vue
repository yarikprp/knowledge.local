<template>
    <Head title="Список типов вопросов" />
    <AppLayout :breadcrumbs="[{ title: 'Список типов вопросов', href: '/question/type' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список типов вопросов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="questionType" :search="search" item-value="id" no-data-text="Вопросов нет" :loading="isLoading">
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

            <QuestionTypeModal v-model="dialog" :QuestionTypeItem="selected" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import QuestionTypeModal from '@/components/Modal/Question/QuestionTypeModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps({
    questionType: {
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
        only: ['newsType'],
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
    if (confirm(`Удалить тип вопроса "${item.name}"?`)) {
        Inertia.delete(`/question/type/${item.id}`, {
            onSuccess: () => {
                alert('Тип вопроса успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении типа вопроса.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
