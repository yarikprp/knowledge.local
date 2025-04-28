<template>
    <Head :title="news.name" />
    <AppLayout :breadcrumbs="[{ title: 'Просмотр новости', href: '/news/Show' }]">
      <div class="pa-4">
        <VCard>
          <VCardTitle class="text-h5">{{ news.name }}</VCardTitle>
          <VCardSubtitle class="text-subtitle-1">
            Автор: {{ news.user.name }} | Тип: {{ news.type.name }}
          </VCardSubtitle>

          <VCardText class="mt-4">
            <div v-html="news.content"></div>
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
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    news: {
        type: Object,
        required: true,
    },
});

const goBack = () => {
    window.history.back();
};
</script>
