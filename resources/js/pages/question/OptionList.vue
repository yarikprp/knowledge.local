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

            <v-expansion-panels v-if="groupedOptions.size > 0">
                <v-expansion-panel v-for="[questionId, questionData] in groupedOptions" :key="questionId">
                    <v-expansion-panel-title>
                        {{ questionData.question.name }} ({{ questionData.options.length }} вариантов)
                    </v-expansion-panel-title>
                    <v-expansion-panel-text>
                        <VDataTable
                            :headers="headers"
                            :items="questionData.options"
                            :search="search"
                            item-value="id"
                            no-data-text="Вариантов нет"
                            :loading="isLoading"
                        >
                            <template v-slot:[`item.actions`]="{ item }">
                                <ActionMenu
                                    :buttons="{ isEdit: true, isDelete: true, isGoToView: false }"
                                    @edit="updateItem(item as Options)"
                                    @delete="deleteItem(item as Options)"
                                />
                            </template>

                            <template v-slot:[`item.is_correct`]="{ item }">
                                <v-icon v-if="(item as Item).is_correct">mdi-check-circle</v-icon>
                                <v-icon v-else>mdi-circle-outline</v-icon>
                            </template>

                            <template #loading>
                                <v-skeleton-loader v-for="n in 5" :key="n" type="table-row" class="mx-2" />
                            </template>
                        </VDataTable>
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>

            <div v-else class="py-4 text-center">
                <p>Вариантов ответов нет</p>
            </div>

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
import { computed, defineProps, ref } from 'vue';

interface Options {
    id: number;
    text: string;
    question_id: number;
    question: {
        id: number;
        name: string;
    };
    is_correct: boolean;
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
    { title: 'Текст варианта', key: 'text' },
    { title: 'Правильность', key: 'is_correct' },
]);

const groupedOptions = computed(() => {
    const groups = new Map<number, { question: { id: number; name: string }; options: Options[] }>();

    props.questions.forEach((question) => {
        groups.set(question.id, {
            question: { id: question.id, name: question.name },
            options: [],
        });
    });

    props.options.forEach((option) => {
        if (groups.has(option.question_id)) {
            groups.get(option.question_id)!.options.push(option);
        } else {
            groups.set(option.question_id, {
                question: option.question,
                options: [option],
            });
        }
    });

    return groups;
});

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
                alert('Ошибка при удалении варианта ответа.');
            },
        });
    }
};

const goBack = () => {
    window.history.back();
};
</script>
