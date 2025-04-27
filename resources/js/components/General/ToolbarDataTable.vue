<template>
    <VToolbar>
        <VToolbarTitle>
            <span class="text-h6">{{ title }}</span>
        </VToolbarTitle>

        <VTextField
            v-if="showSearch && mdAndUp"
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
                    <v-icon :left="mdAndUp">mdi-dots-vertical</v-icon>
                    <span class="d-none d-md-flex">{{ actionsLabel }}</span>
                </v-btn>
            </template>

            <v-list>
                <v-list-item v-if="hasRefresh" @click="emitRefresh">
                    <v-list-item-title> <v-icon>mdi-sync</v-icon>Обновить</v-list-item-title>
                </v-list-item>
                <v-list-item v-if="hasAdd" @click="emitAdd">
                    <v-list-item-title> <v-icon>mdi-plus</v-icon>Добавить</v-list-item-title>
                </v-list-item>

                <v-divider v-if="!mdAndUp"></v-divider>
                <v-list v-if="!mdAndUp">
                    <v-list-item>
                        <VTextField
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
                        />
                    </v-list-item>
                </v-list>
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

const emit = defineEmits(['update:modelValue', 'refresh', 'add']);

const { mdAndUp } = useDisplay();

const updateSearch = (newSearch: string) => {
    emit('update:modelValue', newSearch);
};

const emitRefresh = () => {
    emit('refresh');
};

const emitAdd = () => {
    emit('add');
};
</script>
