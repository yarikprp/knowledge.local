import '../css/app.css';

import { Toaster } from 'vue-sonner'
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { createPinia } from 'pinia';
import { createStore } from 'vuex';
import { createVuetify } from 'vuetify';
import '@mdi/font/css/materialdesignicons.css';
import { ru } from 'vuetify/locale';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

const appName = 'Система знаний';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./pages/${name}.vue`, import.meta.glob<DefineComponent>('./pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vuetify = createVuetify({
            locale: {
              locale: 'ru',
              messages: { ru },
            },
            icons: {
                iconfont: 'mdi',
            },
          });
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .use(createPinia)
            .use(createStore)
            .component('Toaster', Toaster)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
