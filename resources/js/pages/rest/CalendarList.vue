<template>
    <Head title="Календарь" />
    <AppLayout :breadcrumbs="[{ title: 'Календарь', href: '/calendar' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Календарь"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="calendars" :search="search" item-value="id" no-data-text="Событий нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template v-slot:[`item.event_date`]="{ item }">
                    {{ formatDate((item as Item).event_date, 'DD MMMM YYYY, HH:mm') }}
                </template>

                <template v-slot:[`item.event_type_name`]="{ item }">
                    {{ (item as Item).event_type?.name || '-' }}
                </template>

                <template v-slot:[`item.user_name`]="{ item }">
                    {{ (item as Item).user?.name || '-' }}
                </template>

                <template v-slot:[`item.testing_name`]="{ item }">
                    {{ (item as Item).testing?.name || '-' }}
                </template>

                <template v-slot:[`item.is_notified`]="{ item }">
                    <v-chip :color="(item as Item).is_notified ? 'green' : 'grey'" small>
                        {{ (item as Item).is_notified ? 'Да' : 'Нет' }}
                    </v-chip>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <CalendarModal
                v-model="dialog"
                :CalendarFormFormFormItem="selected"
                :events="events"
                :users="users"
                :tests="tests"
                @saved="refreshItems"
            />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import CalendarModal from '@/components/Modal/Rest/CalendarModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/utils/formatDate';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps<{
    calendars: Item[];
    events: { id: number; name: string }[];
    users: { id: number; name: string }[];
    tests: { id: number; name: string }[];
}>();


const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Дата события', key: 'event_date' },
    { title: 'Примечания', key: 'notes' },
    { title: 'Тип события', key: 'event_type_name' },
    { title: 'Уведомлен', key: 'is_notified' },
    { title: 'Пользователь', key: 'user_name' },
    { title: 'Аттестация', key: 'testing_name' },
]);

interface Item {
    id: number;
    event_date: string;
    notes: string | null;
    is_notified: boolean;
    event_type: {
        id: number;
        name: string;
    } | null;
    user: {
        id: number;
        name: string;
    } | null;
    testing: {
        id: number;
        name: string;
    } | null;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['calendars'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const addItem = () => {
    selected.value = null;
    dialog.value = true;
};

const updateItem = (item: Item) => {
    selected.value = item;
    dialog.value = true;
};


const deleteItem = (item: Item) => {
    const formatted = formatDate(item.event_date, 'DD MMMM YYYY, HH:mm');
    if (confirm(`Удалить запись с датой "${formatted}"?`)) {
        Inertia.delete(`/calendar/${item.id}`, {
            onSuccess: () => {
                alert('Запись успешно удалена.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении записи.');
            },
        });
    }
};


const goBack = () => {
    window.history.back();
};
</script>
