<template>
    <v-container class="fill-height d-flex flex-column align-center pa-4 justify-center text-center">
        <v-icon icon="mdi-map-marker-off" size="100" color="red" class="mb-4" />
        <h1 class="text-h3 font-weight-bold mb-4" style="color: #ff3d00">404 - Страница не найдена</h1>
        <p class="text-subtitle-1 mb-6" style="color: #555">
            Ой! Похоже, мы не можем найти то, что вы ищете. Возможно, страница была перемещена или удалена.
        </p>
        <div class="d-flex flex-wrap justify-center gap-4">
            <Link href="/dashboard" class="text-decoration-none">
                <v-btn color="primary" variant="outlined" class="ma-2">
                    <v-icon icon="mdi-home" start />
                    На главную
                </v-btn>
            </Link>
            <v-btn color="secondary" variant="outlined" class="ma-2" @click="$inertia.reload()">
                <v-icon icon="mdi-refresh" start />
                Попробовать снова
            </v-btn>
        </div>
    </v-container>
</template>

<script lang="ts" setup>
import { Link } from '@inertiajs/vue3';
</script>

<style scoped>
.v-container {
    background-color: #f8f8f8;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 40px;
    max-width: 800px;
    margin: 0 auto;
    min-height: 70vh;
}

.v-btn {
    font-weight: bold;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    min-width: 220px;
    height: 48px;
    border-width: 2px;
}

.v-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.v-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

@media (max-width: 600px) {
    .v-container {
        padding: 24px;
        min-height: 60vh;
    }

    h1 {
        font-size: 1.8rem;
    }

    .v-btn {
        min-width: 100%;
        margin: 8px 0;
    }
}
</style>
