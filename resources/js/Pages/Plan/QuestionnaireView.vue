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
        <h3 class="pb-2 text-3xl text-sky-500 font-bold">Atsakykite į klausimus:</h3>
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
            <a-button @click="nextQuestion()" type="primary"
              :disabled="selectedAnswerValue[currentQuestionIndex] === undefined || selectedAnswerValue[currentQuestionIndex] === null">Toliau</a-button>
          </div>
        </div>
      </div>
    </div>
    <a-modal v-model:visible="visible" title="" footer="" :closable="false" :maskClosable="false">
      <div class="flex flex-col items-center justify-center p-2">
        <check-circle-filled style="font-size: 40px; color: #52c41a;" />
        <p class="mt-2">Apklausa baigta</p>
        <a-button @click="handleSubmit" type="primary" class="mt-4">Sugeneruoti planą</a-button>
      </div>
    </a-modal>
    <a-modal v-model:visible="visible2" title="" footer="" :closable="false" :maskClosable="false">
      <div class="flex flex-col items-center justify-center p-2">
        <check-circle-filled style="font-size: 40px; color: #52c41a;" />
        <p class="mt-2">Apklausa baigta</p>
        <p class="mt-2 text-center">Jūsų apklausos rezultatai rodo, kad jūsų turimi įpročiai padengia visus svarbiausius gyvenimo rato
          aspektus</p>
        <p class="mt-2">Jums galime pasiūlyti susikurti planą nuo nulio</p>
        <Link :href="route('Plan.CustomView')">
        <a-button type="primary">Kurti planą nuo nulio</a-button>
        </Link>
        <p class="mt-3">Arba pasirinkti kitą alternatyvą</p>
        <Link :href="route('Plan.ChooseAlternativeView')">
        <a-button type="primary">Rinktis kitą alternatyvą</a-button>
        </Link>
      </div>
    </a-modal>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import {
  ReconciliationOutlined, HomeOutlined, GoldOutlined, CheckCircleFilled,
} from '@ant-design/icons-vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const currentQuestionIndex = ref(0);
const visible = ref(false);
const visible2 = ref(false);
const selectedAnswerValue = ref([
]);
const questions = [
  {
    text: 'Kiek kartų per savaitę dalyvaujate fizinėje veikloje ar sporte?',
    answers: [
      { text: 'Niekuomet', value: 0 },
      { text: 'Retkarčiais (1-2 kartus per savaitę)', value: 1 },
      { text: 'Kartais (3-4 kartus per savaitę)', value: 2 },
      { text: 'Dažnai (5 ar daugiau kartų per savaitę)', value: 3 },
    ],
  },
  {
    text: 'Kiek laiko per dieną skiriate mokantis ar bandydamas mokytis kažko naujo?',
    answers: [
      { text: 'Niekada', value: 0 },
      { text: 'Mažiau nei 1 valandą per dieną', value: 1 },
      { text: '1-2 valandas per dieną', value: 2 },
      { text: 'Daugiau nei 2 valandas per dieną', value: 3 },
    ],
  },
  {
    text: 'Kiek dažnai bendraujate su draugais ar šeimos nariais?',
    answers: [
      { text: 'Niekuomet', value: 0 },
      { text: 'Retkarčiais (kartą per savaitę ar rečiau)', value: 1 },
      { text: 'Kartais (kelis kartus per savaitę)', value: 2 },
      { text: 'Dažnai (beveik kiekvieną dieną)', value: 3 },
    ],
  },
  {
    text: 'Kiek dažnai laisvalaikiu užsiimate savo pomėgiais ar susidomėjimais?',
    answers: [
      { text: 'Niekuomet', value: 0 },
      { text: 'Retkarčiais (kartą per savaitę ar rečiau)', value: 1 },
      { text: 'Kartais (kelis kartus per savaitę)', value: 2 },
      { text: 'Dažnai (beveik kiekvieną dieną)', value: 3 },
    ],
  },
  {
    text: 'Kaip dažnai valgote sveiką maistą?',
    answers: [
      { text: 'Niekuomet', value: 0 },
      { text: 'Retkarčiais (kartą per savaitę ar rečiau)', value: 1 },
      { text: 'Kartais (kelis kartus per savaitę)', value: 2 },
      { text: 'Dažnai (beveik kiekvieną dieną)', value: 3 },
    ],
  },
];
const allValuesGreaterThanOne = ref(false);
const nextQuestion = () => {
  if (currentQuestionIndex.value === questions.length - 1) {
    allValuesGreaterThanOne.value = true;
    for (let i = 0; i < selectedAnswerValue.value.length; i += 1) {
      if (selectedAnswerValue.value[i] <= 1) {
        allValuesGreaterThanOne.value = false;
        break;
      }
    }
    if (allValuesGreaterThanOne.value) {
      visible2.value = true;
    } else {
      visible.value = true;
    }
  } else {
    currentQuestionIndex.value += 1;
  }
};
const handleSubmit = () => {
  router.post(
    '/plans/questionnaire',
    {
      selectedAnswerValue,
    },
    {
      preserveScroll: true,
    },
  );
};
</script>
<style></style>
