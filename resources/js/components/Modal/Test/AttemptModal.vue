<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ props.attemptItem ? 'Редактировать попытку' : 'Добавить попытку' }}
            </v-card-title>

            <v-card-text>
                <v-select
                    v-model="form.user_id"
                    :items="users"
                    item-title="name"
                    item-value="id"
                    label="Пользователь"
                    :error-messages="form.errors.user_id"
                    :disabled="isLoading"
                    required
                />

                <v-select
                    v-model="form.test_id"
                    :items="tests"
                    item-title="name"
                    item-value="id"
                    label="Тест"
                    :error-messages="form.errors.test_id"
                    :disabled="isLoading"
                    required
                />

                <v-text-field
                    v-model="form.total_questions"
                    label="Количество вопросов"
                    type="number"
                    :error-messages="form.errors.total_questions"
                    :disabled="isLoading"
                />

                <v-menu
                    v-model="menuStartDate"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                >
                    <template #activator="{ props: menuProps }">
                        <v-text-field
                            v-model="formattedStartedAt"
                            v-bind="menuProps"
                            label="Дата начала"
                            readonly
                            :error-messages="form.errors.started_at"
                            :loading="isLoading"
                            :disabled="isLoading"
                        />
                    </template>
                    <v-date-picker v-model="form.started_at" @update:model-value="menuStartDate = false" color="primary" elevation="0" />
                </v-menu>

                <v-menu
                    v-model="menuFinishDate"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="290px"
                >
                    <template #activator="{ props: menuProps }">
                        <v-text-field
                            v-model="formattedFinishAt"
                            v-bind="menuProps"
                            label="Дата завершения"
                            readonly
                            :error-messages="form.errors.finish_at"
                            :loading="isLoading"
                            :disabled="isLoading"
                        />
                    </template>
                    <v-date-picker v-model="form.finish_at" @update:model-value="menuFinishDate = false" color="primary" elevation="0" />
                </v-menu>

                <v-text-field
                    v-model="form.passing_score"
                    label="Проходной балл"
                    type="number"
                    :error-messages="form.errors.passing_score"
                    :disabled="isLoading"
                />

                <v-text-field v-model="form.score" label="Счет" type="number" :error-messages="form.errors.score" :disabled="isLoading" />

                <v-select
                    v-model="form.status_id"
                    :items="statusList"
                    item-title="name"
                    item-value="id"
                    label="Статус"
                    :error-messages="form.errors.status_id"
                    :disabled="isLoading"
                />

                <v-checkbox v-model="form.is_passed" label="Пройдено" :disabled="isLoading" />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn text color="grey" @click="dialog = false" :disabled="isLoading"> Отмена </v-btn>
                <v-btn color="primary" @click="submit" :loading="isLoading"> Сохранить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    attemptItem: any | null;
    users: { id: number; name: string }[];
    tests: { id: number; name: string }[];
    statusList: { id: number; name: string }[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);

const dialog = ref(props.modelValue);
watch(
    () => props.modelValue,
    (val) => (dialog.value = val),
);
watch(dialog, (val) => emit('update:modelValue', val));

function formatDate(date: string | Date | null): string {
    if (!date) return '';
    const d = new Date(date);
    if (isNaN(d.getTime())) return '';
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}.${month}.${year}`;
}

function parseDate(input: string): string {
    const [day, month, year] = input.split('.');
    if (!day || !month || !year) return '';
    return `${year}-${month}-${day}`;
}

const isLoading = ref(false);
const menuStartDate = ref(false);
const menuFinishDate = ref(false);

const formattedStartedAt = computed({
    get() {
        return formatDate(form.started_at);
    },
    set(value: string) {
        form.started_at = parseDate(value);
    },
});

const formattedFinishAt = computed({
    get() {
        return formatDate(form.finish_at);
    },
    set(value: string) {
        form.finish_at = parseDate(value);
    },
});

const form = useForm({
    user_id: null,
    test_id: null,
    total_questions: 0,
    passing_score: 0,
    started_at: '',
    finish_at: '',
    score: 0,
    is_passed: false,
    status_id: null,
});

watch(
    () => props.attemptItem,
    (item) => {
        if (item) {
            form.user_id = item.user.id;
            form.test_id = item.test.id;
            form.total_questions = item.total_questions;
            form.passing_score = item.passing_score;
            form.started_at = item.started_at;
            form.finish_at = item.finish_at;
            form.score = item.score;
            form.is_passed = item.is_passed;
            form.status_id = item.status?.id ?? null;
        } else {
            form.reset();
        }
    },
    { immediate: true },
);

const validateForm = (): boolean => {
    form.clearErrors();
    const errors: Record<string, string> = {};

    if (!form.user_id) errors.user_id = 'Пользователь обязателен';
    if (!form.test_id) errors.test_id = 'Тест обязателен';
    if (!form.total_questions || form.total_questions <= 0) errors.total_questions = 'Количество вопросов должно быть больше 0';
    if (!form.passing_score || form.passing_score <= 0) errors.passing_score = 'Проходной балл должен быть больше 0';
    if (!form.started_at) errors.started_at = 'Дата начала обязательна';
    if (!form.finish_at) errors.finish_at = 'Дата завершения обязательна';
    if (form.score < 0) errors.score = 'Счёт не может быть отрицательным';

    form.setError(errors);

    return Object.keys(errors).length === 0;
};

const submit = () => {
    if (!validateForm()) return;
    isLoading.value = true;

    if (props.attemptItem?.id) {
        form.put(`/test/attempt/${props.attemptItem.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    } else {
        form.post('/test/attempt', {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    }
};
</script>
