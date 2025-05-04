<template>
    <Head title="Список событий" />
    <AppLayout :breadcrumbs="[{ title: 'Список событий', href: '/event' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список событий"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="events" :search="search" item-value="id" no-data-text="Событий нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <EventTypeModal v-model="dialog" :EventTypeFormFormItem="selected" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import EventTypeModal from '@/components/Modal/Rest/EventTypeModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps({
    events: {
        type: Array,
        required: true,
    },
});

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'name' },
]);

interface Item {
    id: number;
    name: string;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['events'],
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
    if (confirm(`Удалить событие "${item.name}"?`)) {
        Inertia.delete(`/event/${item.id}`, {
            onSuccess: () => {
                alert('Событие успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении события.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
