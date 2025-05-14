<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { VBtn, VCard, VCardTitle, VCardText, VRow, VCol, VIcon } from 'vuetify/components';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Админ',
    href: '/admin',
  },
];

const adminSections = [
  {
    title: 'Управление контентом',
    icon: 'mdi-cog',
    color: 'primary',
    items: [
      {
        title: 'Новости',
        icon: 'mdi-newspaper-variant',
        href: '/news/newsList',
        color: 'indigo'
      },
      {
        title: 'Типы новостей',
        icon: 'mdi-tag-multiple',
        href: '/news/type',
        color: 'deep-purple'
      },
      {
        title: 'Пользователи',
        icon: 'mdi-account-supervisor',
        href: '/user',
        color: 'blue'
      }
    ]
  },
  {
    title: 'Системные настройки',
    icon: 'mdi-server',
    color: 'secondary',
    items: [
      {
        title: 'Настройки пользователей',
        icon: 'mdi-account-cog',
        href: '/admin/user-settings',
        color: 'teal'
      },
      {
        title: 'Системные настройки',
        icon: 'mdi-tune',
        href: '/admin/system-settings',
        color: 'green'
      },
      {
        title: 'Настройки темы',
        icon: 'mdi-palette-advanced',
        href: '/admin/theme-settings',
        color: 'deep-orange'
      }
    ]
  },
  {
    title: 'Безопасность и данные',
    icon: 'mdi-shield-lock',
    color: 'secondary',
    items: [
      {
        title: 'Резервное копирование',
        icon: 'mdi-database-export',
        href: '/admin/backup',
        color: 'amber'
      },
      {
        title: 'Логи и мониторинг',
        icon: 'mdi-clipboard-text',
        href: '/admin/logs',
        color: 'brown'
      },
      {
        title: 'API управление',
        icon: 'mdi-api',
        href: '/admin/api-settings',
        color: 'purple'
      }
    ]
  },
  {
    title: 'Уведомления',
    icon: 'mdi-bell-alert',
    color: 'secondary',
    items: [
      {
        title: 'Настройки уведомлений',
        icon: 'mdi-bell-cog',
        href: '/admin/notification-settings',
        color: 'pink'
      },
      {
        title: 'Шаблоны сообщений',
        icon: 'mdi-email-edit',
        href: '/admin/notification-templates',
        color: 'red'
      },
      {
        title: 'История уведомлений',
        icon: 'mdi-history',
        href: '/admin/notification-history',
        color: 'cyan'
      }
    ]
  }
];
</script>

<template>
  <Head title="Админ панель" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="admin-dashboard">
      <VRow>
        <VCol
          v-for="(section, index) in adminSections"
          :key="index"
          cols="12"
          md="6"
          lg="4"
          xl="3"
        >
          <VCard class="admin-card" :color="`${section.items[0].color}-lighten-5`">
            <VCardTitle class="admin-section-title">
              <VIcon :icon="section.icon" class="mr-2" size="24" />
              <span>{{ section.title }}</span>
            </VCardTitle>
            <VCardText class="admin-section-content">
              <VBtn
                v-for="(item, i) in section.items"
                :key="i"
                :href="item.href"
                :color="item.color"
                variant="tonal"
                block
                class="mb-3 admin-btn"
              >
                <VIcon :icon="item.icon" class="mr-2" />
                <span>{{ item.title }}</span>
              </VBtn>
            </VCardText>
          </VCard>
        </VCol>
      </VRow>
    </div>
  </AppLayout>
</template>

<style scoped>
.admin-dashboard {
  padding: 24px;
  background-color: #f8f9fa;
}

.admin-card {
  height: 100%;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 12px;
  border-left: 4px solid;
  border-left-color: inherit;
}

.admin-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.15);
}

.admin-section-title {
  display: flex;
  align-items: center;
  padding: 16px;
  font-size: 1.1rem;
  font-weight: 600;
  color: #2c3e50;
  background-color: rgba(255,255,255,0.7);
  border-bottom: 1px solid rgba(0,0,0,0.05);
}

.admin-section-content {
  padding: 16px;
}

.admin-btn {
  justify-content: flex-start;
  padding: 12px 16px;
  text-transform: none;
  font-size: 0.95rem;
  letter-spacing: normal;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.admin-btn:hover {
  transform: translateX(5px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.v-icon {
  font-size: 1.2rem;
}

@media (max-width: 960px) {
  .admin-dashboard {
    padding: 16px;
  }

  .admin-section-title {
    font-size: 1rem;
    padding: 12px;
  }
}
</style>
