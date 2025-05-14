<template>
    <Head :title="`Просмотр результата #${result.id}`" />

    <AppLayout :breadcrumbs="[{ title: 'Назад', href: '/test/result' }]">
        <div class="pa-6 space-y-6">
            <v-card elevation="2">
                <v-toolbar flat color="transparent">
                    <v-toolbar-title>Информация о результате №{{ result.id }}</v-toolbar-title>
                </v-toolbar>

                <v-divider></v-divider>

                <v-card-text>
                    <v-row dense>
                        <v-col cols="12" md="6"> <strong>Пользователь:</strong> {{ result.user?.name ?? '—' }} </v-col>

                        <v-col cols="12" md="6"> <strong>Аттестация:</strong> {{ result.test?.name ?? '—' }} </v-col>

                        <v-col cols="12" md="6"> <strong>Счет:</strong> {{ result.score }} </v-col>

                        <v-col cols="12" md="6"> <strong>Процент:</strong> {{ result.percentage }}% </v-col>

                        <v-col cols="12" md="6">
                            <strong>Пройден:</strong>
                            <v-icon color="success" v-if="result.is_passed">mdi-check-circle</v-icon>
                            <v-icon color="error" v-else>mdi-close-circle</v-icon>
                        </v-col>

                        <v-col cols="12" md="6"> <strong>Дата попытки:</strong> {{ result.attempted_at }} </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    result: {
        id: number;
        user: { name: string };
        test: { name: string };
        score: number;
        percentage: number;
        is_passed: boolean;
        attempted_at: string;
    };
}>();
</script>
