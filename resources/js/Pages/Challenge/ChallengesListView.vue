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
          <dashboard-outlined style="font-size:20px" />
          Iššūkiai
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h3 class="pl-4 text-xl">Aktyvūs iššūkiai:</h3>
        <div class="flex flex-col h-screen">
          <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div v-for="challenge in props.challenges" :key="challenge" class="bg-white rounded-lg shadow p-6 relative">
                <h2 style="color:#0F4C81" class="text-xl font-bold mb-2">{{ challenge.title }}</h2>
                <p class="text-gray-700 mb-8">{{ challenge.description }}</p>
                <div class="flex justify-between">
                  <span class="text-gray-500 absolute left-6 bottom-4">{{ dayjs(challenge.created_at).format('YYYY-MM-DD')
                  }}</span>
                  <div class="absolute right-4 bottom-4">
                    <a-button type="primary" @click="showModal(challenge)">Plačiau</a-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <a-modal v-model:visible="visible" :title="title">
        <p>{{ description }}</p>
      </a-modal>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { DashboardOutlined, HomeOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ challenges: Object });
const visible = ref(false);
const title = ref('');
const description = ref('');

const showModal = (challenge) => {
  title.value = challenge.title;
  description.value = challenge.description;
  visible.value = true;
};

</script>
<style></style>
