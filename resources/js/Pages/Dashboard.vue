<template>
  <Head title="Pagrindinis" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item class="text-xl">
          <home-outlined style="font-size:20px" />
          Pagrindinis
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div v-if="!$page.props.auth.user.is_admin">
            <p class="text-3xl font-bold text-black text-center p-3">Sveiki, {{ $page.props.auth.user.username }}
            </p>
            <div style="padding: 20px">
              <a-row :gutter="16">
                <a-col :span="16">
                  <a-card :bordered="false">
                    <template #title>
                      <div class="text-2xl text-sky-500">
                        <bell-outlined />
                        <span class="font-bold pr-1"> Pranešimai</span>
                        <a-badge :count="props.notification.length"></a-badge>
                      </div>
                    </template>
                    <p v-if="props.notification.length === 0" class="pt-2 pl-3">Neturite naujų pranešimų</p>
                    <div v-for="challenge in props.notification" :key="challenge">
                      <p v-if="challenge.status === 'pending'" class="pt-2 pl-3 font-semibold">Jūs gavote naują iššūkį nuo
                        {{ challenge.challenge.challenge_author.username }}</p>
                    </div>
                  </a-card>
                </a-col>
                <a-col :span="8">
                  <a-card :bordered="false">
                    <template #title>
                      <div class="text-2xl text-sky-500">
                        <calendar-outlined />
                        <span class="font-bold"> Artimiausios užduotys</span>
                      </div>
                    </template>
                    <div v-for="task in tasks" :key="task" class="pt-1">
                      <span class="font-semibold">{{ dayjs(task.execution_date).format('dddd/MMMM')
                      }}</span>
                      <div class="pb-1">
                        <div class="rounded-sm max-h-[22px] pl-1 bg-sky-100 max-w-[220px]">
                          <a-badge :text="dayjs(task.execution_date).format('HH:mm') + ' ' + task.get_task.title" />
                        </div>
                      </div>
                    </div>
                    <p v-if="tasks.length === 0" class="pt-2">Neturite užduočių</p>
                  </a-card>
                </a-col>
                <a-col :span="8">
                  <a-card :bordered="false">
                    <template #title>
                      <div class="text-2xl text-sky-500">
                        <form-outlined />
                      <span class="font-bold"> Naujausi užrašai</span>
                    </div>
                  </template>
                    <p v-if="notes.length === 0">Neturite užrašų</p>
                    <a-list v-if="notes.length" :data-source="notes" item-layout="horizontal">
                      <template #renderItem="{ item }">
                        <a-list-item style="padding:0px">
                          <a-comment>
                            <template #content>
                              <div>{{ item.description }}</div>
                            </template>
                            <template #datetime>
                              <a-tooltip :title="dayjs().format('YYYY-MM-DD HH:mm:ss')">
                                <span>{{ dayjs(item.updated_at).fromNow() }}</span>
                              </a-tooltip>
                            </template>
                          </a-comment>
                        </a-list-item>
                      </template>
                    </a-list>
                  </a-card>
                </a-col>
              </a-row>
            </div>
          </div>
          <div v-else class="p-5">
            <div class="text-3xl font-bold text-black text-center p-3">Sėkmingai prisijungėte prie administratoriaus
              paskyros</div>
            <div class="text-md text-gray-400 text-center">Galite pradėti sistemos administravimą</div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import {
  HomeOutlined, CalendarOutlined, FormOutlined, BellOutlined,
} from '@ant-design/icons-vue';
import { defineProps } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/lt';
import relativeTime from 'dayjs/plugin/relativeTime';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

dayjs.extend(relativeTime);
dayjs.locale('lt');

const props = defineProps({
  tasks: Object,
  notes: Object,
  notification: Object,
});
console.log(props.notification);
// console.log(props.tasks);
// console.log(props.notes);
</script>
<style>
.ant-card-body {
  padding-top: 0px;
}
</style>
