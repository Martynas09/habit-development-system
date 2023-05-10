<template>
  <Head title="Tvarkaraštis" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item href="" class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <calendar-outlined style="font-size:20px" />
          Tvarkaraštis</a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <p class="pt-2 pl-3 text-lg">Dienos citata: <span class="text-xl">"{{ quote }}"</span></p>
          <h3 v-if="plan.length>0" class="m-3">Aktyvūs planai:</h3>
          <h3 v-else class="m-3">Neturite aktyvių planų</h3>
          <div class="flex">
            <div v-for="planName in plan" :key="planName.title">
              <div class="pl-3 w-fit">
                <p class="px-2 rounded-sm" :style="{ backgroundColor: planName.color }">{{ planName.title }}</p>
              </div>
            </div>
          </div>
          <div style="border: 1px solid #d9d9d9; border-radius: 4px; margin: 16px ">
            <a-config-provider :locale="ltLT">
              <a-calendar v-model:value="value">
                <template #dateCellRender="{ current }">
                  <ul class="events">
                    <li v-for="item in   getListData(current)  " :key="item.content">
                      <a-popover trigger="click">
                        <template #title>
                          <span class="font-bold">{{ text }}</span>
                        </template>
                        <template #content>
                          <p class="font-semibold p-0 m-0">Atlikimo laikas:</p>
                          <p class="pb-1 m-0">{{ starttime }}-{{ endtime }}</p>
                          <p :class="status === 'atlikta' ? 'text-green-600' : 'text-red-600'">Užduotis {{ status }}</p>
                          <a-button v-if="status === 'neatlikta' && visible === true" type="primary" size="small"
                            class="w-full" @click="handleTaskDone(); taskDone()">Pažymėti atlikta</a-button>
                        </template>
                        <div class="pb-1">
                          <div class="rounded-sm max-h-[22px] pl-1" :style=" { backgroundColor: item.color } ">
                            <a-badge @click= handleClick(item)  :status=" item.type "
                              :text=" dayjs(item.time).format('HH:mm') + ' ' + item.content " />
                          </div>
                        </div>
                      </a-popover>
                    </li>
                  </ul>
                </template>
                <template #monthCellRender=" { current } ">
                  <div v-if=" getMonthData(current)[0] " class="notes-month">
                    <p class="text-sm p-0 m-0">Išviso užduočių: {{ getMonthData(current)[0] }}</p>
                    <p class="text-sm p-0 m-0 text-green-600">Atlikta: {{ getMonthData(current)[1] }}</p>
                    <p class="text-sm p-0 m-0 text-red-600">Neatlikta: {{ getMonthData(current)[2] }}</p>
                  </div>
                  <div v-else class="notes-month">
                    <span class="text-sm p-0 m-0">Nėra užduočių</span>
                  </div>
                </template>
              </a-calendar>
            </a-config-provider>
            <a-modal v-model:visible=" visibleReflection " title='Refleksija' okText="Atlikti"
              cancelText="Priminti vėliau" @ok=" handleOk " @cancel=" handleCancel " :closable=" false "
              :maskClosable=" false ">
              Praėjo savaitė laiko nuo paskutinės refleksijos. Ar norite atlikti refleksiją?
            </a-modal>
          </div>
          <div class="flex flex-row-reverse mr-4 mb-2">
          <a-button type="primary" @click="exportCalendar">Ekportuoti tvarkaraštį</a-button>
        </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
<script setup>
import {
  Head, Link, router,
} from '@inertiajs/vue3';
import { HomeOutlined, CalendarOutlined } from '@ant-design/icons-vue';
import { ref, onMounted, h } from 'vue';
import dayjs from 'dayjs';
import { message, Modal } from 'ant-design-vue';
import ltLT from 'ant-design-vue/es/locale/lt_LT';
import axios from 'axios';
import relativeTime from 'dayjs/plugin/relativeTime';
import { createEvent, createEvents } from 'ics';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import 'dayjs/locale/lt';
import useExperience from '../Composables/useExperience';

dayjs.extend(relativeTime);
dayjs.locale('lt');
const props = defineProps({ plan: Object, tasks: Object, oneWeekPassed: Boolean });
const value = ref();
const starttime = ref();
const endtime = ref();
const text = ref();
const status = ref();
const visible = ref(false);
const visibleReflection = ref(false);
const taskID = ref();
const { pullXP } = useExperience();
// const disabledDate = (current) => current && current < dayjs().endOf('day');
const quote = ref('');

async function getapi(url) {
  const response = await fetch(url);
  const data = await response.json();
  quote.value = data.quote;
}
onMounted(async () => {
  await getapi('https://iq.orm.ovh/');
  const cancelTimestamp = parseInt(localStorage.getItem('cancelTimestamp'), 10);
  if (props.oneWeekPassed === true) {
    if (!isNaN(cancelTimestamp)) {
      const now = Date.now();
      const timeDiff = now - cancelTimestamp;
      const fifteenMinutesInMs = 15 * 60 * 1000;
      if (timeDiff >= fifteenMinutesInMs) {
        visibleReflection.value = true;
        localStorage.removeItem('cancelTimestamp');
      }
    } else {
      visibleReflection.value = true;
    }
  }
});

