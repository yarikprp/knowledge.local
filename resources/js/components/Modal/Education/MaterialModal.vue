<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface MaterialItemProps {
    id: number;
    name: string;
    content: string;
    subjects: { name: string };
}

interface MaterialForm {
    id?: number;
    name: string;
    content: string;
    subject_id: number | null;
}

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    MaterialItem: {
        type: [Object, null],
        required: false,
        default: null,
    },
    subjects: {
        type: Array as PropType<{ id: number; name: string }[]>,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);
const subjects = computed(() => props.subjects);

const form = useForm<MaterialForm>({
    name: '',
    content: '',
    subject_id: null,
});

const dialog = ref(props.modelValue);

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.MaterialItem) {
            form.name = props.MaterialItem.name;
            form.content = props.MaterialItem.content;
            form.subject_id = props.MaterialItem.subject_id;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof MaterialForm, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Заголовок обязателен';
    } else if (form.name.length > 255) {
        errors.name = 'Заголовок не должен превышать 255 символов';
    }

    if (!form.content || form.content.trim() === '') {
        errors.content = 'Контент обязателен';
    }

    if (!form.subject_id) {
        errors.subject_id = 'Предмет обязателен';
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

    if (props.MaterialItem?.id) {
        form.put(route('material.update', props.MaterialItem.id), {
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
        form.post(route('material.store'), {
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
                {{ MaterialItem ? 'Редактировать' : 'Добавить' }} материал
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

                <v-textarea
                    v-model="form.content"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Контент"
                    :error-messages="form.errors.content"
                    required
                />

                <v-select
                    v-model="form.subject_id"
                    :items="subjects"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Предмет"
                    item-value="id"
                    item-title="name"
                    :error-messages="form.errors.subject_id"
                    required
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn color="grey" text @click="dialog = false" :disabled="isLoading">Отмена</v-btn>
                <v-btn color="primary" @click="submit" :loading="isLoading && form.processing" :disabled="isLoading">Сохранить</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
