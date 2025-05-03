<template>
    <Head title="Предметы" />
    <AppLayout :breadcrumbs="[{ title: 'Предметы', href: '/subject/list' }]">
        <div class="pa-4">
            <template v-if="isLoading">
                <div class="mb-4 text-center">Загрузка...</div>
                <v-row dense>
                    <v-col v-for="n in 4" :key="n" cols="12" sm="6" md="4" lg="3">
                        <v-skeleton-loader type="card" />
                    </v-col>
                </v-row>
            </template>

            <template v-else>
                <v-row dense>
                    <v-col v-for="item in subject" :key="item.id" cols="12">
                        <v-card class="elevation-2 rounded-lg">
                            <v-card-title class="text-h6">{{ item.name }}</v-card-title>
                            <v-card-text class="text-body-2">
                                {{ item.description || 'Описание отсутствует.' }}
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn :href="`/subject/${item.id}`" color="primary" variant="flat"> Перейти </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </template>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';

interface Subject {
    id: number;
    name: string;
    description?: string;
}

defineProps<{
    subject: Subject[];
}>();

const isLoading = ref(false);
</script>
