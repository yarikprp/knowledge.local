<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ props.answerItem ? 'Редактировать ответ' : 'Добавить ответ' }}
            </v-card-title>

            <v-card-text>
                <v-text-field v-model="form.name" label="Ответ" :error-messages="form.errors.name" :disabled="isLoading" />

                <v-checkbox v-model="form.is_correct" label="Правильный ответ" :disabled="isLoading" />

                <v-text-field
                    v-model="form.time_spent"
                    label="Время ответа (сек)"
                    type="number"
                    :error-messages="form.errors.time_spent"
                    :disabled="isLoading"
                />

                <v-select
                    v-model="form.question_id"
                    :items="questions"
                    item-title="name"
                    item-value="id"
                    label="Вопрос"
                    :error-messages="form.errors.question_id"
                    :disabled="isLoading"
                    required
                />

                <v-select
                    v-model="form.attempt_id"
                    :items="attempts"
                    item-title="id"
                    item-value="id"
                    label="Попытка"
                    :error-messages="form.errors.attempt_id"
                    :disabled="isLoading"
                    required
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn text color="grey" @click="dialog = false" :disabled="isLoading">Отмена</v-btn>
                <v-btn color="primary" @click="submit" :loading="isLoading">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    answerItem: any | null;
    questions: { id: number; name: string }[];
    attempts: { id: number; name: string }[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);

const dialog = ref(props.modelValue);
watch(
    () => props.modelValue,
    (val) => (dialog.value = val),
);
watch(dialog, (val) => emit('update:modelValue', val));

const isLoading = ref(false);

const form = useForm({
    name: '',
    is_correct: false,
    time_spent: 0,
    question_id: null,
    attempt_id: null,
});

watch(
    () => props.answerItem,
    (item) => {
        if (item) {
            form.name = item.name;
            form.is_correct = item.is_correct;
            form.time_spent = item.time_spent;
            form.question_id = item.question.id;
            form.attempt_id = item.attempt.id;
        } else {
            form.reset();
        }
    },
    { immediate: true },
);

const validateForm = () => {
    form.clearErrors();
    const errors: Record<string, string> = {};

    if (!form.name) errors.name = 'Поле обязательно';
    if (!form.question_id) errors.question_id = 'Выберите вопрос';
    if (!form.attempt_id) errors.attempt_id = 'Выберите попытку';

    form.setError(errors);
    return Object.keys(errors).length === 0;
};

const submit = () => {
    if (!validateForm()) return;

    isLoading.value = true;
    if (props.answerItem?.id) {
        form.put(`/test/answer/${props.answerItem.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    } else {
        form.post('/test/answer', {
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
