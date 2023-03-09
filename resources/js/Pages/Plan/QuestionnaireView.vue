<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('Plan.PlanListView')">
                    <reconciliation-outlined style="font-size:20px" />
                    Planų valdymas
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('Plan.ChooseAlternativeView')">
                    <gold-outlined style="font-size:20px" />
                    Alternatyvos pasirinkimas
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">Apklausa</a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h3 class="pb-2 text-xl">Atsakykite į klausimus:</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pl-4 pt-4 pb-2">
                        <p>{{ questions[0].question }}</p>
                        <a-radio-group v-model:value="Question1value">
                            <a-radio :style="radioStyle" :value="1">{{ Question1answers[0].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="2">{{ Question1answers[1].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="3">{{ Question1answers[2].answer }}</a-radio>
                        </a-radio-group>
                    </div>
                    <div class="pl-4 py-2">
                        <p>{{ questions[1].question }}</p>
                        <a-radio-group v-model:value="Question2value">
                            <a-radio :style="radioStyle" :value="1">{{ Question2answers[0].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="2">{{ Question2answers[1].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="3">{{ Question2answers[2].answer }}</a-radio>
                        </a-radio-group>
                    </div>
                    <div class="pl-4 py-2">
                        <p>{{ questions[2].question }}</p>
                        <a-radio-group v-model:value="Question3value">
                            <a-radio :style="radioStyle" :value="1">{{ Question3answers[0].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="2">{{ Question3answers[1].answer }}</a-radio>
                            <a-radio :style="radioStyle" :value="3">{{ Question3answers[2].answer }}</a-radio>
                        </a-radio-group>
                    </div>
                    <div class="flex justify-start pl-6 py-4">
                        <a-button v-if="Question1value && Question2value && Question3value" type="primary"
                            @click="handleNext">Toliau</a-button>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ReconciliationOutlined, HomeOutlined, GoldOutlined } from '@ant-design/icons-vue';
import { reactive, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const radioStyle = reactive({
  display: 'flex',
  height: '30px',
  lineHeight: '30px',
  padding: '0 10px',
});
const questions = reactive([
  { question: 'Klausimas 1' },
  { question: 'Klausimas 2' },
  { question: 'Klausimas 3' },
]);
const Question1answers = reactive([
  { answer: 'Pirmo klausimo pasirinkimas A' },
  { answer: 'Pirmo klausimo pasirinkimas B' },
  { answer: 'Pirmo klausimo pasirinkimas C' },
]);
const Question2answers = reactive([
  { answer: 'Antro Klausimo pasirinkimas A' },
  { answer: 'Antro Klausimo pasirinkimas B' },
  { answer: 'Antro Klausimo pasirinkimas C' },
]);
const Question3answers = reactive([
  { answer: 'Trečio klausimo pasirinkimas A' },
  { answer: 'Trečio klausimo pasirinkimas B' },
  { answer: 'Trečio klausimo pasirinkimas C' },
]);
const Question1value = ref();
const Question2value = ref();
const Question3value = ref();

function updateQuestionAndAnswers(questionIndex, selectedValue) {
  const question = questions[questionIndex];
  const answers = eval(`Question${questionIndex + 1}answers`);
  const newQuestion = `Pasirinktas ${String.fromCharCode(64 + selectedValue)}`;
  const newAnswers = [
    `Naujas ${question.question} pasirinkimas A`,
    `Naujas ${question.question} pasirinkimas B`,
    `Naujas ${question.question} pasirinkimas C`,
  ];

  question.question = newQuestion;
  answers.forEach((answer, index) => {
    answer.answer = newAnswers[index];
  });
}
function handleNext() {
  if (Question1value.value >= 1 && Question1value.value <= 3) {
    updateQuestionAndAnswers(0, Question1value.value);
    Question1value.value = 0;
  }

  if (Question2value.value >= 1 && Question2value.value <= 3) {
    updateQuestionAndAnswers(1, Question2value.value);
    Question2value.value = 0;
  }

  if (Question3value.value >= 1 && Question3value.value <= 3) {
    updateQuestionAndAnswers(2, Question3value.value);
    Question3value.value = 0;
  }
}

</script>
<style scoped></style>
