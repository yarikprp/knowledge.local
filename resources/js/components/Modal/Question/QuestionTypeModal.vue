<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface QuestionTypeItemProps {
    id: number;
    name: string;
    slug: string;
}

interface QuestionTypeForm {
    id?: number;
    name: string;
    slug: string;
}

const props = defineProps<{
    modelValue: boolean;
    QuestionTypeItem?: QuestionTypeItemProps | null;
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);

const form = useForm<QuestionTypeForm>({
    name: '',
    slug: '',
});

const dialog = ref(props.modelValue);

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.QuestionTypeItem) {
            form.name = props.QuestionTypeItem.name;
            form.slug = props.QuestionTypeItem.slug;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof QuestionTypeForm, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Заголовок обязателен';
    } else if (form.name.length > 255) {
        errors.name = 'Заголовок не должен превышать 255 символов';
    }

    if (!form.slug || form.slug.trim() === '') {
        errors.slug = 'Слаг обязателен';
    } else if (form.slug.length > 255) {
        errors.slug = 'Слаг не должен превышать 50 символов';
    }
    return errors;
});

const hasErrors = computed(() => Object.keys(validationErrors.value).length > 0);

const submit = () => {
    if (hasErrors.value) {
        form.errors = validationErrors.value as any;
        return;
    }

    isLoading.value = true;

    if (props.QuestionTypeItem?.id) {
        form.put(route('types.update', props.QuestionTypeItem.id), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        });
    } else {
        form.post(route('types.store'), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        });
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ QuestionTypeItem ? 'Редактировать' : 'Добавить' }}
            </v-card-title>

            <v-card-text>
                <v-text-field
                    v-model="form.name"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Заголовок"
                    :error-messages="form.errors.name"
                    required
                />
                <v-text-field
                    v-model="form.slug"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Слаг"
                    :error-messages="form.errors.slug"
                    required
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn :loading="isLoading" :disabled="isLoading" color="grey" text @click="dialog = false"> Отмена </v-btn>
                <v-btn :loading="isLoading && form.processing" :disabled="isLoading" color="primary" @click="submit"> Сохранить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
