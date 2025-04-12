<script setup lang="ts">
defineProps<{
    message?: string;
}>();

const translateError = (error?: string): string => {
    if (!error) return '';

    const errorMap: Record<string, string> = {
        'The email field is required.': 'Email обязателен для заполнения',
        'The email must be a valid email address.': 'Введите корректный email',
        'The password field is required.': 'Пароль обязателен для заполнения',
        'These credentials do not match our records.': 'Неверные учетные данные',
        'A reset link will be sent if the account exists.': 'Если учетная запись существует, вам будет отправлена ​​ссылка для сброса пароля.',
    };

    return errorMap[error] || error;
};
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-red-600 dark:text-red-500">
            {{ translateError(message) }}
        </p>
    </div>
</template>
