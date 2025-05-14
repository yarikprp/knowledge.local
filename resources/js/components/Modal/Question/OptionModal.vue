<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch } from 'vue';

interface OptionProps {
    id: number;
    text: string;
    question_id: number;
    is_correct: boolean;
}

interface OptionForm {
    text: string;
    question_id: number | null;
    is_correct: boolean;
}

const props = defineProps<{
    modelValue: boolean;
    OptionItem?: OptionProps | null;
    questions: { id: number; name: string }[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);
const dialog = ref(props.modelValue);

const form = useForm<OptionForm>({
    text: '',
    question_id: null,
    is_correct: false,
});

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.OptionItem) {
            form.text = props.OptionItem.text;
            form.question_id = props.OptionItem.question_id;
            form.is_correct = props.OptionItem.is_correct;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => emit('update:modelValue', value));

const submit = () => {
    isLoading.value = true;
    if (props.OptionItem?.id) {
        form.put(`/question/option/${props.OptionItem.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    } else {
        form.post('/question/option', {
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
            <v-card-title>{{ OptionItem ? 'Редактировать' : 'Добавить' }}</v-card-title>
            <v-card-text>
                <v-text-field v-model="form.text" label="Заголовок" :error-messages="form.errors.text" required />
                <v-select
                    v-model="form.question_id"
                    :items="props.questions"
                    item-title="name"
                    item-value="id"
                    label="Вопрос"
                    :error-messages="form.errors.question_id"
                    required
                />

                <v-checkbox v-model="form.is_correct" label="Правильный вариант" :error-messages="form.errors.is_correct" />
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn color="grey" text @click="dialog = false">Отмена</v-btn>
                <v-btn color="primary" :loading="isLoading && form.processing" @click="submit">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
