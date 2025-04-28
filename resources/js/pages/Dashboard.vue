<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { VBtn } from 'vuetify/components';

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
    <div class="pa-4">
      <VBtn
        color="primary"
        href="/news/newsList"
        prepend-icon="mdi-format-list-bulleted"
      >
        Список новостей
      </VBtn>
      <VBtn
        color="primary"
        href="/news/type"
        prepend-icon="mdi-tag"
      >
        Список типов новостей
      </VBtn>
      <VBtn
        color="primary"
        href="/user"
        prepend-icon="mdi-account-group"
      >
        Список пользователей
      </VBtn>
    </div>
  </AppLayout>
</template>
