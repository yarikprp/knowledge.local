<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { VBtn, VCard, VCardTitle, VCardText } from 'vuetify/components';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Админ',
    href: '/dashboard',
  },
];
</script>

<template>
  <Head title="Админ" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="pa-6">
      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Управление</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="primary"
              href="/news/newsList"
              prepend-icon="mdi-format-list-bulleted"
              class="d-flex justify-start align-center text-h6"
            >
              Новости
            </VBtn>
            <VBtn
              color="primary"
              href="/news/type"
              prepend-icon="mdi-tag"
              class="d-flex justify-start align-center text-h6"
            >
              Типы новостей
            </VBtn>
            <VBtn
              color="primary"
              href="/user"
              prepend-icon="mdi-account-group"
              class="d-flex justify-start align-center text-h6"
            >
              Пользователи
            </VBtn>
          </div>
        </VCardText>
      </VCard>

      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Дополнительные настройки</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="secondary"
              href="/admin/user-settings"
              prepend-icon="mdi-account-cog"
              class="d-flex justify-start align-center text-h6"
            >
              Настройки пользователей
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/system-settings"
              prepend-icon="mdi-cogs"
              class="d-flex justify-start align-center text-h6"
            >
              Системные настройки
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/theme-settings"
              prepend-icon="mdi-palette"
              class="d-flex justify-start align-center text-h6"
            >
              Настройки темы
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/backup"
              prepend-icon="mdi-backup"
              class="d-flex justify-start align-center text-h6"
            >
              Резервное копирование
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/api-settings"
              prepend-icon="mdi-api"
              class="d-flex justify-start align-center text-h6"
            >
              Настройки API
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/logs"
              prepend-icon="mdi-file-document"
              class="d-flex justify-start align-center text-h6"
            >
              Логи и мониторинг
            </VBtn>
            <VBtn
              color="secondary"
              href="/admin/notification-settings"
              prepend-icon="mdi-bell-ring"
              class="d-flex justify-start align-center text-h6"
            >
              Настройки уведомлений
            </VBtn>
          </div>
        </VCardText>
      </VCard>
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

.text-h6 {
  font-weight: 600;
}

.mb-6 {
  margin-bottom: 24px;
}

.v-card {
  background-color: #f5f5f5;
  border-radius: 12px;
}

.v-card-title {
  background-color: #1976d2;
  color: white;
  padding: 16px;
  border-radius: 12px 12px 0 0;
}

.v-btn {
  font-size: 16px;
  padding: 12px;
}
</style>
