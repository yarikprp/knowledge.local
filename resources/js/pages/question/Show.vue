<template>
    <Head title="Просмотр вопроса" />
    <AppLayout :breadcrumbs="[{ title: 'Просмотр вопроса', href: '/question' }]">
        <div class="min-h-screen bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="p-6">
                        <div class="mb-6 flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-gray-800">Просмотр вопроса</h1>
                            <div class="space-x-2">
                                <Link
                                    :href="route('question.index')"
                                    class="rounded bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                                >
                                    Назад
                                </Link>
                                <button @click="editQuestion" class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">
                                    Редактировать
                                </button>
                                <button @click="confirmDelete" class="rounded bg-red-600 px-4 py-2 text-white transition hover:bg-red-700">
                                    Удалить
                                </button>
                            </div>
                        </div>

                        <ConfirmationModal
                            v-model="showConfirm"
                            title="Удалить вопрос?"
                            message="Вы действительно хотите удалить этот вопрос? Это действие нельзя отменить."
                            @confirm="deleteQuestion"
                            @cancel="showConfirm = false"
                        />

                        <div v-if="isEditing" class="mb-8">
                            <h2 class="mb-4 text-xl font-semibold text-gray-800">Редактирование вопроса</h2>
                            <form @submit.prevent="updateQuestion">
                                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Название</label>
                                        <input v-model="editForm.name" type="text" class="w-full rounded border p-2" />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Тест</label>
                                        <select v-model="editForm.test_id" class="w-full rounded border p-2">
                                            <option v-for="test in tests" :key="test.id" :value="test.id">{{ test.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Тип вопроса</label>
                                        <select v-model="editForm.question_type_id" class="w-full rounded border p-2">
                                            <option v-for="type in questionTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Баллы</label>
                                        <input v-model="editForm.points" type="number" min="1" class="w-full rounded border p-2" />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Текст вопроса</label>
                                    <textarea v-model="editForm.question_text" class="min-h-[100px] w-full rounded border p-2"></textarea>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button
                                        @click="cancelEdit"
                                        type="button"
                                        class="rounded bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                                    >
                                        Отмена
                                    </button>
                                    <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">
                                        Сохранить
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div v-else class="mb-8">
                            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Название</label>
                                    <div class="rounded border border-gray-200 bg-gray-50 p-2">{{ question?.name }}</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Тест</label>
                                    <div class="rounded border border-gray-200 bg-gray-50 p-2">{{ question?.test?.name }}</div>
                                </div>
                            </div>

                            <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Тип вопроса</label>
                                    <div class="rounded border border-gray-200 bg-gray-50 p-2">{{ question?.question_type?.name }}</div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-gray-700">Баллы</label>
                                    <div class="rounded border border-gray-200 bg-gray-50 p-2">{{ question?.points || 'Не указано' }}</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="mb-1 block text-sm font-medium text-gray-700">Текст вопроса</label>
                                <div class="min-h-[100px] rounded border border-gray-200 bg-gray-50 p-2">{{ question?.question_text }}</div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-semibold text-gray-800">Варианты ответов</h2>
                                <button
                                    v-if="isEditing"
                                    @click="showOptionForm = true"
                                    class="rounded bg-green-600 px-4 py-2 text-white transition hover:bg-green-700"
                                >
                                    Добавить вариант
                                </button>
                            </div>

                            <div v-if="showOptionForm" class="mb-4 rounded border bg-gray-50 p-4">
                                <h3 class="mb-2 text-lg font-medium">
                                    {{ editingOption ? 'Редактировать вариант ответа' : 'Добавить вариант ответа' }}
                                </h3>
                                <form @submit.prevent="editingOption ? updateOption() : addOption()">
                                    <div class="mb-2">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Текст варианта</label>
                                        <input v-model="newOption.text" type="text" class="w-full rounded border p-2" required />
                                    </div>
                                    <div class="mb-2 flex items-center">
                                        <input v-model="newOption.is_correct" type="checkbox" id="is_correct" class="mr-2" />
                                        <label for="is_correct" class="text-sm font-medium text-gray-700">Правильный ответ</label>
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button
                                            @click="cancelOptionEdit"
                                            type="button"
                                            class="rounded bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                                        >
                                            Отмена
                                        </button>
                                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">
                                            {{ editingOption ? 'Обновить' : 'Добавить' }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div v-if="question?.options?.length > 0" class="space-y-2">
                                <div
                                    v-for="(option, index) in question.options"
                                    :key="option.id"
                                    class="flex items-center justify-between rounded border p-3"
                                    :class="{
                                        'border-green-200 bg-green-50': isCorrectAnswer(option),
                                        'bg-gray-50': !isCorrectAnswer(option),
                                    }"
                                >
                                    <div class="flex items-center">
                                        <span class="mr-3 font-medium">{{ index + 1 }}.</span>
                                        <span>{{ option.text }}</span>
                                    </div>
                                    <div v-if="isEditing" class="flex space-x-2">
                                        <v-tooltip left>
                                            <template v-slot:activator="{ props }">
                                                <button v-bind="props" @click="editOption(option)" class="text-blue-600 hover:text-blue-800">
                                                    <v-icon>mdi-pencil</v-icon>
                                                </button>
                                            </template>
                                            <span>Редактировать</span>
                                        </v-tooltip>
                                        <v-tooltip left>
                                            <template v-slot:activator="{ props }">
                                                <button v-bind="props" @click="confirmOptionDelete(option)" class="text-red-600 hover:text-red-800">
                                                    <v-icon>mdi-delete</v-icon>
                                                </button>
                                            </template>
                                            <span>Удалить</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 italic">Нет вариантов ответа</div>
                        </div>

                        <div class="mb-8">
                            <div class="mb-4 flex items-center justify-between">
                                <h2 class="text-xl font-semibold text-gray-800">Правильные ответы</h2>
                                <button
                                    v-if="isEditing"
                                    @click="showCorrectAnswerForm = true"
                                    class="rounded bg-green-600 px-4 py-2 text-white transition hover:bg-green-700"
                                >
                                    Добавить правильный ответ
                                </button>
                            </div>

                            <div v-if="showCorrectAnswerForm" class="mb-4 rounded border bg-gray-50 p-4">
                                <h3 class="mb-2 text-lg font-medium">
                                    {{ editingCorrectAnswer ? 'Редактировать правильный ответ' : 'Добавить правильный ответ' }}
                                </h3>
                                <form @submit.prevent="editingCorrectAnswer ? updateCorrectAnswer() : addCorrectAnswer()">
                                    <div class="mb-2">
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Текст ответа</label>
                                        <input v-model="newCorrectAnswer.text" type="text" class="w-full rounded border p-2" required />
                                    </div>
                                    <div class="flex justify-end space-x-2">
                                        <button
                                            @click="cancelCorrectAnswerEdit"
                                            type="button"
                                            class="rounded bg-gray-200 px-4 py-2 text-gray-700 transition hover:bg-gray-300"
                                        >
                                            Отмена
                                        </button>
                                        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700">
                                            {{ editingCorrectAnswer ? 'Обновить' : 'Добавить' }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div v-if="question?.correct_answers?.length > 0" class="space-y-2">
                                <div
                                    v-for="(answer, index) in question.correct_answers"
                                    :key="answer.id"
                                    class="flex items-center justify-between rounded border border-green-200 bg-green-50 p-3"
                                >
                                    <div>
                                        <span class="mr-3 font-medium">{{ index + 1 }}.</span>
                                        <span>{{ answer.text }}</span>
                                    </div>
                                    <div v-if="isEditing" class="flex space-x-2">
                                        <v-tooltip left>
                                            <template v-slot:activator="{ props }">
                                                <button v-bind="props" @click="editCorrectAnswer(answer)" class="text-blue-600 hover:text-blue-800">
                                                    <v-icon>mdi-pencil</v-icon>
                                                </button>
                                            </template>
                                            <span>Редактировать</span>
                                        </v-tooltip>
                                        <v-tooltip left>
                                            <template v-slot:activator="{ props }">
                                                <button
                                                    v-bind="props"
                                                    @click="confirmCorrectAnswerDelete(answer)"
                                                    class="text-red-600 hover:text-red-800"
                                                >
                                                    <v-icon>mdi-delete</v-icon>
                                                </button>
                                            </template>
                                            <span>Удалить</span>
                                        </v-tooltip>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 italic">Нет правильных ответов</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ConfirmationModal from '@/components/Modal/Question/ConfirmationModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';

interface Option {
    id: number;
    text: string;
}

interface CorrectAnswer {
    id: number;
    text: string;
}

interface Test {
    id: number;
    name: string;
}

interface QuestionType {
    id: number;
    name: string;
}

interface Question {
    id: number;
    name: string;
    test: Test;
    question_type: QuestionType;
    question_text: string;
    points?: number;
    options: Option[];
    correct_answers: CorrectAnswer[];
}

const props = defineProps({
    question: {
        type: Object as PropType<Question>,
        required: true,
    },
    tests: {
        type: Array as PropType<Test[]>,
        required: true,
    },
    questionTypes: {
        type: Array as PropType<QuestionType[]>,
        required: true,
    },
});

const isEditing = ref(false);
const showDeleteModal = ref(false);
const showOptionForm = ref(false);
const showCorrectAnswerForm = ref(false);
const editingOption = ref<Option | null>(null);
const editingCorrectAnswer = ref<CorrectAnswer | null>(null);
const showConfirm = ref(false);
const deleteInProgress = ref(false);

const editForm = ref({
    name: props.question.name,
    test_id: props.question.test.id,
    question_type_id: props.question.question_type.id,
    question_text: props.question.question_text,
    points: props.question.points,
});

const newOption = ref({
    text: '',
    is_correct: false,
});

const newCorrectAnswer = ref({
    text: '',
});

const isCorrectAnswer = (option: Option): boolean => {
    return props.question.correct_answers.some((answer: CorrectAnswer) => answer.text === option.text);
};

const editQuestion = () => {
    isEditing.value = true;
};

const cancelOptionEdit = () => {
    editingOption.value = null;
    newOption.value = { text: '', is_correct: false };
    showOptionForm.value = false;
};

const cancelEdit = () => {
    isEditing.value = false;
    editForm.value = {
        name: props.question.name,
        test_id: props.question.test.id,
        question_type_id: props.question.question_type.id,
        question_text: props.question.question_text,
        points: props.question.points,
    };
};

const updateQuestion = () => {
    router.put(route('question.update', props.question.id), editForm.value, {
        onSuccess: () => {
            isEditing.value = false;
        },
    });
};

const confirmDelete = () => {
    showDeleteModal.value = true;
};

const deleteQuestion = async () => {
    if (deleteInProgress.value) return;

    deleteInProgress.value = true;
    try {
        await router.delete(route('question.destroy', props.question.id), {
            onSuccess: () => {
                router.visit(route('question.index'));
            },
            onError: () => {
                deleteInProgress.value = false;
            },
        });
    } catch (error) {
        deleteInProgress.value = false;
        console.error('Error deleting question:', error);
    }
};

const addOption = () => {
    router.post(
        route('options.store'),
        {
            question_id: props.question.id,
            text: newOption.value.text,
            is_correct: newOption.value.is_correct,
        },
        {
            onSuccess: () => {
                showOptionForm.value = false;
                newOption.value = { text: '', is_correct: false };
            },
        },
    );
};

const editOption = (option: Option) => {
    editingOption.value = option;
    newOption.value = {
        text: option.text,
        is_correct: isCorrectAnswer(option),
    };
    showOptionForm.value = true;
};

const updateOption = () => {
    if (!editingOption.value) return;

    router.put(
        route('options.update', editingOption.value.id),
        {
            text: newOption.value.text,
            is_correct: newOption.value.is_correct,
            question_id: props.question.id,
        },
        {
            onSuccess: () => {
                cancelOptionEdit();
            },
        },
    );
};

const confirmOptionDelete = (option: Option) => {
    if (confirm('Вы уверены, что хотите удалить этот вариант ответа?')) {
        router.delete(route('options.destroy', option.id));
    }
};

const editCorrectAnswer = (answer: CorrectAnswer) => {
    editingCorrectAnswer.value = answer;
    newCorrectAnswer.value = { text: answer.text };
    showCorrectAnswerForm.value = true;
};

const cancelCorrectAnswerEdit = () => {
    editingCorrectAnswer.value = null;
    newCorrectAnswer.value = { text: '' };
    showCorrectAnswerForm.value = false;
};

const addCorrectAnswer = () => {
    router.post(
        route('correctAnswers.store'),
        {
            question_id: props.question.id,
            text: newCorrectAnswer.value.text,
        },
        {
            onSuccess: () => {
                showCorrectAnswerForm.value = false;
                newCorrectAnswer.value = { text: '' };
            },
        },
    );
};

const updateCorrectAnswer = () => {
    if (!editingCorrectAnswer.value) return;

    router.put(
        route('correctAnswers.update', editingCorrectAnswer.value.id),
        {
            text: newCorrectAnswer.value.text,
            question_id: props.question.id,
        },
        {
            onSuccess: () => {
                cancelCorrectAnswerEdit();
            },
        },
    );
};

const confirmCorrectAnswerDelete = (answer: CorrectAnswer) => {
    if (confirm('Вы уверены, что хотите удалить этот правильный ответ?')) {
        router.delete(route('correctAnswers.destroy', answer.id));
    }
};
</script>
