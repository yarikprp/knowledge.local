<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
  subject: {
    id: number;
    name: string;
    materials: Array<{
      id: number;
      name: string;
      content: string;
    }>;
  };
}>();

const goBack = () => {
  window.history.back();
};

const readMaterials = ref<number[]>([]);

const isRead = (id: number) => readMaterials.value.includes(id);

const markAsRead = (id: number) => {
  if (!isRead(id)) {
    readMaterials.value.push(id);
  } else {
    readMaterials.value = readMaterials.value.filter(materialId => materialId !== id);
  }
};
</script>

<template>
  <Head :title="subject.name" />
  <AppLayout :breadcrumbs="[{ title: 'Просмотр предмета', href: '/education/SubjectShow' }]">
    <div class="pa-4">
      <VCard>
        <VCardTitle class="text-h5">{{ subject.name }}</VCardTitle>
        <VCardText class="mt-4">
          <div v-if="subject.materials.length">
            <VCard
              v-for="material in subject.materials"
              :key="material.id"
              class="mb-4"
              outlined
            >
              <VCardTitle class="text-subtitle-1">{{ material.name }}</VCardTitle>
              <VCardText>{{ material.content }}</VCardText>
              <VCardActions class="d-flex justify-end">
                <VBtn
                  :color="isRead(material.id) ? 'green' : 'grey'"
                  @click="markAsRead(material.id)"
                >
                  <v-icon left>{{ isRead(material.id) ? 'mdi-check-circle' : 'mdi-circle-outline' }}</v-icon>
                  {{ isRead(material.id) ? 'Прочитано' : 'Не прочитано' }}
                </VBtn>
              </VCardActions>
            </VCard>
          </div>
          <div v-else>
            <p class="text-grey">Материалы отсутствуют.</p>
          </div>
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
