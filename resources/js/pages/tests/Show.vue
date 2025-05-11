<template>
    <Head title="Аттестация" />
    <AppLayout :breadcrumbs="[{ title: 'Аттестация', href: '/tests' }]">
        <v-container>
            <template v-if="isLoading">
                <v-card class="mb-4" elevation="2">
                    <v-skeleton-loader type="heading, subtitle, paragraph" />
                </v-card>
                <v-card>
                    <v-skeleton-loader type="list-item-two-line" :loading="true" />
                </v-card>
            </template>

            <template v-else-if="test">
                <v-card class="mb-4" elevation="2">
                    <ToolbarNavigation
                        :title="test.name"
                        :is-parent-loading="isLoading"
                        :has-refresh="true"
                        :actions-label="'Управление'"
                        @edit="openEditModal"
                        @delete="deleteTest"
                        @refresh="refreshTest"
                    />

                    <v-card-subtitle class="text-grey">
                        {{ test.test_type?.name }} | Даты: {{ formatDate(test.start_date) }} – {{ formatDate(test.end_date) }}
                    </v-card-subtitle>

                    <v-card-text>
                        <div v-if="test.description" v-text="test.description" />
                        <div><strong>Ограничение по попыткам:</strong> {{ test.attempt_limit }}</div>
                        <div><strong>Лимит времени:</strong> {{ test.time_limit ?? 'Без ограничения' }}</div>
                        <div><strong>Статус:</strong> {{ test.is_active ? 'Активен' : 'Неактивен' }}</div>
                    </v-card-text>

                    <v-card-actions>
                        <v-btn color="primary" :loading="isLoading" :disabled="isLoading" @click="goBack">
                            <v-icon start>mdi-arrow-left</v-icon>
                            Назад
                        </v-btn>
                    </v-card-actions>
                </v-card>

                <v-card>
                    <v-toolbar flat density="comfortable">
                        <v-toolbar-title>Вопросы</v-toolbar-title>
                        <v-spacer />
                        <v-text-field v-model="searchQuery" label="Поиск вопросов" append-icon="mdi-magnify" dense hide-details class="mr-2" />
                        <v-btn color="secondary" :loading="isLoading" :disabled="isLoading" @click="goToQuestions"> Перейти к вопросам </v-btn>
                    </v-toolbar>
                    <v-divider />
                    <v-card-text>
                        <v-list v-if="filteredQuestions.length">
                            <v-list-item v-for="question in filteredQuestions" :key="question.id" class="mb-2">
                                <v-list-item>
                                    <v-list-item-title>{{ question.name }}</v-list-item-title>
                                    <v-list-item-subtitle>
                                        {{ question.question_type?.name }} | Баллы: {{ question.points ?? '—' }}
                                    </v-list-item-subtitle>
                                    <div class="mt-2" v-text="question.question_text" />
                                </v-list-item>
                                <v-list-item-action>
                                    <v-tooltip left>
                                        <template v-slot:activator="{ props }">
                                            <v-btn v-bind="props" icon @click="deleteItem(question)">
                                                <v-icon>mdi-delete</v-icon>
                                            </v-btn>
                                        </template>
                                        <span>Удалить</span>
                                    </v-tooltip>
                                </v-list-item-action>
                            </v-list-item>
                        </v-list>
                        <div v-else class="text-grey">Нет добавленных вопросов.</div>
                    </v-card-text>
                </v-card>
            </template>

            <template v-else>
                <v-alert type="info" class="ma-4">Тест загружается или не найден...</v-alert>
            </template>
        </v-container>
    </AppLayout>

    <TestModal v-model="dialog" :testItem="selected" :testTypes="props.testTypes" @saved="refreshTest" />
</template>

<script setup lang="ts">
import ToolbarNavigation from '@/components/General/ToolbarNavigation.vue';
import TestModal from '@/components/Modal/Test/TestModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Question {
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

interface Test {
    id: number;
    name: string;
    description?: string;
    start_date: string;
    end_date: string;
    test_type: { name: string };
    attempt_limit: number;
    time_limit?: number;
    is_active: boolean;
    questions: Question[];
}

const props = defineProps<{
    testTypes: { id: number; name: string }[];
    questionTypes: { id: number; name: string }[];
}>();

const test = ref<Test | null>((usePage().props.test as Test | null) ?? null);
const isLoading = ref(false);
const dialog = ref(false);
const selected = ref<Test | null>(null);

const searchQuery = ref('');

const filteredQuestions = computed(() => {
    if (!test.value?.questions) return [];
    return test.value.questions.filter(
        (question) =>
            question.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            question.question_text.toLowerCase().includes(searchQuery.value.toLowerCase()),
    );
});
const openEditModal = () => {
    if (test.value) {
        selected.value = { ...test.value };
        dialog.value = true;
    }
};

const deleteTest = () => {
    if (!test.value) return;

    if (confirm('Вы уверены, что хотите удалить этот тест?')) {
        router.delete(`/test/${test.value.id}`, {
            onStart: () => (isLoading.value = true),
            onFinish: () => (isLoading.value = false),
            onSuccess: () => {
                router.visit('/test');
            },
        });
    }
};

const refreshTest = () => {
    if (!test.value) return;

    isLoading.value = true;
    router.reload({
        only: ['test'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const formatDate = (date: string) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['questions'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const deleteItem = (item: Question) => {
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

const goToQuestions = () => {
    if (test.value) {
        router.visit(`/question`);
    }
};

const goBack = () => {
    window.history.back();
};
</script>
