<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

import {
  VCarousel,
  VCarouselItem,
  VCard,
  VCardText,
  VCardTitle,
  VCardSubtitle,
  VBtn,
} from 'vuetify/components';

const props = defineProps<{
  news: {
    id: number;
    title: string;
    content: string;
    user: { name: string };
    type: { name: string };
  }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Новости',
    href: '/news/news',
  },
];

const carouselNews = props.news.slice(0, 5);
</script>

<template>
  <Head title="Новости" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="d-flex justify-center">
      <VCarousel
        class="w-100"
        style="max-width: 960px"
        hide-delimiter-background
        show-arrows
        prev-icon="mdi-chevron-left"
        next-icon="mdi-chevron-right"
      >
        <VCarouselItem
          v-for="item in carouselNews"
          :key="item.id"
        >
          <VCard class="mx-auto my-4" max-width="1800">
            <VCardTitle class="text-center">{{ item.title }}</VCardTitle>
            <VCardSubtitle class="text-center text-grey text--darken-1">
              {{ item.user.name }} • {{ item.type.name }}
            </VCardSubtitle>
            <VCardText class="text-center">{{ item.content }}</VCardText>
          </VCard>
        </VCarouselItem>

        <VCarouselItem key="view-all">
          <VCard class="mx-auto my-4" max-width="900">
            <VCardTitle class="text-center">Посмотреть все новости</VCardTitle>
            <VCardText class="text-center">
              <VBtn href="/news/newsList" color="primary">Перейти ко всем новостям</VBtn>
            </VCardText>
          </VCard>
        </VCarouselItem>
      </VCarousel>
    </div>
  </AppLayout>
</template>
