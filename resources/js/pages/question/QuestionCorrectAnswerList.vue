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

            <v-expansion-panels v-if="groupedAnswers.size > 0">
                <v-expansion-panel v-for="[questionId, questionData] in groupedAnswers" :key="questionId">
                    <v-expansion-panel-title>
                        {{ questionData.question.name }} ({{ questionData.answers.length }} ответов)
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <VDataTable
                            :headers="headers"
                            :items="questionData.answers"
                            :search="search"
                            item-value="id"
                            no-data-text="Ответов нет"
                            :loading="isLoading"
                        >
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
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>

            <div v-else class="text-center py-4">
                <p>Ответов нет</p>
            </div>

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
import { defineProps, ref, computed } from 'vue';

interface CorrectAnswer {
    id: number;
    text: string;
    question_id: number;
    question: {
        id: number;
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
    { title: 'Ответ', key: 'text' },
]);

const groupedAnswers = computed(() => {
    const groups = new Map<number, { question: { id: number; name: string }, answers: CorrectAnswer[] }>();

    props.questions.forEach(question => {
        groups.set(question.id, {
            question: { id: question.id, name: question.name },
            answers: []
        });
    });

    props.correctAnswers.forEach(answer => {
        if (groups.has(answer.question_id)) {
            groups.get(answer.question_id)!.answers.push(answer);
        } else {
            groups.set(answer.question_id, {
                question: answer.question,
                answers: [answer]
            });
        }
    });

    return groups;
});

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
    if (confirm(`Удалить ответ "${item.text}"?`)) {
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
