<template>
    <Head title="Календарь аттестаций" />

    <AppLayout :breadcrumbs="[{ title: 'Календарь аттестаций', href: '/test-calendar' }]">
        <div class="pa-6">
            <VCard class="mb-6">
                <VCardTitle>Календарь аттестаций</VCardTitle>
                <VCardText>
                    <VCalendar color="primary" type="month" :events="calendarEvents" @click:event="handleEventClick">
                        <template #day="{ date }">
                            <div>{{ formatDay(date) }}</div>
                        </template>
                        <template #event="{ event }">
                            <div class="v-calendar-event">
                                <VBtn color="primary" @click.stop="handleEventClick(event)" class="rounded-btn">
                                    <v-icon>mdi-eye</v-icon>
                                </VBtn>
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
                        <VBtn color="primary" @click="isDialogVisible = false">Закрыть</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useDate } from 'vuetify';
import { VBtn, VCard, VCardActions, VCardText, VCardTitle, VDialog } from 'vuetify/components';
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
    }))
);

const selectedEvent = ref<Calendar | null>(null);
const isDialogVisible = ref(false);
const adapter = useDate();

function formatDate(date: string): string {
    const formattedDate = new Date(date);
    return adapter.format(formattedDate, 'DD MMM YYYY, HH:mm');
}

function formatDay(date: Date): string {
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    return days[date.getDay()];
}

function handleEventClick(event: any) {
    if (event?.data) {
        selectedEvent.value = event.data;
        isDialogVisible.value = true;
    }
}
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
