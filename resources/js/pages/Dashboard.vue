<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Ознакомление',
        href: '/dashboard',
    },
];

const dialog = ref(false);

const openSupportDialog = () => {
    dialog.value = true;
};
</script>

<template>
    <Head title="Ознакомление" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <v-container class="pa-6">
            <v-row align="stretch">
                <v-col cols="12" md="6">
                    <v-card elevation="2" class="d-flex flex-column" style="height: 100%">
                        <v-card-title class="text-h5">Добро пожаловать в Ознакомление</v-card-title>
                        <v-card-subtitle>Здесь вы можете найти полезную информацию для начала работы с платформой.</v-card-subtitle>

                        <v-card-text>
                            <p>
                                На этой странице вы найдете основные разделы для работы с нашей платформой. Ознакомьтесь с основными функциями, чтобы
                                лучше понимать, как использовать все возможности.
                            </p>
                            <p>Если у вас возникли вопросы или проблемы, вы можете связаться с нами через следующие каналы:</p>
                            <v-btn color="primary" @click="openSupportDialog">Перейти к поддержке</v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col cols="12" md="6">
                    <v-card elevation="2" class="d-flex flex-column" style="height: 100%">
                        <v-card-title class="text-h5">Телегам бот</v-card-title>
                        <v-card-text>
                            <p>Чтобы быть в курсе предстоящих аттестаций подпишитесь на телеграм бота.</p>
                            <v-btn color="secondary" :href="'https://t.me/eventslms_bot'" target="_blank"> Перейти к боту </v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>

        <v-dialog v-model="dialog" persistent max-width="500px">
            <v-card>
                <v-card-title class="headline">Служба поддержки</v-card-title>

                <v-card-text>
                    <ul>
                        <li>Телефон: +1 (123) 456-7890</li>
                        <li>Электронная почта: support@platform.com</li>
                        <li>Вам обязательно ответят и помогут!</li>
                    </ul>
                    <p>Мы всегда рады помочь!</p>
                </v-card-text>

                <v-card-actions>
                    <v-btn color="primary" @click="dialog = false">Закрыть</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>
