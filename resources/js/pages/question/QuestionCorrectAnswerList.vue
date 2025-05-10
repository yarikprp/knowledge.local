<template>
    <Head title="Ответы" />
    <AppLayout :breadcrumbs="[{ title: 'Ответы', href: 'question/correct-answer' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Ответы"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="correctAnswers" :search="search" item-value="id" no-data-text="Ответов нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as CorrectAnswer)"
                        @delete="deleteItem(item as CorrectAnswer)"
                    />
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <QuestionCorrectAnswerModal v-model="dialog" :AnswerItem="selected" :questions="props.questions" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import QuestionCorrectAnswerModal from '@/components/Modal/Question/QuestionCorrectAnswerModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

interface CorrectAnswer {
    id: number;
    text: string;
    question: {
        name: string;
    };
}

const props = defineProps<{
    correctAnswers: CorrectAnswer[];
    questions: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<CorrectAnswer | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'text' },
    { title: 'Вопрос', key: 'question.name' },
]);

interface CorrectAnswer {
    id: number;
    text: string;
    question_id: number;
    questions: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['correctAnswers'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const addItem = () => {
    selected.value = null;
    dialog.value = true;
};

const updateItem = (item: CorrectAnswer) => {
    selected.value = item;
    dialog.value = true;
};

const deleteItem = (item: CorrectAnswer) => {
    if (confirm(`Удалить ответа "${item.text}"?`)) {
        Inertia.delete(`/question/correct-answer/${item.id}`, {
            onSuccess: () => {
                alert('Ответ успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении ответа.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
