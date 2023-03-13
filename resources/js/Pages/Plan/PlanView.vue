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
        <a-breadcrumb-item class="text-xl">Planas "{{ plan.title }}"</a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <h3 :style="{ margin: '16px' }">Planas:</h3>
          <div style="border: 1px solid #d9d9d9; border-radius: 4px; margin: 16px ">
            <a-config-provider :locale="ltLT">
            <a-calendar v-model:value="value">
              <template #dateCellRender="{ current }">
                <ul class="events">
                  <li v-for="item in getListData(current)" :key="item.content">
                    <a-popover trigger="click">
                      <template #title>
                        <span class="font-bold">{{text}}</span>
                      </template>
                      <template #content>
                        <p class="font-semibold p-0 m-0">Atlikimo laikas:</p>
                        <p class="pb-1 m-0">{{ starttime }}-{{ endtime }}</p>
                        <p :class="status === 'atlikta' ? 'text-green-600' : 'text-red-600'">Užduotis {{ status }}</p>
                        <a-button v-if="status==='neatlikta'&& visible===true" type="primary" size="small" class="w-full" @click="handleTaskDone();taskDone()">Pažymėti atlikta</a-button>
                      </template>
                      <a-badge @click=handleClick(item) :status="item.type"
                        :text="dayjs(item.time).format('HH:mm') + ' ' + item.content" />
                    </a-popover>
                  </li>
                </ul>
              </template>
              <template #monthCellRender="{ current }">
                <div v-if="getMonthData(current)[0]" class="notes-month">
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
            <!-- <a-modal v-model:visible="visible" :title='text' okText="Uždaryti" @ok="handleOk">
              <p>Atlikimo laikas: {{ time }}</p>
              <p>Užduotis {{ status }}</p>
              <template #footer>
              </template>
            </a-modal> -->
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ReconciliationOutlined, HomeOutlined } from '@ant-design/icons-vue';
import { ref } from 'vue';
import dayjs from 'dayjs';
import { message } from 'ant-design-vue';
import ltLT from 'ant-design-vue/es/locale/lt_LT';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import 'dayjs/locale/lt';

const props = defineProps({ plan: Object });
const value = ref();
const starttime = ref();
const endtime = ref();
const text = ref();
const status = ref();
const visible = ref(false);
const taskID = ref();
// const disabledDate = (current) => current && current < dayjs().endOf('day');

function getListData(current) {
  const events = [];
  props.plan.get_tasks.forEach((task) => {
    if (dayjs(task.execution_date).format('D') === dayjs(current).format('D') && dayjs(task.execution_date).format('M') === dayjs(current).format('M')) {
      if (task.is_done === 1) {
        events.push({
          id: task.id,
          type: 'success',
          content: task.get_task[0].title,
          time: task.execution_date,
          duration: task.get_task[0].duration,
        });
      } else {
        events.push({
          id: task.id,
          type: 'error',
          content: task.get_task[0].title,
          time: task.execution_date,
          duration: task.get_task[0].duration,
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
  visible.value = true;
}
// const handleOk = () => {
//   visible.value = false;
// };
function getMonthData(current) {
  let total = 0;
  let done = 0;
  let undone = 0;
  props.plan.get_tasks.forEach((task) => {
    if (dayjs(task.execution_date).format('M') === dayjs(current).format('M')) {
      total += 1;
      if (task.is_done === 1) {
        done += 1;
      } else {
        undone += 1;
      }
    }
  });
  return [total, done, undone];
}
const taskDone = () => {
  router.post(
    '/plans/view{id}',
    {
      id: taskID.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Užduotis pažymėta kaip atlikta', 1).then(() => message.info('Gavote 200 patirties taškų!', 2.5)),
      onError: () => message.error('Klaida pažymėjant užduotį kaip atliktą'),
    },
  );
};
function handleTaskDone() {
  visible.value = false;
  status.value = 'atlikta';
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
}
</style>
