<template>
    <v-menu offset-x>
        <template v-slot:activator="{ props }">
            <v-btn v-bind="props" color="deep-purple darken-2" icon>
                <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
        </template>
        <v-list>
            <v-list-item v-if="isShowGoToView" class="px-2">
                <v-tooltip left>
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" color="blue" icon variant="text" @click="handleGoToView">
                            <v-icon>mdi-eye-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Посмотреть</span>
                </v-tooltip>
            </v-list-item>
            <v-list-item v-if="isShowEdit" class="px-2">
                <v-tooltip left>
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" color="orange" icon variant="text" @click="handleEdit">
                            <v-icon>mdi-pencil-outline</v-icon>
                        </v-btn>
                    </template>
                    <span>Редактировать</span>
                </v-tooltip>
            </v-list-item>
            <v-list-item v-if="isShowDelete" class="px-2">
                <v-tooltip left>
                    <template v-slot:activator="{ props }">
                        <v-btn v-bind="props" color="grey darken-1" icon variant="text" @click="handleDelete">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </template>
                    <span>Удалить</span>
                </v-tooltip>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    buttons: {
        type: Object,
        default: () => ({
            isEdit: false,
            isDelete: false,
            isGoToView: false,
        }),
    },
});

const emit = defineEmits(['edit', 'delete', 'goToView']);

const isShowEdit = computed(() => props.buttons.isEdit);
const isShowDelete = computed(() => props.buttons.isDelete);
const isShowGoToView = computed(() => props.buttons.isGoToView);

const handleEdit = () => emit('edit');
const handleDelete = () => emit('delete');
const handleGoToView = () => emit('goToView');
</script>
