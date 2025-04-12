<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <Head title="Добро пожаловать">
            <link rel="preconnect" href="https://rsms.me/" />
            <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
        </Head>

        <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
            <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
                <nav class="flex items-center justify-end gap-4">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Новости
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                        >
                            Войти
                        </Link>
                        <Link
                            :href="route('register')"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                        >
                            Регистрация
                        </Link>
                    </template>
                </nav>
            </header>

            <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                <main class="flex w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                    <div class="w-full rounded-xl bg-white p-8 text-center shadow-lg dark:bg-[#1a1a1a]">
                        <h1 class="mb-4 text-2xl font-bold lg:text-4xl">Добро пожаловать в Систему Знаний</h1>
                        <p class="mb-6 text-base text-gray-700 lg:text-lg dark:text-gray-300">
                            Исследуйте, учитесь и управляйте своими учебными материалами легко и эффективно.
                        </p>

                        <div>
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="rounded-md bg-[#1b1b18] px-6 py-2 text-sm text-white hover:opacity-90 dark:bg-[#EDEDEC] dark:text-[#1a1a1a]"
                            >
                                Перейти к обучению
                            </Link>
                            <template v-else>
                                <Link
                                    :href="route('register')"
                                    class="rounded-md bg-[#1b1b18] px-6 py-2 text-sm text-white hover:opacity-90 dark:bg-[#EDEDEC] dark:text-[#1a1a1a]"
                                >
                                    Начать обучение
                                </Link>
                            </template>
                        </div>
                    </div>
                </main>
            </div>

            <div class="hidden h-14.5 lg:block"></div>
        </div>
    </div>
</template>
