<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch } from 'vue';

interface AnswerItemProps {
    id: number;
    text: string;
    question_id: number;
}

interface AnswerForm {
    text: string;
    question_id: number | null;
}

const props = defineProps<{
    modelValue: boolean;
    AnswerItem?: AnswerItemProps | null;
    questions: { id: number; name: string }[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);
const dialog = ref(props.modelValue);

const form = useForm<AnswerForm>({
    text: '',
    question_id: null,
});

watch(() => props.modelValue, (value) => {
    dialog.value = value;
    if (value && props.AnswerItem) {
        form.text = props.AnswerItem.text;
        form.question_id = props.AnswerItem.question_id;
    } else {
        form.reset();
    }
});

watch(dialog, (value) => emit('update:modelValue', value));

const submit = () => {
    isLoading.value = true;
    if (props.AnswerItem?.id) {
        form.put(`/question/correct-answer/${props.AnswerItem.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => (isLoading.value = false),
        });
    } else {
        form.post('/question/correct-answer', {
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
            <v-card-title>{{ AnswerItem ? 'Редактировать' : 'Добавить' }}</v-card-title>
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
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn color="grey" text @click="dialog = false">Отмена</v-btn>
                <v-btn color="primary" :loading="isLoading && form.processing" @click="submit">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