function getListData(current) {
  const events = [];
  const colors = [];
  props.plan.forEach((plan) => {
    colors[plan.id] = plan.color;
  });
  props.tasks.forEach((task) => {
    if (dayjs(task.execution_date).format('D') === dayjs(current).format('D') && dayjs(task.execution_date).format('M') === dayjs(current).format('M')) {
      if (task.is_done === 1) {
        events.push({
          id: task.id,
          type: 'success',
          content: task.get_task.title,
          time: task.execution_date,
          duration: task.get_task.duration,
          color: colors[task.fk_plan],
        });
      } else {
        events.push({
          id: task.id,
          type: 'error',
          content: task.get_task.title,
          time: task.execution_date,
          duration: task.get_task.duration,
          color: colors[task.fk_plan],
        });
      }
    }
  });
  return events;
}

function handleClick(item) {
  starttime.value = dayjs(item.time).format('HH:mm');
  endtime.value = dayjs(item.time).add(item.duration, 'minute').format('HH:mm');
  text.value = item.content;
  if (item.type === 'success') {
    status.value = 'atlikta';
  } else {
    status.value = 'neatlikta';
  }
  taskID.value = item.id;
  // if endtime is less than current time, then show button
  if (dayjs(item.time).add(item.duration, 'minute').isBefore(dayjs())) {
    visible.value = true;
  } else {
    visible.value = false;
  }
}
const handleOk = () => {
  visibleReflection.value = false;
  router.visit('reflection');
};
const handleCancel = () => {
  visibleReflection.value = false;
  const timestamp = Date.now();
  localStorage.setItem('cancelTimestamp', timestamp);
};
function getMonthData(current) {
  let total = 0;
  let done = 0;
  let undone = 0;
  props.plan.forEach((plan) => {
    plan.get_tasks.forEach((task) => {
      if (dayjs(task.execution_date).format('M') === dayjs(current).format('M')) {
        total += 1;
        if (task.is_done === 1) {
          done += 1;
        } else {
          undone += 1;
        }
      }
    });
  });
  return [total, done, undone];
}
function prizeCheck() {
  axios.get('/api/isPrize', {
    params: {
      id: taskID.value,
    },
  })
    .then((response) => {
      if (response.data.title !== undefined) {
        Modal.success({
          title: 'Priminimas',
          content: h('div', {}, [h('p', 'Jums priklauso prizas:'), h('p', response.data.title)]),
        });
      }
    })
    .catch((error) => {
      console.log(error);
    });
}
function achievementCheck() {
  axios.get('/api/isAchievement', {
  })
    .then((response) => {
      if (response.data !== undefined) {
        Modal.success({
          title: 'Naujas pasiekimas',
          content: h('div', {}, [h('p', response.data)]),
        });
      }
    })
    .catch((error) => {
      console.log(error);
    });
}
const taskDone = () => {
  router.post(
    'schedule',
    {
      id: taskID.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        message.success('Užduotis pažymėta kaip atlikta', 1).then(() => message.info('Gavote 5 patirties taškus!', 2.5)); prizeCheck(); achievementCheck(); pullXP();
      },
      onError: () => message.error('Klaida pažymėjant užduotį kaip atliktą'),
    },
  );
};

function handleTaskDone() {
  visible.value = false;
  status.value = 'atlikta';
}
function exportCalendar() {
  const temp = [];
  props.tasks.forEach((task) => {
    temp.push({
      title: task.get_task.title,
      start: [parseInt(dayjs(task.execution_date).format('YYYY'), 10), parseInt(dayjs(task.execution_date).format('MM'), 10), parseInt(dayjs(task.execution_date).format('DD'), 10), parseInt(dayjs(task.execution_date).format('HH'), 10), parseInt(dayjs(task.execution_date).format('mm'), 10)],
      duration: { minutes: task.get_task.duration },
    });
  });

  const calendarData = createEvents(temp);

  const blob = new Blob([calendarData.value], { type: 'text/calendar' });
  const url = URL.createObjectURL(blob);

  const link = document.createElement('a');
  link.href = url;
  link.download = 'Kalendorius.ics';
  link.click();

  // Clean up the URL object
  URL.revokeObjectURL(url);
}
</script>
<style scoped>
.events {
  list-style: none;
  margin: 0;
  padding: 0;
}

.events .ant-badge-status {
  overflow: hidden;
  white-space: nowrap;
  width: 100%;
  text-overflow: ellipsis;
  font-size: 12px;
}

.notes-month {
  text-align: center;
  font-size: 28px;
}

.notes-month section {
  font-size: 28px;
}</style>
