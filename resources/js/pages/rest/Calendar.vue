<template>
    <Head title="Календарь аттестаций" />

    <AppLayout :breadcrumbs="[{ title: 'Календарь аттестаций', href: '/test-calendar' }]">
        <div class="pa-6">
            <VCard class="mb-6">
                <v-toolbar>
                    <v-btn icon @click="goBack">
                        <v-icon>mdi-arrow-left</v-icon>
                    </v-btn>
                    <span class="ml-2">Календарь аттестаций</span>

                    <v-spacer></v-spacer>

                    <VBtn color="primary" @click="openCreateDialog">
                        <v-icon left>mdi-plus</v-icon>
                        Добавить
                    </VBtn>
                </v-toolbar>
                <VCardText>
                    <VCalendar color="primary" type="month" :events="calendarEvents" @click:event="handleEventClick">
                        <template #day="{ date }">
                            <div>{{ formatDay(date) }}</div>
                        </template>

                        <template #event="{ event }">
                            <div class="v-calendar-event">
                                <v-tooltip location="top">
                                    <template #activator="{ props }">
                                        <VBtn v-bind="props" color="primary" @click.stop="handleEventClick(event)" class="rounded-btn">
                                            <v-icon>mdi-eye</v-icon>
                                        </VBtn>
                                    </template>
                                    <div class="text-white">
                                        <div><strong>Пользователь:</strong> {{ getCalendarData(event).user.name }}</div>
                                        <div><strong>Тест:</strong> {{ getCalendarData(event).testing?.name || '—' }}</div>
                                    </div>
                                </v-tooltip>
                            </div>
                        </template>
                    </VCalendar>
                </VCardText>
            </VCard>

            <VDialog v-model="isDialogVisible" max-width="500px">
                <VCard v-if="selectedEvent">
                    <VCardTitle class="text-h6">{{ selectedEvent.event_type.name }}</VCardTitle>
                    <VCardText>
                        <p><strong>Дата:</strong> {{ formatDate(selectedEvent.event_date) }}</p>
                        <p><strong>Пользователь:</strong> {{ selectedEvent.user.name }}</p>
                        <p><strong>Тест:</strong> {{ selectedEvent.testing?.name || '—' }}</p>
                        <p><strong>Примечание:</strong> {{ selectedEvent.notes || '—' }}</p>
                    </VCardText>
                    <VCardActions>
                        <VBtn color="grey" @click="isDialogVisible = false">Закрыть</VBtn>
                        <VBtn color="blue" @click="openEditDialog(selectedEvent)">Редактировать</VBtn>
                        <VBtn color="red" @click="deleteEvent(selectedEvent.id)">Удалить</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>

            <CalendarModal
                v-model="isFormDialogVisible"
                :CalendarFormFormFormItem="editableEvent"
                :events="props.events"
                :users="props.users"
                :tests="props.tests"
                @saved="reloadEvents"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import CalendarModal from '@/components/Modal/Rest/CalendarModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useDate } from 'vuetify';
import { VCalendar } from 'vuetify/labs/VCalendar';

interface EventType {
    id: number;
    name: string;
}
interface User {
    id: number;
    name: string;
}
interface Test {
    id: number;
    name: string;
}
interface Calendar {
    id: number;
    event_date: string;
    event_type: EventType;
    notes: string | null;
    is_notified: boolean;
    user: User;
    testing: Test | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    calendars: Calendar[];
    events: EventType[];
    users: User[];
    tests: Test[];
}>();

const calendarEvents = ref(
    props.calendars.map((event) => ({
        id: event.id,
        name: `${event.event_type.name} — ${event.user.name}`,
        start: new Date(event.event_date),
        end: new Date(event.event_date),
        color: 'blue',
        timed: false,
        data: event,
        user: event.user.name,
        eventType: event.event_type.name,
    })),
);
const selectedEvent = ref<Calendar | null>(null);
const editableEvent = ref<Calendar | null>(null);
const isDialogVisible = ref(false);
const isFormDialogVisible = ref(false);
const adapter = useDate();

function getCalendarData(event: any): Calendar {
    return event.data as Calendar;
}

watch(
    () => props.calendars,
    (newCalendars) => {
        calendarEvents.value = newCalendars.map((event) => ({
            id: event.id,
            name: `${event.event_type.name} — ${event.user.name}`,
            start: new Date(event.event_date),
            end: new Date(event.event_date),
            color: 'blue',
            timed: false,
            data: event,
            user: event.user.name,
            eventType: event.event_type.name,
        }));
    },
    { immediate: true },
);

function formatDate(date: string): string {
    return adapter.format(new Date(date), 'DD MMM YYYY, HH:mm');
}

function formatDay(date: Date): string {
    return ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'][date.getDay()];
}

function handleEventClick(event: any) {
    if (event?.data) {
        selectedEvent.value = event.data;
        isDialogVisible.value = true;
    }
}

function openCreateDialog() {
    editableEvent.value = null;
    isFormDialogVisible.value = true;
}

function openEditDialog(event: Calendar) {
    isDialogVisible.value = false;
    editableEvent.value = event;
    isFormDialogVisible.value = true;
}

function deleteEvent(id: number) {
    if (confirm('Вы уверены, что хотите удалить событие?')) {
        router.delete(route('calendar.destroy', id), {
            onSuccess: () => {
                reloadEvents();
                isDialogVisible.value = false;
            },
        });
    }
}

function reloadEvents() {
    router.reload({ only: ['calendars'] });
}

const goBack = () => {
    window.history.back();
};
</script>

<style scoped>
.pa-6 {
    padding: 32px;
}
.v-card-title {
    background-color: #1976d2;
    color: white;
    padding: 16px;
    border-radius: 12px 12px 0 0;
}
.mb-6 {
    margin-bottom: 24px;
}
.rounded-btn {
    border-radius: 50%;
    padding: 8px;
    min-width: 40px;
    height: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
