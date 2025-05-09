<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { VBtn, VCard, VCardActions, VCardText, VCardTitle, VDialog } from 'vuetify/components';
import { VCalendar } from 'vuetify/labs/VCalendar';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps<{
    calendars?: any[];
    events?: any[];
    users?: any[];
    tests?: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Мой календарь аттестаций',
        href: '/test-calendar',
    },
];

// Преобразуем события календаря в формат для VCalendar
const calendarEvents = computed(() => {
    return (
        props.calendars?.map((event) => ({
            id: event.id,
            start: new Date(event.event_date),
            end: new Date(event.event_date),
            title: event.event_type?.name || 'Событие',
            data: event,
            color: getEventColor(event.event_type_id),
        })) || []
    );
});

const selectedEvent = ref<any>(null);
const isDialogVisible = ref(false);

// Цвета для разных типов событий
const getEventColor = (eventTypeId: number) => {
    const colors = ['primary', 'secondary', 'success', 'warning', 'error'];
    return colors[eventTypeId % colors.length];
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('ru-RU', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

const handleEventClick = (event: any) => {
    if (event?.data) {
        selectedEvent.value = event.data;
        isDialogVisible.value = true;
    }
};
</script>

<template>
    <Head title="Мой календарь аттестаций" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="pa-6">
            <VCard class="mb-6">
                <VCardTitle>Мои аттестации</VCardTitle>
                <VCardText>
                    <VCalendar color="primary" type="month" :events="calendarEvents" @click:event="handleEventClick">
                        <template #event="{ event }">
                            <div class="v-calendar-event">
                                <VBtn :color="event.color" @click.stop="handleEventClick(event)">
                                    {{ event.title }}
                                </VBtn>
                            </div>
                        </template>
                    </VCalendar>
                </VCardText>
            </VCard>

            <VDialog v-model="isDialogVisible" max-width="500px">
                <VCard v-if="selectedEvent">
                    <VCardTitle class="text-h6">{{ selectedEvent.event_type?.name || 'Событие' }}</VCardTitle>
                    <VCardText>
                        <p><strong>Дата:</strong> {{ formatDate(selectedEvent.event_date) }}</p>
                        <p><strong>Тест:</strong> {{ selectedEvent.testing?.name || 'Не указан' }}</p>
                        <p><strong>Примечание:</strong> {{ selectedEvent.notes || 'Нет примечаний' }}</p>
                        <p>
                            <strong>Статус уведомления:</strong>
                            {{ selectedEvent.is_notified ? 'Получено' : 'Ожидает уведомления' }}
                        </p>
                    </VCardText>
                    <VCardActions>
                        <VBtn
                            v-if="!selectedEvent.is_notified"
                            color="primary"
                            @click="Inertia.post(route('test-calendar.markAsNotified', selectedEvent.id))"
                        >
                            Отметить как уведомлён
                        </VBtn>
                        <VBtn color="secondary" @click="isDialogVisible = false">Закрыть</VBtn>
                    </VCardActions>
                </VCard>
            </VDialog>
        </div>
    </AppLayout>
</template>

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

.v-calendar-event {
    padding: 4px;
}
</style>
