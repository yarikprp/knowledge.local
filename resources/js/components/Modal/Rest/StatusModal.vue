<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface StatusItemProps {
    id: number;
    name: string;
}

interface StatusForm {
    id?: number;
    name: string;
}

const props = defineProps<{
    modelValue: boolean;
    StatusFormItem?: StatusItemProps | null;
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);

const form = useForm<StatusForm>({
    name: '',
});

const dialog = ref(props.modelValue);

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.StatusFormItem) {
            form.name = props.StatusFormItem.name;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof StatusForm, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Заголовок обязателен';
    } else if (form.name.length > 255) {
        errors.name = 'Заголовок не должен превышать 255 символов';
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

    if (props.StatusFormItem?.id) {
        form.put(route('status.update', props.StatusFormItem.id), {
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
        form.post(route('status.store'), {
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
                {{ StatusFormItem ? 'Редактировать' : 'Добавить' }}
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
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn :loading="isLoading" :disabled="isLoading" color="grey" text @click="dialog = false"> Отмена </v-btn>
                <v-btn :loading="isLoading && form.processing" :disabled="isLoading" color="primary" @click="submit"> Сохранить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
