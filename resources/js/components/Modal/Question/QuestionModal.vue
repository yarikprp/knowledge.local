<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch } from 'vue';

interface QuestionItemProps {
    id: number;
    name: string;
    test_id: number;
    test: { id: number; name: string };
    question_type_id: number;
    question_type: { id: number; name: string };
    question_text: string;
    points: number;
    order: number;
}

interface QuestionForm {
    name: string;
    test_id: number | null;
    question_type_id: number | null;
    question_text: string;
    points: number;
    order: number;
}

const props = defineProps<{
    modelValue: boolean;
    QuestionItem?: QuestionItemProps | null;
    tests: { id: number; name: string }[];
    questionTypes: { id: number; name: string }[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);
const dialog = ref(props.modelValue);

const form = useForm<QuestionForm>({
    name: '',
    test_id: null,
    question_type_id: null,
    question_text: '',
    points: 1,
    order: 0,
});

watch(() => props.modelValue, (value) => {
    dialog.value = value;
    if (value && props.QuestionItem) {
        form.name = props.QuestionItem.name;
        form.test_id = props.QuestionItem.test_id;
        form.question_type_id = props.QuestionItem.question_type_id;
        form.question_text = props.QuestionItem.question_text;
        form.points = props.QuestionItem.points;
        form.order = props.QuestionItem.order;
    } else {
        form.reset();
    }
});

watch(dialog, (value) => emit('update:modelValue', value));

const submit = () => {
    isLoading.value = true;
    if (props.QuestionItem?.id) {
        form.put(`/question/${props.QuestionItem.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    } else {
        form.post('/question', {
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

<template>
    <v-dialog v-model="dialog" max-width="800px" persistent>
        <v-card>
            <v-card-title>{{ QuestionItem ? 'Редактировать' : 'Добавить' }}</v-card-title>
            <v-card-text>
                <v-text-field v-model="form.name" label="Заголовок" :error-messages="form.errors.name" required />
                <v-select
                    v-model="form.test_id"
                    :items="props.tests"
                    item-title="name"
                    item-value="id"
                    label="Тест"
                    :error-messages="form.errors.test_id"
                    required
                />
                <v-select
                    v-model="form.question_type_id"
                    :items="props.questionTypes"
                    item-title="name"
                    item-value="id"
                    label="Тип вопроса"
                    :error-messages="form.errors.question_type_id"
                    required
                />
                <v-textarea v-model="form.question_text" label="Текст вопроса" :error-messages="form.errors.question_text" required />
                <v-text-field v-model="form.points" label="Баллы" type="number" :error-messages="form.errors.points" />
                <v-text-field v-model="form.order" label="Порядок" type="number" :error-messages="form.errors.order" />
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn color="grey" text @click="dialog = false">Отмена</v-btn>
                <v-btn color="primary" :loading="isLoading && form.processing" @click="submit">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
