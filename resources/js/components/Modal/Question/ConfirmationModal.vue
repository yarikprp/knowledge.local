<template>
  <v-dialog v-model="isOpen" max-width="500">
    <v-card>
      <v-card-title class="text-h6">{{ title }}</v-card-title>
      <v-card-text>{{ message }}</v-card-text>
      <v-card-actions class="justify-end">
        <v-btn text @click="cancel">Отмена</v-btn>
        <v-btn color="red" text @click="confirm">Подтвердить</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script  lang="ts" setup>
import { defineProps, defineEmits, watch } from 'vue'
import { ref } from 'vue'

const props = defineProps({
  modelValue: Boolean,
  title: {
    type: String,
    default: 'Вы уверены?',
  },
  message: {
    type: String,
    default: 'Это действие нельзя отменить.',
  },
})

const emit = defineEmits(['update:modelValue', 'confirm'])

const isOpen = ref(props.modelValue)

watch(() => props.modelValue, (val) => {
  isOpen.value = val
})

const cancel = () => {
  emit('update:modelValue', false)
}

const confirm = () => {
  emit('update:modelValue', false)
  emit('confirm')
}
</script>
