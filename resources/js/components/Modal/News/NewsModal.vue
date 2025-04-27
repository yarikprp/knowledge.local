<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { defineEmits, defineProps, ref, watch } from 'vue';

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

const submit = () => {
    if (props.newsItem?.id) {
        form.put(route('news.update', props.newsItem.id), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
            },
        });
    } else {
        form.post(route('news.store'), {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
            },
        });
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ newsItem ? 'Редактировать новость' : 'Добавить новость' }}
            </v-card-title>

            <v-card-text>
                <v-text-field v-model="form.name" label="Заголовок" :error-messages="form.errors.name" required />
                <v-textarea v-model="form.content" label="Содержание" :error-messages="form.errors.content" required />

                <v-select v-model="form.user_id" :items="props.users" item-title="name" item-value="id" label="Автор" clearable />
                <v-select v-model="form.news_type_id" :items="props.newsTypes" item-title="name" item-value="id" label="Тип новости" clearable />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn color="grey" text @click="dialog = false"> Отмена </v-btn>
                <v-btn color="primary" @click="submit" :loading="form.processing"> Сохранить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
