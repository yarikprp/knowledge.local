<template>
    <Head title="Результаты" />
    <AppLayout :breadcrumbs="[{ title: 'Список результатов', href: '/test/answer' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список результатов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="answers" :search="search" item-value="id" no-data-text="Результатов нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template v-slot:[`item.question`]="{ item }">
                    {{ (item as Item).question.name }}
                </template>

                <template v-slot:[`item.attempt`]="{ item }">
                    {{ (item as Item).attempt.id }}
                </template>
                <template v-slot:[`item.is_correct`]="{ item }">
                    <v-icon v-if="(item as Item).is_correct">mdi-check-circle</v-icon>
                    <v-icon v-else>mdi-circle-outline</v-icon>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <AnswerModal v-model="dialog" :answerItem="selected" :questions="props.questions" :attempts="props.attempts" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import AnswerModal from '@/components/Modal/Test/AnswerModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps<{
    answers: any[];
    questions: { id: number; name: string }[];
    attempts: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
    { title: 'Правильность', key: 'is_correct' },
    { title: 'Время ответа', key: 'time_spent' },
    { title: 'Вопрос', key: 'question' },
    { title: 'Попытка', key: 'attempt' },
]);

interface Item {
    id: number;
    name: string;
    is_correct: boolean;
    time_spent: number;
    question: {
        id: number;
        name: string;
    };
    attempt: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['answers'],
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
    if (confirm(`Удалить результат "${item.name}"?`)) {
        Inertia.delete(`/test/answer/${item.id}`, {
            onSuccess: () => {
                alert('Результат успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении результата.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
