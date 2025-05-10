<template>
    <Head title="Варианты ответов" />
    <AppLayout :breadcrumbs="[{ title: 'Варианты ответов', href: 'question/options' }]">
        <div class="pa-4">
            <ToolbarDataTable
                title="Варианты ответов"
                :isParentLoading="isLoading"
                :showSearch="true"
                v-model="search"
                @refresh="refreshItems"
                @add="addItem"
                @back="goBack"
            />

            <VDataTable :headers="headers" :items="options" :search="search" item-value="id" no-data-text="Ответов нет" :loading="isLoading">
                <template v-slot:[`item.actions`]="{ item }">
                    <ActionMenu
                        :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                        @edit="updateItem(item as Options)"
                        @delete="deleteItem(item as Options)"
                    />
                </template>

                <template #loading>
                    <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                </template>
            </VDataTable>

            <OptionModal v-model="dialog" :OptionItem="selected" :questions="props.questions" @saved="refreshItems" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import ActionMenu from '@/components/General/ActionMenu.vue';
import ToolbarDataTable from '@/components/General/ToolbarDataTable.vue';
import OptionModal from '@/components/Modal/Question/OptionModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

interface Options {
    id: number;
    text: string;
    question: {
        name: string;
    };
}

const props = defineProps<{
    options: Options[];
    questions: { id: number; name: string }[];
}>();

const search = ref('');
const selected = ref<Options | null>(null);
const dialog = ref(false);
const isLoading = ref(false);

const headers = ref([
    { title: 'Действия', key: 'actions', sortable: false },
    { title: '#', key: 'id' },
    { title: 'Заголовок', key: 'text' },
    { title: 'Вопрос', key: 'question.name' },
]);

interface Options {
    id: number;
    text: string;
    question_id: number;
    questions: {
        id: number;
        name: string;
    };
}

const refreshItems = () => {
    isLoading.value = true;
    router.reload({
        only: ['options'],
        onFinish: () => {
            isLoading.value = false;
        },
    });
};

const addItem = () => {
    selected.value = null;
    dialog.value = true;
};

const updateItem = (item: Options) => {
    selected.value = item;
    dialog.value = true;
};

const deleteItem = (item: Options) => {
    if (confirm(`Удалить вариант ответа "${item.text}"?`)) {
        Inertia.delete(`/question/option/${item.id}`, {
            onSuccess: () => {
                alert('Вариант ответа успешно удален.');
                refreshItems();
            },
            onError: () => {
                alert('Ошибка при вариант ответа.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
