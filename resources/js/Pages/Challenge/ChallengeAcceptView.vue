<template>
  <Head title="Iššūkiai" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item href="" class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <Link :href="route('Challenge.ChallengesListView')">
          <dashboard-outlined style="font-size:20px" />
          Iššūkiai
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">Iššūkio priimimas</a-breadcrumb-item>
      </a-breadcrumb>
    </template>
    <div class="pt-12 pb-12">
      <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="m-6">
            <p class="text-lg font-bold text-lime-500">{{ challenge.title }}</p>
            <div class="text-lg font-bold">Iššūkis per savaitę pasikartoja {{ challenge.timesPerWeek }} kartus</div>
            <a-form-item style="margin-top:0px;margin-bottom:0px" name="receivers"
              :label="'Pasirinkite vykdymo laiką ir ' + challenge.timesPerWeek + ' savaitės dienas'"
              :rules="[{ required: true }]"></a-form-item>
            <a-form>
              <div class="flex item-center">
                <a-form-item style="margin-top:0px;margin-bottom:10px" class="flex"><a-time-picker
                    style="width: 25%; margin-bottom:7px;margin-top:7px;margin-right:5px" format="HH:mm"
                    placeholder="Laikas" v-model:value="time" />
                  <a-select v-model:value="days" mode="multiple" style="width: 250px" placeholder="Savaitės dienos">
                    <a-select-option value="monday">Pirmadienis</a-select-option>
                    <a-select-option value="tuesday">Antradienis</a-select-option>
                    <a-select-option value="wednesday">Trečiadienis</a-select-option>
                    <a-select-option value="thursday">Ketvirtadienis</a-select-option>
                    <a-select-option value="friday">Penktadienis</a-select-option>
                    <a-select-option value="saturday">Šeštadienis</a-select-option>
                    <a-select-option value="sunday">Sekmadienis</a-select-option>
                  </a-select>
                </a-form-item>
              </div>
              <a-alert v-if="!daysValidation && days.length!==0" :message="`Pasirinkite ${challenge.timesPerWeek} dienas`" type="error" show-icon/>
              <div><a-form-item style="margin-top:0px;margin-bottom:0px" name="category" label="Priminimai"
                  :rules="[{ required: true }]"></a-form-item></div>
              <a-select v-model:value="reminder" style="width: 250px" placeholder="Pasirinkite">
                <a-select-option value="self">Savarankiški</a-select-option>
                <a-select-option value="system">Sisteminiai (<span
                    class="text-sm text-green-500">rekomenduojama</span>)</a-select-option>
              </a-select>
              <div class="flex justify-end my-6 mr-4">
                <a-button type="primary" @click="acceptChallenge" :disabled="!daysValidation">Priimti
                  iššūkį<send-outlined /></a-button>
              </div>
            </a-form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { DashboardOutlined, HomeOutlined, SendOutlined } from '@ant-design/icons-vue';
import {
  defineProps, ref, onMounted, computed,
} from 'vue';
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ challenge: Object });
const title = ref('');
const duration = ref();
const days = ref([]);
const time = ref();
const reminder = ref();
const daysValidation = computed(() => {
  if (days.value.length === props.challenge.timesPerWeek) {
    return true;
  }
  return false;
});

onMounted(() => {
  title.value = props.challenge.title;
  duration.value = props.challenge.duration;
});

const form = useForm({
  title,
  duration,
  days,
  time,
  reminder,
});
const acceptChallenge = () => {
  form.post(
    route('Challenge.ChallengeAcceptView', props.challenge.id),
    {
      preserveScroll: true,
      onSuccess: () => message.success('Iššūkis sėkmingai priimtas'),
      onError: () => message.error('Klaida priimant iššūkį'),
    },
  );
};
</script>
<style></style>
