<template>
    <VToolbar>
        <VToolbarTitle>
            <span class="text-h6">{{ title }}</span>
        </VToolbarTitle>

        <VTextField
            v-if="showSearch"
            :model-value="modelValue"
            @update:model-value="updateSearch"
            label="Поиск"
            prepend-inner-icon="mdi-magnify"
            class="ml-4"
            variant="outlined"
            clearable
            density="compact"
            single-line
            hide-details
            style="max-width: 300px"
        />

        <div class="flex-grow-1"></div>

        <v-menu :close-on-content-click="false" transition="slide-y-transition" offset-y bottom>
            <template v-slot:activator="{ props: menuProps }">
                <v-btn v-bind="menuProps" color="primary" class="white--text ml-1">
                    <v-icon :left="mdAndUp ? '' : 'mdi-menu'">mdi-dots-vertical</v-icon>
                    <span class="d-none d-md-flex">{{ actionsLabel }}</span>
                </v-btn>
            </template>

            <v-list>
                <v-list-item v-if="hasRefresh" @click="emitRefresh">
                    <v-list-item-title>Обновить</v-list-item-title>
                </v-list-item>
                <v-list-item v-if="hasAdd" @click="emitAdd">
                    <v-list-item-title>Добавить</v-list-item-title>
                </v-list-item>
                <slot></slot>
            </v-list>
        </v-menu>
    </VToolbar>
</template>

<script setup lang="ts">
import { defineEmits, defineProps } from 'vue';
import { useDisplay } from 'vuetify';

defineProps({
    title: {
        type: String,
        required: true,
    },
    showSearch: {
        type: Boolean,
        default: true,
    },
    actionsLabel: {
        type: String,
        default: 'Действия',
    },
    hasRefresh: {
        type: Boolean,
        default: true,
    },
    hasAdd: {
        type: Boolean,
        default: true,
    },
    modelValue: {
        type: String,
        required: true,
    },
});

const emit = defineEmits(['update:modelValue', 'refreshNews', 'addNews']);

const { mdAndUp } = useDisplay();

const updateSearch = (newSearch: string) => {
    emit('update:modelValue', newSearch);
};

const emitRefresh = () => {
    emit('refreshNews');
};

const emitAdd = () => {
    emit('addNews');
};
</script>
