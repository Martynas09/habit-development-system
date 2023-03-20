<template>
  <Head title="Apklausa" />

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
          <div class="questions-container">
            <div class="question text-2xl font-bold m-4">
              {{ questions[currentQuestionIndex].text }}
            </div>
          </div>
          <div class="flex flex-col m-4 gap-4">
            <a-radio-group v-model:value="selectedAnswerValue[currentQuestionIndex]"
              v-for="(answer) in questions[currentQuestionIndex].answers" :key="answer.key">
              <a-radio :value="answer.value"><span class="text-lg">{{ answer.text }}</span></a-radio>
            </a-radio-group>
          </div>
          <div class="m-4">
            <a-button @click="nextQuestion()" type="primary">Toliau</a-button>
          </div>
        </div>
      </div>
    </div>
    <a-modal v-model:visible="visible" title="" footer="" :closable="false" :maskClosable="false">
              <div class="flex flex-col items-center justify-center p-2">
                <check-circle-filled style="font-size: 40px; color: #52c41a;" />
                <p class="mt-2">Apklausa baigta</p>
                <Link :href="route('Schedule')">
                <a-button type="primary" class="mt-4">Peržiūrėti
                  sugeneruotą planą</a-button>
                </Link>
              </div>
            </a-modal>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
  ReconciliationOutlined, HomeOutlined, GoldOutlined, CheckCircleFilled,
} from '@ant-design/icons-vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const currentQuestionIndex = ref(0);
const visible = ref(false);
const selectedAnswerValue = ref([
]);
const questions = [
  {
    text: 'Kaip dažnai per savaitę mankštinatės?',
    answers: [
      { text: 'Rečiau nei kartą per savaitę', value: 1 },
      { text: '1-2 kartus per savaitę', value: 2 },
      { text: '3-4 kartus per savaitę ir daugiau', value: 3 },
    ],
  },
  {
    text: 'Kaip dažnai valgote vaisius ir daržoves?',
    answers: [
      { text: 'Retai arba niekada', value: 1 },
      { text: '1-2 kartus per dieną', value: 2 },
      { text: '3-4 kartus per dieną', value: 3 },
    ],
  },
  {
    text: 'Kaip dažnai užsiimate veikla, kuri jums teikia džiaugsmo ar atpalaiduoja, pavyzdžiui, hobiu ar meditacija?',
    answers: [
      { text: 'Retai arba niekada', value: 1 },
      { text: '1-2 kartus per savaitę', value: 2 },
      { text: '3 ar daugiau kartų per savaitę', value: 3 },
    ],
  },
  {
    text: 'Kaip dažnai dalyvaujate socialinėje veikloje su draugais ar šeima?',
    answers: [
      { text: 'Retai arba niekada', value: 1 },
      { text: '1-2 kartus per savaitę', value: 2 },
      { text: '3 ar daugiau kartų per savaitę', value: 3 },
    ],
  },
];
const nextQuestion = () => {
  if (currentQuestionIndex.value === questions.length - 1) {
    visible.value = true;
  } else {
    currentQuestionIndex.value += 1;
  }
};
</script>
<style></style>
