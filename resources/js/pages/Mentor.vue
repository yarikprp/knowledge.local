<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { VBtn, VCard, VCardTitle, VCardText } from 'vuetify/components';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Наставник',
    href: '/mentor',
  },
];
</script>

<template>
  <Head title="Наставник" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="pa-6">
      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Учебный материал</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="primary"
              href="/subject"
              prepend-icon="mdi-book-open-page-variant"
              class="d-flex justify-start align-center text-h6"
            >
              Предметы
            </VBtn>
            <VBtn
              color="primary"
              href="/material"
              prepend-icon="mdi-file-document-multiple"
              class="d-flex justify-start align-center text-h6"
            >
              Материалы
            </VBtn>
          </div>
        </VCardText>
      </VCard>

      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Вопросы</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-help-box-multiple"
              class="d-flex justify-start align-center text-h6"
            >
              Вопросы
            </VBtn>
            <VBtn
              color="primary"
              href="/question/type"
              prepend-icon="mdi-shape"
              class="d-flex justify-start align-center text-h6"
            >
              Типы вопросов
            </VBtn>
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-reply-all"
              class="d-flex justify-start align-center text-h6"
            >
              Ответы
            </VBtn>
          </div>
        </VCardText>
      </VCard>

      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Аттестация</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-clipboard-check"
              class="d-flex justify-start align-center text-h6"
            >
              Аттестация
            </VBtn>
            <VBtn
              color="primary"
              href="/test/type"
              prepend-icon="mdi-shape-outline"
              class="d-flex justify-start align-center text-h6"
            >
              Типы аттестации
            </VBtn>
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-chart-bar"
              class="d-flex justify-start align-center text-h6"
            >
              Результаты
            </VBtn>
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-repeat"
              class="d-flex justify-start align-center text-h6"
            >
              Попытки
            </VBtn>
          </div>
        </VCardText>
      </VCard>

      <VCard class="mb-6" outlined>
        <VCardTitle class="headline">Остальное</VCardTitle>
        <VCardText>
          <div class="d-flex flex-column gap-4 mt-4">
            <VBtn
              color="primary"
              href="#"
              prepend-icon="mdi-star-check"
              class="d-flex justify-start align-center text-h6"
            >
              Оценка
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
