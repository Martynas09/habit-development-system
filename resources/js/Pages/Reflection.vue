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
        <a-breadcrumb-item class="text-xl">Refleksija</a-breadcrumb-item>
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
            <a-button v-if="selectedAnswerValue[currentQuestionIndex] !== undefined" @click="nextQuestion()"
              type="primary">Toliau</a-button>
          </div>
        </div>
      </div>
    </div>
    <a-modal v-model:visible="visible" title="" footer="" :closable="false" :maskClosable="false">
      <div class="flex flex-col items-center justify-center p-2">
        <check-circle-filled style="font-size: 40px; color: #52c41a;" />
        <p class="mt-2">Refleksija baigta</p>
        <p class="text-lg font-bold text-center">{{ suggestion }}</p>
        <p>Citata motyvacijai palaikyti:</p>
        <p>{{ quote }}</p>
        <a-button @click="finish()" type="primary" class="mt-1">Grįžti į tvarkaraštį</a-button>
      </div>
    </a-modal>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import {
  HomeOutlined, CheckCircleFilled,
} from '@ant-design/icons-vue';
import { ref, onBeforeMount, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ questionData: Object, answerData: Object, planData: Object });
const questions = ref([]);
const suggestion = ref();
onBeforeMount(() => {
  questions.value = [
    ...props.questionData.map((q) => {
      const answers = props.answerData
        .filter((a) => a.fk_question === q.id)
        .map((a) => ({ text: a.content, value: a.value }));
      return { text: q.content, answers };
    }),
    ...props.planData.map((q) => {
      const answers = [
        { text: 'Taip', value: 0 },
        { text: 'Ne', value: 0 },
      ];
      return { text: `Ar planas '${q.title}' jau užbaigtas?`, answers };
    }),
  ];
});
const currentQuestionIndex = ref(0);
const visible = ref(false);
const selectedAnswerValue = ref([]);
const quote = ref('');
async function getapi(url) {
  const response = await fetch(url);
  const data = await response.json();
  quote.value = data.quote;
}
onMounted(async () => {
  await getapi('https://iq.orm.ovh/');
  console.log(props.planData.length);
});
const nextQuestion = () => {
  if (currentQuestionIndex.value === questions.value.length - 1) {
    const modifiedArray = [...selectedAnswerValue.value.slice(0, -2)];
    console.log(selectedAnswerValue.value);
    console.log(modifiedArray.value);
    const total = selectedAnswerValue.value.reduce((a, b) => a + b, 0);
    if (total > 0) {
      suggestion.value = 'Planų progresas yra labai geras';
    }
    if (total === 0) {
      suggestion.value = 'Planų progresas yra vidutinis';
    }
    if (total < 0) {
      suggestion.value = 'Planų progresas yra prastas, siūlome paredaguoti savo planus';
    }
    visible.value = true;
  } else {
    currentQuestionIndex.value += 1;
  }
};
const finish = () => {
  router.post(
    '/reflection',
    {
      planAnswers: selectedAnswerValue.value.slice(-props.answerData.length),
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success(),
      onError: () => message.error(),
    },
  );
};
</script>
<style></style>
