<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

import { VBtn, VCard, VCardSubtitle, VCardText, VCardTitle, VCarousel, VCarouselItem } from 'vuetify/components';

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
                style="max-width: 100vw"
                hide-delimiter-background
                show-arrows
                prev-icon="mdi-chevron-left"
                next-icon="mdi-chevron-right"
            >
                <VCarouselItem v-for="item in carouselNews" :key="item.id">
                    <VCard class="mx-auto my-4" max-width="100%">
                        <VCardTitle class="text-center">{{ item.title }}</VCardTitle>
                        <VCardSubtitle class="text-grey text--darken-1 text-center"> {{ item.user.name }} • {{ item.type.name }} </VCardSubtitle>
                        <VCardText class="text-center">{{ item.content }}</VCardText>

                        <VCardActions class="justify-center">
                            <VBtn :href="`/news/${item.id}`" color="primary" variant="tonal"> Читать подробнее </VBtn>
                        </VCardActions>
                    </VCard>
                </VCarouselItem>

                <VCarouselItem key="view-all">
                    <VCard class="mx-auto my-4" max-width="100%">
                        <VCardTitle class="text-center">Посмотреть все новости</VCardTitle>
                        <VCardText class="text-center">
                            <VBtn href="/news/listView" color="primary">Перейти ко всем новостям</VBtn>
                        </VCardText>
                    </VCard>
                </VCarouselItem>
            </VCarousel>
        </div>
    </AppLayout>
</template>

<style scoped>
.v-carousel {
    width: 100% !important;
    height: 100vh;
}

.v-carousel-item {
    height: 100%;
}
</style>
