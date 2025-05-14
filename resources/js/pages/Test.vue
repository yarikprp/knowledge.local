<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Test {
    id: number;
    name: string;
    description: string;
    test_type: {
        name: string;
    };
    start_date: string;
    end_date: string;
    attempt_limit: number;
    time_limit: number | null;
    is_active: boolean;
}

defineProps<{
    tests: Test[];
    testTypes: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Аттестация',
        href: '/test',
    },
];

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU');
};
</script>

<template>
    <Head title="Аттестация" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="pa-6">
            <h1 class="text-h4 mb-6">Доступные аттестации</h1>

            <div class="d-flex flex-column gap-4">
                <v-card v-for="test in tests" :key="test.id" class="test-card">
                    <v-card-title class="d-flex justify-space-between align-center">
                        <span>{{ test.name }}</span>
                        <v-chip color="white">{{ test.test_type.name }}</v-chip>
                    </v-card-title>

                    <v-card-text class="pt-4">
                        <div class="mb-4">{{ test.description }}</div>

                        <div class="d-flex mb-4 flex-wrap gap-6">
                            <div>
                                <div class="text-caption">Дата начала</div>
                                <div class="font-weight-medium">{{ formatDate(test.start_date) }}</div>
                            </div>

                            <div>
                                <div class="text-caption">Дата окончания</div>
                                <div class="font-weight-medium">{{ formatDate(test.end_date) }}</div>
                            </div>

                            <div>
                                <div class="text-caption">Лимит попыток</div>
                                <div class="font-weight-medium">{{ test.attempt_limit }}</div>
                            </div>

                            <div v-if="test.time_limit">
                                <div class="text-caption">Лимит времени</div>
                                <div class="font-weight-medium">{{ test.time_limit }} мин.</div>
                            </div>
                        </div>

                        <v-btn color="primary" :href="route('attestation.show', test.id)" :disabled="!test.is_active">
                            Начать тест
                            <v-icon end>mdi-arrow-right</v-icon>
                        </v-btn>

                        <v-chip v-if="!test.is_active" color="error" class="ml-4" small> Неактивен </v-chip>
                    </v-card-text>
                </v-card>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.pa-6 {
    padding: 32px;
}

.d-flex {
    display: flex;
}

.flex-column {
    flex-direction: column;
}

.gap-4 {
    gap: 16px;
}

.mt-4 {
    margin-top: 16px;
}

.text-h4 {
    font-size: 2.125rem;
    font-weight: 400;
    line-height: 2.5rem;
    letter-spacing: 0.0073529412em;
}

.mb-6 {
    margin-bottom: 24px;
}

.test-card {
    border-radius: 8px;
    box-shadow:
        0 3px 1px -2px rgba(0, 0, 0, 0.2),
        0 2px 2px 0 rgba(0, 0, 0, 0.14),
        0 1px 5px 0 rgba(0, 0, 0, 0.12);
    transition: box-shadow 0.28s cubic-bezier(0.4, 0, 0.2, 1);
}

.test-card:hover {
    box-shadow:
        0 5px 5px -3px rgba(0, 0, 0, 0.2),
        0 8px 10px 1px rgba(0, 0, 0, 0.14),
        0 3px 14px 2px rgba(0, 0, 0, 0.12);
}

.v-card-title {
    background-color: #1976d2;
    color: white;
    padding: 16px;
    border-radius: 8px 8px 0 0;
}

.v-card-text {
    padding: 16px;
}

.text-caption {
    font-size: 0.75rem;
    font-weight: 400;
    line-height: 1.25rem;
    letter-spacing: 0.0333333333em;
    color: rgba(0, 0, 0, 0.6);
}

.font-weight-medium {
    font-weight: 500;
}
</style>
