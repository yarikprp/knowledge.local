<template>
    <Head :title="user.name" />
    <AppLayout :breadcrumbs="[{ title: 'Просмотр пользователя', href: '/user/Show' }]">
        <div class="pa-4">
            <VCard>
                <VCardTitle class="text-h5">{{ user.name }}</VCardTitle>
                <VCardSubtitle class="text-subtitle-1">
                    Почта: {{ user.email }} | Верификация: {{ user.email_verified_at ? 'Подтвержден' : 'Не подтвержден' }}
                </VCardSubtitle>

                <VCardText class="mt-4">
                    <p><strong>Дата создания:</strong> {{ formatDate(user.created_at) }}</p>
                    <p><strong>Дата обновления:</strong> {{ formatDate(user.updated_at) }}</p>
                    <p>
                        <strong>Роли:</strong>
                        <span v-if="user.roles && user.roles.length">
                            {{ user.roles.map((role) => role.name).join(', ') }}
                        </span>
                        <span v-else>Нет ролей</span>
                    </p>
                </VCardText>

                <VCardActions>
                    <VBtn color="primary" @click="goBack">
                        <v-icon left>mdi-arrow-left</v-icon>
                        Назад
                    </VBtn>
                </VCardActions>
            </VCard>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { formatDate } from '@/utils/formatDate';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const goBack = () => {
    window.history.back();
};
</script>
