<template>
    <Head title="Список вопросов" />
    <AppLayout :breadcrumbs="[{ title: 'Список вопросов', href: '/question' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список вопросов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="questions" :search="search" item-value="id" no-data-text="Вопросов нет" :loading="isLoading">
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

            <QuestionModal
                v-model="dialog"
                :QuestionItem="selected"
                :tests="props.tests"
                :questionTypes="props.questionTypes"
                @saved="refreshItems"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import QuestionModal from '@/components/Modal/Question/QuestionModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps<{
    questions: any[];
    tests: { id: number; name: string }[];
    questionTypes: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Аттестация', key: 'test.name' },
    { title: 'Тип вопроса', key: 'question_type.name' },
    { title: 'Текст вопроса', key: 'question_text' },
    { title: 'Награда', key: 'points' },
    { title: 'Порядок', key: 'order' },
]);

interface Item {
    id: number;
    name: string;
    test_id: number;
    test: {
        id: number;
        name: string;
    };
    question_type_id: number;
    question_type: {
        id: number;
        name: string;
    };
    question_text: string;
    points: number;
    order: number;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['questions'],
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
    if (confirm(`Удалить вопрос "${item.name}"?`)) {
        Inertia.delete(`/question/${item.id}`, {
            onSuccess: () => {
                alert('Вопрос успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при вопроса.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
