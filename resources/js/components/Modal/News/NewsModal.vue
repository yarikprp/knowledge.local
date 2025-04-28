<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch, computed } from 'vue';

interface NewsItem {
    id: number;
    name: string;
    content: string;
    user: { name: string };
    type: { name: string };
}

interface NewsForm {
    id?: number;
    name: string;
    content: string;
    user_id: number | null;
    news_type_id: number | null;
}

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    newsItem: {
        type: [Object, null],
        required: false,
        default: null,
    },
    users: {
        type: Array as PropType<{ id: number; name: string }[]>,
        required: true,
    },
    newsTypes: {
        type: Array as PropType<{ id: number; name: string }[]>,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);

const form = useForm<NewsForm>({
    name: '',
    content: '',
    user_id: null,
    news_type_id: null,
});

const dialog = ref(props.modelValue);

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.newsItem) {
            form.name = props.newsItem.name;
            form.content = props.newsItem.content;
            form.user_id = props.newsItem.user_id;
            form.news_type_id = props.newsItem.news_type_id;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof NewsForm, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Заголовок обязателен';
    } else if (form.name.length > 255) {
        errors.name = 'Заголовок не должен превышать 255 символов';
    }

    if (!form.content || form.content.trim() === '') {
        errors.content = 'Содержание обязательно';
    }

    if (!form.user_id) {
        errors.user_id = 'Автор обязателен';
    }

    if (!form.news_type_id) {
        errors.news_type_id = 'Тип новости обязателен';
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

    if (props.newsItem?.id) {
        form.put(route('news.update', props.newsItem.id), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            }
        });
    } else {
        form.post(route('news.store'), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            }
        });
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ newsItem ? 'Редактировать' : 'Добавить' }}
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
                    label="Содержание"
                    :error-messages="form.errors.content"
                    required
                />

                <v-select
                    v-model="form.user_id"
                    :loading="isLoading"
                    :disabled="isLoading"
                    :items="props.users"
                    item-title="name"
                    item-value="id"
                    label="Автор"
                    clearable
                />
                <v-select
                    v-model="form.news_type_id"
                    :loading="isLoading"
                    :disabled="isLoading"
                    :items="props.newsTypes"
                    item-title="name"
                    item-value="id"
                    label="Тип новости"
                    clearable
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
