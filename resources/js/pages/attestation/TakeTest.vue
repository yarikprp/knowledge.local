<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps<{
    test: {
        id: number;
        name: string;
        time_limit: number;
        questions: {
            id: number;
            question_text: string;
            options: {
                id: number;
                text: string;
            }[];
        }[];
    };
}>();

const form = useForm({
    answers: props.test.questions.map((q) => ({
        question_id: q.id,
        selected_option_id: null,
        text: '',
    })),
});

const timeLeft = ref(props.test.time_limit * 60);
let timerInterval: number | null = null;

const formatTime = (seconds: number): string => {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
};

const submit = () => {
    if (!form.processing) {
        const confirmed = window.confirm('Точно ли вы хотите завершить тест?');
        if (confirmed) {
            form.post(route('attestation.submit', props.test.id));
        }
    }
};

const handleVisibilityChange = () => {
    if (document.hidden) {
        clearInterval(timerInterval!);
        submit();
    }
};

onMounted(() => {
    timerInterval = window.setInterval(() => {
        if (timeLeft.value > 0) {
            timeLeft.value--;
        } else {
            clearInterval(timerInterval!);
            submit();
        }
    }, 1000);

    document.addEventListener('visibilitychange', handleVisibilityChange);
});

onBeforeUnmount(() => {
    if (timerInterval) clearInterval(timerInterval);
    document.removeEventListener('visibilitychange', handleVisibilityChange);
});
</script>

<template>
    <Head title="Аттестация" />

    <v-container>
        <div class="d-flex justify-space-between align-center mb-4">
            <h1 class="text-2xl">{{ test.name }}</h1>
            <div class="text-red text-xl font-bold">⏳ {{ formatTime(timeLeft) }}</div>
        </div>

        <div v-for="(question, index) in test.questions" :key="question.id" class="mb-6">
            <p class="font-bold">{{ index + 1 }}. {{ question.question_text }}</p>

            <div v-if="question.options.length">
                <v-radio-group v-model="form.answers[index].selected_option_id">
                    <v-radio v-for="option in question.options" :key="option.id" :label="option.text" :value="option.id" />
                </v-radio-group>
            </div>

            <div v-else>
                <v-text-field v-model="form.answers[index].text" label="Ваш ответ" />
            </div>
        </div>

        <v-btn color="primary" @click="submit" :loading="form.processing"> Завершить тест </v-btn>
    </v-container>
</template>
