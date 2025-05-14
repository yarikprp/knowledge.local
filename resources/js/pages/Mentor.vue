<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { VBtn, VCard, VCardTitle, VCardText, VRow, VCol, VIcon } from 'vuetify/components';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Наставник',
    href: '/mentor',
  },
];

const sections = [
  {
    title: 'Учебный материал',
    icon: 'mdi-book-education',
    items: [
      {
        title: 'Предметы',
        icon: 'mdi-book-open-page-variant',
        href: '/subject',
        color: 'deep-purple'
      },
      {
        title: 'Материалы',
        icon: 'mdi-file-document-multiple',
        href: '/material',
        color: 'indigo'
      }
    ]
  },
  {
    title: 'Вопросы',
    icon: 'mdi-help-rhombus',
    items: [
      {
        title: 'Вопросы',
        icon: 'mdi-help-box-multiple',
        href: '/question',
        color: 'blue'
      },
      {
        title: 'Типы вопросов',
        icon: 'mdi-shape',
        href: '/question/type',
        color: 'light-blue'
      },
      {
        title: 'Ответы',
        icon: 'mdi-reply-all',
        href: '/question/correct-answer',
        color: 'cyan'
      },
      {
        title: 'Варианты ответов',
        icon: 'mdi-format-list-checks',
        href: '/question/option',
        color: 'teal'
      }
    ]
  },
  {
    title: 'Аттестация',
    icon: 'mdi-certificate',
    items: [
      {
        title: 'Аттестация',
        icon: 'mdi-clipboard-check',
        href: '/test',
        color: 'green'
      },
      {
        title: 'Календарь аттестаций',
        icon: 'mdi-calendar-check',
        href: '/calendar',
        color: 'light-green'
      },
      {
        title: 'Типы аттестации',
        icon: 'mdi-shape-outline',
        href: '/test/type',
        color: 'lime'
      },
      {
        title: 'Ответы пользователя',
        icon: 'mdi-chart-bar',
        href: '/test/answer',
        color: 'amber'
      },
      {
        title: 'Попытки',
        icon: 'mdi-repeat',
        href: '/test/attempt',
        color: 'orange'
      },
      {
        title: 'Результаты',
        icon: 'mdi-repeat',
        href: '/test/result',
        color: 'orange'
      }
    ]
  },
  {
    title: 'Остальное',
    icon: 'mdi-dots-hexagon',
    items: [
      {
        title: 'Статусы',
        icon: 'mdi-format-list-bulleted',
        href: '/status',
        color: 'deep-orange'
      },
      {
        title: 'События',
        icon: 'mdi-calendar-clock',
        href: '/event',
        color: 'brown'
      }
    ]
  }
];
</script>

<template>
  <Head title="Наставник" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="dashboard-container">
      <VRow>
        <VCol
          v-for="(section, index) in sections"
          :key="index"
          cols="12"
          sm="6"
          md="4"
          lg="3"
        >
          <VCard class="section-card" :color="`${section.items[0].color}-lighten-5`">
            <VCardTitle class="section-title">
              <VIcon :icon="section.icon" class="mr-2" />
              {{ section.title }}
            </VCardTitle>
            <VCardText class="section-content">
              <VBtn
                v-for="(item, i) in section.items"
                :key="i"
                :href="item.href"
                :color="item.color"
                variant="tonal"
                block
                class="mb-2 action-btn"
              >
                <VIcon :icon="item.icon" class="mr-2" />
                {{ item.title }}
              </VBtn>
            </VCardText>
          </VCard>
        </VCol>
      </VRow>
    </div>
  </AppLayout>
</template>

<style scoped>
.dashboard-container {
  padding: 24px;
}

.section-card {
  height: 100%;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 12px;
}

.section-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.section-title {
  display: flex;
  align-items: center;
  padding: 16px;
  font-size: 1.25rem;
  font-weight: 700;
  color: #424242;
  border-bottom: 1px solid rgba(0,0,0,0.1);
}

.section-content {
  padding: 16px;
}

.action-btn {
  justify-content: flex-start;
  padding: 12px 16px;
  text-transform: none;
  font-size: 0.95rem;
  letter-spacing: normal;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.action-btn:hover {
  transform: translateX(5px);
}

.v-icon {
  font-size: 1.25rem;
}
</style>
