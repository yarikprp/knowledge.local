import { onMounted, ref } from 'vue';
import { useTheme } from 'vuetify';

type Appearance = 'light' | 'dark' | 'system';

const setCookie = (name: string, value: string, days = 365) => {
    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const mediaQuery = () => {
    return window.matchMedia('(prefers-color-scheme: dark)');
};

const getStoredAppearance = () => {
    return localStorage.getItem('appearance') as Appearance | null;
};

export function useAppearance() {
    const theme = useTheme();
    const appearance = ref<Appearance>('system');

    const updateTheme = (value: Appearance) => {
        if (value === 'system') {
            const prefersDark = mediaQuery().matches;
            theme.global.name.value = prefersDark ? 'dark' : 'light';
        } else {
            theme.global.name.value = value;
        }

        document.documentElement.classList.toggle('dark', theme.global.name.value === 'dark');
    };

    const handleSystemThemeChange = () => {
        const saved = getStoredAppearance() ?? 'system';
        updateTheme(saved);
    };

    const initializeTheme = () => {
        const saved = getStoredAppearance() ?? 'system';
        updateTheme(saved);
        mediaQuery().addEventListener('change', handleSystemThemeChange);
    };

    onMounted(() => {
        initializeTheme();
        const saved = getStoredAppearance();
        if (saved) {
            appearance.value = saved;
        }
    });

    function updateAppearance(value: Appearance) {
        appearance.value = value;
        localStorage.setItem('appearance', value);
        setCookie('appearance', value);
        updateTheme(value);
    }

    return {
        appearance,
        updateAppearance,
    };
}
