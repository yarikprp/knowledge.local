<template>
    <Head title="Попытки" />
    <AppLayout :breadcrumbs="[{ title: 'Список результатов', href: '/test/result' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Список результатов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="results" :search="search" item-value="id" no-data-text="Результатов нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: false, isDelete: false, isGoToView: true }"
                        @goToView="goToView(item as Item)"
                    />
                </template>

                <template v-slot:[`item.test`]="{ item }">
                    {{ (item as Item).test.name }}
                </template>

                <template v-slot:[`item.user`]="{ item }">
                    {{ (item as Item).user.name }}
                </template>

                <template v-slot:[`item.is_passed`]="{ item }">
                    <v-icon v-if="(item as Item).is_passed">mdi-check-circle</v-icon>
                    <v-icon v-else>mdi-circle-outline</v-icon>
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

defineProps<{
    results: any[];
    users: { id: number; name: string }[];
    tests: { id: number; name: string }[];
}>();

const search = ref('');
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Пользователь', key: 'user' },
    { title: 'Аттестация', key: 'test' },
    { title: 'Счет', key: 'score' },
    { title: 'Процент', key: 'percentage' },
    { title: 'Пройденный', key: 'passed' },
    { title: 'Попытка', key: 'attempted_at' },
]);

interface Item {
    id: number;
    user: {
        id: number;
        name: string;
    };
    test: {
        id: number;
        name: string;
    };
    score: number;
    percentage: number;
    passed: boolean;
    attempted_at: string;
    duration: string;
    is_passed: boolean;
    status: string;
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['results'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const goToView = (item: Item) => {
    router.visit(`/test/result/${item.id}`);
};

const goBack = () => {
    window.history.back();
};
</script>
