<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Аттестация',
    href: '/test',
  },
];
</script>

<template>
  <Head title="Аттестация" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="pa-6">

    </div>
  </AppLayout>
</template>


<style scoped>
.pa-6 {
  padding: 32px;
}

.d-flex {
  display: flex;
}

.flex-column {
  flex-direction: column;
}

.gap-4 {
  gap: 16px;
}

.mt-4 {
  margin-top: 16px;
}

.text-h6 {
  font-weight: 600;
}

.mb-6 {
  margin-bottom: 24px;
}

.v-card {
  background-color: #f5f5f5;
  border-radius: 12px;
}

.v-card-title {
  background-color: #1976d2;
  color: white;
  padding: 16px;
  border-radius: 12px 12px 0 0;
}

.v-btn {
  font-size: 16px;
  padding: 12px;
}
</style>
