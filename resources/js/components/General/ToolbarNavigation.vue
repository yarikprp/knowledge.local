<template>
    <v-toolbar flat density="comfortable">
        <v-toolbar-title>{{ title }}</v-toolbar-title>
        <v-spacer />

        <v-menu :close-on-content-click="false" transition="slide-y-transition" offset-y bottom>
            <template v-slot:activator="{ props: menuProps }">
                <v-btn v-bind="menuProps" :loading="isParentLoading" :disabled="isParentLoading" color="primary" class="white--text ml-1">
                    <v-icon :left="mdAndUp">mdi-dots-vertical</v-icon>
                    <span class="d-none d-md-flex">{{ actionsLabel }}</span>
                </v-btn>
            </template>

            <v-list>
                <v-list-item v-if="hasRefresh" :loading="isParentLoading" :disabled="isParentLoading" @click="emitRefresh">
                    <v-list-item-title> <v-icon>mdi-sync</v-icon>Обновить</v-list-item-title>
                </v-list-item>
                <v-list-item v-if="hasEdit" @click="emitEdit">
                    <v-list-item-title> <v-icon>mdi-pencil</v-icon>Редактировать</v-list-item-title>
                </v-list-item>
                <v-list-item v-if="hasDelete" @click="emitDelete">
                    <v-list-item-title> <v-icon>mdi-delete</v-icon>Удалить</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-menu>
    </v-toolbar>
</template>

<script setup lang="ts">
import { useDisplay } from 'vuetify';

defineProps({
    title: {
        type: String,
        required: true,
    },
    actionsLabel: {
        type: String,
        default: 'Действия',
    },
    hasRefresh: {
        type: Boolean,
        default: true,
    },
    hasEdit: {
        type: Boolean,
        default: true,
    },
    hasDelete: {
        type: Boolean,
        default: true,
    },
    isParentLoading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['refresh', 'edit', 'delete']);

const { mdAndUp } = useDisplay();

const emitRefresh = () => {
    emit('refresh');
};

const emitEdit = () => {
    emit('edit');
};

const emitDelete = () => {
    emit('delete');
};
</script>
