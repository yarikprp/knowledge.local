<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, defineEmits, defineProps, ref, watch } from 'vue';

interface RegisterForm {
    id?: number;
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
}

const props = defineProps<{
    modelValue: boolean;
    user?: RegisterForm;
}>();

const emit = defineEmits(['update:modelValue', 'saved']);
const dialog = ref(props.modelValue);
const isLoading = ref(false);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);
const passwordGeneratorDialog = ref(false);
const generatedPasswords = ref<string[]>([]);

function generatePasswords() {
    generatedPasswords.value = Array.from({ length: 5 }, () => generateRandomPassword());
}

function generateRandomPassword(length = 10) {
    const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    const password = Array.from({ length }, () => chars[Math.floor(Math.random() * chars.length)]).join('');

    if (!/[A-Z]/.test(password) || !/[0-9]/.test(password) || !/[!@#$%^&*()_+]/.test(password)) {
        return generateRandomPassword(length); // Recurse until a valid password is generated
    }
    return password;
}

function selectGeneratedPassword(password: string) {
    form.password = password;
    form.password_confirmation = password;
    passwordGeneratorDialog.value = false;
}

function openPasswordGenerator() {
    generatePasswords();
    passwordGeneratorDialog.value = true;
}

const form = useForm<RegisterForm>({
    id: props.user?.id,
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    password_confirmation: '',
});

watch(
    () => props.modelValue,
    (value) => {
        dialog.value = value;
        if (!value) {
            form.reset();
        } else if (props.user) {
            form.id = props.user.id;
            form.name = props.user.name;
            form.email = props.user.email;
            form.password = '';
            form.password_confirmation = '';
        }
    },
);

watch(dialog, (value) => {
    emit('update:modelValue', value);
});

const validationErrors = computed(() => {
    const errors: Partial<Record<keyof RegisterForm, string>> = {};

    if (!form.name || form.name.trim() === '') {
        errors.name = 'Имя обязательно';
    } else if (form.name.length > 255) {
        errors.name = 'Имя не должно превышать 255 символов';
    }

    if (!form.email || form.email.trim() === '') {
        errors.email = 'Email обязателен';
    } else if (!/^\S+@\S+\.\S+$/.test(form.email)) {
        errors.email = 'Некорректный формат email';
    }

    if (!form.id) {
        if (!form.password) {
            errors.password = 'Пароль обязателен';
        } else if (form.password.length < 8) {
            errors.password = 'Пароль должен быть не менее 8 символов';
        }
    } else {
        if (form.password && form.password.length < 8) {
            errors.password = 'Пароль должен быть не менее 8 символов';
        }
    }

    if (form.password && form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Пароли не совпадают';
    }

    return errors;
});

const hasErrors = computed(() => Object.keys(validationErrors.value).length > 0);

const submit = () => {
    if (hasErrors.value) {
        form.errors = validationErrors.value as any;
        return;
    }

    isLoading.value = true;

    if (form.id) {
        form.put(`/user/${form.id}`, {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        });
    } else {
        form.post('/user', {
            onSuccess: () => {
                emit('saved');
                dialog.value = false;
                isLoading.value = false;
            },
            onError: () => {
                isLoading.value = false;
            },
        });
    }
};
</script>

<template>
    <v-dialog v-model="dialog" max-width="600px" persistent>
        <v-card>
            <v-card-title>
                {{ form.id ? 'Редактирование пользователя' : 'Регистрация пользователя' }}
            </v-card-title>

            <v-card-text>
                <v-text-field
                    v-model="form.name"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Имя"
                    :error-messages="form.errors.name"
                    required
                />
                <v-text-field
                    v-model="form.email"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Email"
                    :error-messages="form.errors.email"
                    required
                />

                <v-text-field
                    v-model="form.password"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Новый пароль"
                    :type="showPassword ? 'text' : 'password'"
                    :error-messages="form.errors.password"
                    :required="!form.id"
                >
                    <template #append-inner>
                        <v-icon @click="showPassword = !showPassword" class="mr-2" :icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'" />
                        <v-icon @click="openPasswordGenerator" icon="mdi-dice-multiple" />
                    </template>
                </v-text-field>

                <v-text-field
                    v-if="form.password"
                    v-model="form.password_confirmation"
                    :loading="isLoading"
                    :disabled="isLoading"
                    label="Подтвердите новый пароль"
                    :type="showPasswordConfirmation ? 'text' : 'password'"
                    :error-messages="form.errors.password_confirmation"
                    required
                    :append-inner-icon="showPasswordConfirmation ? 'mdi-eye-off' : 'mdi-eye'"
                    @click:append-inner="showPasswordConfirmation = !showPasswordConfirmation"
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />
                <v-btn :loading="isLoading" :disabled="isLoading" color="grey" text @click="dialog = false">Отмена</v-btn>
                <v-btn :loading="isLoading && form.processing" :disabled="isLoading" color="primary" @click="submit">
                    {{ form.id ? 'Сохранить' : 'Зарегистрировать' }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="passwordGeneratorDialog" max-width="400">
        <v-card>
            <v-card-title class="text-h6">Выберите сгенерированный пароль</v-card-title>
            <v-card-text>
                <v-list>
                    <v-list-item
                        v-for="(password, index) in generatedPasswords"
                        :key="index"
                        @click="selectGeneratedPassword(password)"
                        class="cursor-pointer"
                    >
                        <v-list-item-title>{{ password }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn text color="grey" @click="passwordGeneratorDialog = false">Закрыть</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
