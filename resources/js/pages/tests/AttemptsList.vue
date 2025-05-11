<template>
    <Head title="Попытки" />
    <AppLayout :breadcrumbs="[{ title: 'Список попыток', href: '/test/attempt' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список попыток"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="attempts" :search="search" item-value="id" no-data-text="Попыток нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Item)"
                        @delete="deleteItem(item as Item)"
                    />
                </template>

                <template v-slot:[`item.test`]="{ item }">
                    {{ (item as Item).test.name }}
                </template>

                <template v-slot:[`item.user`]="{ item }">
                    {{ (item as Item).user.name }}
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    {{ (item as Item).status.name }}
                </template>

                <template v-slot:[`item.is_passed`]="{ item }">
                    <v-icon v-if="(item as Item).is_passed">mdi-check-circle</v-icon>
                    <v-icon v-else>mdi-circle-outline</v-icon>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <AttemptModal v-model="dialog" :attemptItem="selected" :users="props.users" :tests="props.tests" :statusList="props.statusList" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import AttemptModal from '@/components/Modal/Test/AttemptModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

const props = defineProps<{
    attempts: any[];
    users: { id: number; name: string }[];
    tests: { id: number; name: string }[];
    statusList: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Item | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Пользователь', key: 'user' },
    { title: 'Аттестация', key: 'test' },
    { title: 'Количество вопросов', key: 'total_questions' },
    { title: 'Проходной балл', key: 'passing_score' },
    { title: 'Начало', key: 'started_at' },
    { title: 'Конец', key: 'finish_at' },
    { title: 'Счет', key: 'score' },
    { title: 'Передано', key: 'is_passed' },
    { title: 'Статус', key: 'status' },
]);

interface Item {
    id: number;
    slug: string;
    user: {
        id: number;
        name: string;
    };
    test: {
        id: number;
        name: string;
    };
    total_questions: number;
    passing_score: number;
    started_at: string;
    finish_at: string;
    score: number;
    is_passed: boolean;
    status: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['attempts'],
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
    if (confirm(`Удалить попытку?`)) {
        Inertia.delete(`/test/attempt/${item.id}`, {
            onSuccess: () => {
                alert('Попытка успешно удалён.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при удалении попытки.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
