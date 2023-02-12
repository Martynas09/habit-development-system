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
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <h3 :style="{ margin: '16px' }">Planas:</h3>
          <div style="border: 1px solid #d9d9d9; border-radius: 4px; margin: 16px ">
            <a-calendar v-model:value="value">
              <template #dateCellRender="{ current }">
                <ul class="events">
                  <li v-for="item in getListData(current)" :key="item.content">
                    <a-popover :title='text' trigger="click">
                      <template #content>
                        <p>Atlikimo laikas: {{ time }}</p>
                        <p>Užduotis {{ status }}</p>
                      </template>
                      <a-badge @click=handleClick(item) :status="item.type" :text="item.content" />
                    </a-popover>
                  </li>
                </ul>
              </template>
              <!-- <template #monthCellRender="{ current }">
                <div v-if="getMonthData(current)" class="notes-month">
                  <section>{{ getMonthData(current) }}</section>
                  <span>Backlog number</span>
                </div>
              </template> -->
            </a-calendar>
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
import { Head, Link } from '@inertiajs/vue3';
import { ReconciliationOutlined, HomeOutlined } from '@ant-design/icons-vue';
import { ref } from 'vue';
import dayjs from 'dayjs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ plan: Object });

const value = ref();
const time = ref();
const text = ref();
const status = ref();
const visible = ref(false);
// const disabledDate = (current) => current && current < dayjs().endOf('day');

function getListData(current) {
  const events = [];
  props.plan.get_tasks.forEach((task) => {
    if (dayjs(task.execution_date).format('D') === dayjs(current).format('D') && dayjs(task.execution_date).format('M') === dayjs(current).format('M')) {
      if (task.is_done === 1) {
        events.push({
          type: 'success',
          content: task.get_task[0].title,
          time: task.execution_date,
        });
      } else {
        events.push({
          type: 'error',
          content: task.get_task[0].title,
          time: task.execution_date,
        });
      }
    }
  });
  return events;
}

function handleClick(item) {
  time.value = dayjs(item.text).format('HH:mm');
  text.value = item.content;
  if (item.type === 'success') {
    status.value = 'atlikta';
  } else {
    status.value = 'neatlikta';
  }
  visible.value = true;
}
// const handleOk = () => {
//   visible.value = false;
// };
// function getMonthData() {
//   return 123;
// }
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
