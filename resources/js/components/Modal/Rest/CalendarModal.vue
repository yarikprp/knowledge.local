<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface Option {
    id: number;
    name: string;
}

interface CalendarItemProps {
    id: number;
    event_date: string;
    notes: string | null;
    is_notified: boolean;
    event_type: Option | null;
    user: Option | null;
    testing: Option | null;
}

interface CalendarForm {
    event_date: string;
    notes: string | null;
    is_notified: boolean;
    event_type_id: number | null;
    user_id: number | null;
    testing_id: number | null;
}

const props = defineProps<{
    modelValue: boolean;
    calendarItem?: CalendarItemProps | null;
    events: Option[];
    users: Option[];
    tests: Option[];
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const dialog = ref(props.modelValue);
const isLoading = ref(false);

const form = useForm<CalendarForm>({
    event_date: '',
    notes: '',
    is_notified: false,
    event_type_id: null,
    user_id: null,
    testing_id: null,
});

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;

        if (value && props.calendarItem) {
            const item = props.calendarItem;
            form.event_date = item.event_date;
            form.notes = item.notes;
            form.is_notified = item.is_notified;
            form.event_type_id = item.event_type?.id ?? null;
            form.user_id = item.user?.id ?? null;
            form.testing_id = item.testing?.id ?? null;
        } else {
            form.reset();
        }
    },
);

watch(dialog, (value) => emit('update:modelValue', value));

const menuDate = ref(false);

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

const formattedDate = computed<string>({
    get: () => form.event_date ? formatDate(form.event_date) : '',
    set: (val: string) => {
        form.event_date = parseDate(val);
    }
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof CalendarForm, string>> = {};
    if (!form.event_date) errors.event_date = 'Дата обязательна';
    if (!form.notes || form.notes.trim() === '') {
        errors.notes = 'Примечание обязательно';
    } else if (form.notes.length > 255) {
        errors.notes = 'Примечание не должно превышать 255 символов';
    }
    if (!form.event_type_id) errors.event_type_id = 'Тип события обязателен';
    if (!form.user_id) errors.user_id = 'Пользователь обязателен';
    return errors;
});

const hasErrors = computed(() => Object.keys(validationErrors.value).length > 0);

const submit = () => {
    if (hasErrors.value) {
        form.setError(validationErrors.value as Record<string, string>);
        return;
    }

    isLoading.value = true;

    const successCallback = () => {
        emit('saved');
        dialog.value = false;
        isLoading.value = false;
    };

    const errorCallback = () => {
        isLoading.value = false;
    };

    if (props.calendarItem?.id) {
        form.put(route('calendar.update', props.calendarItem.id), {
            onSuccess: successCallback,
            onError: errorCallback,
        });
    } else {
        form.post(route('calendar.store'), {
            onSuccess: successCallback,
            onError: errorCallback,
        });
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ props.calendarItem ? 'Редактировать событие' : 'Добавить событие' }}
            </v-card-title>

            <v-card-text>
                <!-- Date -->
                <v-menu v-model="menuDate" :close-on-content-click="false" transition="scale-transition" offset-y max-width="290px" min-width="290px">
                    <template #activator="{ props: menuProps }">
                        <v-text-field
                            v-model="formattedDate"
                            v-bind="menuProps"
                            label="Дата назначения"
                            readonly
                            :error-messages="form.errors.event_date"
                            :loading="isLoading"
                            :disabled="isLoading"
                        />
                    </template>
                    <v-date-picker v-model="form.event_date" @update:model-value="menuDate = false" color="primary" elevation="0" />
                </v-menu>

                <!-- Notes -->
                <v-text-field
                    v-model="form.notes"
                    label="Примечание"
                    :error-messages="form.errors.notes"
                    :disabled="isLoading"
                    required
                />

                <!-- Event Type -->
                <v-select
                    v-model="form.event_type_id"
                    :items="events"
                    item-title="name"
                    item-value="id"
                    label="Тип события"
                    :error-messages="form.errors.event_type_id"
                    :disabled="isLoading"
                    required
                />

                <!-- User -->
                <v-select
                    v-model="form.user_id"
                    :items="users"
                    item-title="name"
                    item-value="id"
                    label="Пользователь"
                    :error-messages="form.errors.user_id"
                    :disabled="isLoading"
                    required
                />

                <!-- Testing (optional) -->
                <v-select
                    v-model="form.testing_id"
                    :items="tests"
                    item-title="name"
                    item-value="id"
                    label="Аттестация"
                    :disabled="isLoading"
                    :error-messages="form.errors.testing_id"
                    clearable
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn text color="grey" :disabled="isLoading" @click="dialog = false">Отмена</v-btn>
                <v-btn :loading="form.processing" :disabled="isLoading" color="primary" @click="submit">
                    Сохранить
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
