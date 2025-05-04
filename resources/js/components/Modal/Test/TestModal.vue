<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface TestProps {
    id?: number;
    name: string;
    slug: string;
    description: string;
    start_date: string;
    end_date: string;
    test_types_id: number;
    attempt_limit: number;
    time_limit: number;
    is_active: boolean;
}

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    testItem: {
        type: [Object, null],
        required: false,
        default: null,
    },
    testTypes: {
        type: Array as PropType<{ id: number; name: string }[]>,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'saved']);
const isLoading = ref(false);
const startMenu = ref(false);
const endMenu = ref(false);

const form = useForm<TestProps>({
    name: '',
    slug: '',
    description: '',
    start_date: '',
    end_date: '',
    test_types_id: null,
    attempt_limit: 1,
    time_limit: 0,
    is_active: true,
});

const dialog = ref(props.modelValue);

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (value && props.testItem) {
            form.name = props.testItem.name;
            form.slug = props.testItem.slug;
            form.description = props.testItem.description;
            form.start_date = props.testItem.start_date;
            form.end_date = props.testItem.end_date;
            form.test_types_id = props.testItem.test_types_id;
            form.attempt_limit = props.testItem.attempt_limit;
            form.time_limit = props.testItem.time_limit;
            form.is_active = props.testItem.is_active;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});


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


const formattedStartDate = computed<string>({
    get: () => form.start_date ? formatDate(form.start_date) : '',
    set: (val: string) => {
        form.start_date = parseDate(val);
    }
});

const formattedEndDate = computed<string>({
    get: () => form.end_date ? formatDate(form.end_date) : '',
    set: (val: string) => {
        form.end_date = parseDate(val);
    }
});


const validationErrors = computed(() => {
    const errors: Partial<Record<keyof TestProps, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Название теста обязательно';
    }

    if (!form.slug || form.slug.trim() === '') {
        errors.slug = 'Слаг теста обязательно';
    }

    if (!form.description || form.description.trim() === '') {
        errors.description = 'Описание теста обязательно';
    }

    if (!form.start_date || !form.end_date) {
        errors.start_date = 'Дата начала и окончания обязательны';
    }

    if (form.attempt_limit <= 0) {
        errors.attempt_limit = 'Ограничение по попыткам должно быть больше нуля';
    }

    if (form.time_limit <= 0) {
        errors.time_limit = 'Ограничение по времени должно быть больше нуля';
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

    if (props.testItem?.id) {
        form.put(route('test.update', props.testItem.id), {
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
        form.post(route('test.store'), {
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
                {{ props.testItem ? 'Редактировать' : 'Добавить' }}
            </v-card-title>

            <v-card-text>
                <v-text-field
                    v-model="form.name"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Название теста"
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

                <v-textarea
                    v-model="form.description"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Описание"
                    :error-messages="form.errors.description"
                    required
                />

                <v-menu v-model="startMenu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                    <template #activator="{ props: menuProps }">
                        <v-text-field
                            v-model="formattedStartDate"
                            v-bind="menuProps"
                            label="Дата начала"
                            readonly
                            :error-messages="form.errors.start_date"
                            :loading="isLoading"
                            :disabled="isLoading"
                        />
                    </template>
                    <v-date-picker v-model="form.start_date" @update:model-value="startMenu = false" color="primary" elevation="0" />
                </v-menu>

                <v-menu v-model="endMenu" :close-on-content-click="false" transition="scale-transition" offset-y min-width="auto">
                    <template #activator="{ props: menuProps }">
                        <v-text-field
                            v-model="formattedEndDate"
                            v-bind="menuProps"
                            label="Дата окончания"
                            readonly
                            :error-messages="form.errors.end_date"
                            :loading="isLoading"
                            :disabled="isLoading"
                        />
                    </template>
                    <v-date-picker v-model="form.end_date" @update:model-value="endMenu = false" color="primary" elevation="0" />
                </v-menu>

                <v-select
                    v-model="form.test_types_id"
                    :items="props.testTypes"
                    item-value="id"
                    item-title="name"
                    label="Тип теста"
                    :loading="isLoading"
                    :disabled="isLoading"
                    :error-messages="form.errors.test_types_id"
                    required
                />

                <v-text-field
                    v-model="form.attempt_limit"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Ограничение по попыткам"
                    type="number"
                    :error-messages="form.errors.attempt_limit"
                    required
                />

                <v-text-field
                    v-model="form.time_limit"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Ограничение по времени (в минутах)"
                    type="number"
                    :error-messages="form.errors.time_limit"
                    required
                />

                <v-checkbox v-model="form.is_active" :loading="isLoading" :disabled="isLoading" label="Активность" />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn :loading="isLoading" :disabled="isLoading" color="grey" text @click="dialog = false"> Отмена </v-btn>
                <v-btn :loading="isLoading && form.processing" :disabled="isLoading" color="primary" @click="submit"> Сохранить </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
