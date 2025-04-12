<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const translateError = (error: string): string => {
    const errorMap: Record<string, string> = {
        'The email field is required.': 'Поле email обязательно для заполнения',
        'The email must be a valid email address.': 'Введите корректный email адрес',
        'The password field is required.': 'Поле пароль обязательно для заполнения',
        'These credentials do not match our records.': 'Неверный email или пароль',
    };
    return errorMap[error] || error;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors) => {
            Object.values(errors).forEach(error => {
                toast.error(translateError(error));
            });
        },
    });
};
</script>

<template>
    <AuthBase title="Войдите в свою учетную запись" description="Введите свой адрес электронной почты и пароль ниже, чтобы войти в систему">
        <Head title="Войти" />

        <Toaster position="top-center" richColors />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Почта</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        v-model="form.email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="translateError(form.errors.email)" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Пароль</Label>
                        <TextLink v-if="canResetPassword" :href="route('password.request')" class="text-sm" :tabindex="5">
                            Забыли пароль?
                        </TextLink>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        v-model="form.password"
                        placeholder="!dwkjs1"
                    />
                    <InputError :message="translateError(form.errors.password)" />
                </div>

                <div class="flex items-center justify-between" :tabindex="3">
                    <Label for="remember" class="flex items-center space-x-3">
                        <Checkbox id="remember" v-model="form.remember" :tabindex="4" />
                        <span>Запомнить меня</span>
                    </Label>
                </div>

                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Войти
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Нет аккаунта?
                <TextLink :href="route('register')" :tabindex="5">Зарегистрируйтесь</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
