<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage  } from '@inertiajs/vue3';
import { ClipboardCheck, BookOpen, Shield, LayoutGrid, Newspaper, UserCircle, CalendarDays } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { type PageProps } from '@/types/PageProps';

const { props } = usePage<PageProps>();
const user = props.auth.user;

const mainNavItems: NavItem[] = [
    {
        title: 'Ознакомление',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Новости',
        href: '/news',
        icon: Newspaper,
    },
    {
        title: 'Предмет',
        href: '/subject/list',
        icon: BookOpen,
    },
    {
        title: 'Аттестации',
        href: '/test/list',
        icon: ClipboardCheck,
    },
    {
        title: 'Календарь аттестаций',
        href: '/test-calendar',
        icon: CalendarDays,
    },
];

if (user?.id === 1) {
    mainNavItems.push(
        {
            title: 'Админ',
            href: '/admin',
            icon: Shield,
        },
        {
            title: 'Наставник',
            href: '/mentor',
            icon: UserCircle,
        }
    );
} else {
    if (user?.id === 2) {
        mainNavItems.push({
            title: 'Наставник',
            href: '/mentor',
            icon: UserCircle,
        });
    }
}

const footerNavItems: NavItem[] = [
    {
        title: 'Документация',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
