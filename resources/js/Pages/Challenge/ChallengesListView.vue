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
          <dashboard-outlined style="font-size:20px" />
          Iššūkiai
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center pb-3 pl-5"><span class="mr-2 text-xl">Tipas:</span><a-switch
            v-model:checked="checked" checked-children="Privatūs" un-checked-children="Vieši" style="width:90px;" /></div>
        <!-- Vieši iššūkiai -->
        <div v-if="checked === false">
          <div class="flex flex-col h-screen">
            <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="challenge in props.publicChallenges" :key="challenge"
                  class="bg-white rounded-lg shadow p-6 relative">
                  <h2 style="color:#0F4C81" class="text-xl font-bold mb-2">{{ challenge.title }}</h2>
                  <p class="text-gray-700 mb-8">{{ challenge.description }}</p>
                  <div class="flex justify-between">
                    <span class="text-gray-700 absolute left-6 bottom-4">Skiriama <span class="font-bold">{{
                      challenge.xpGiven }}</span> <span v-if="challenge.xpGiven === 1">patirties taškas</span><span
                        v-if="challenge.xpGiven === 10">patirties taškų</span><span
                        v-if="challenge.xpGiven !== 1 && challenge.xpGiven !== 10">patirties taškai</span></span>
                    <div class="absolute right-4 bottom-4">
                      <a-button type="primary" @click="showModal(challenge)">Plačiau</a-button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Vieši iššūkiai pabaiga-->
        <div class="pl-6" v-if="checked === true">
          <a-tabs v-model:activeKey="activeKey">
            <!-- Privatūs mesti iššūkiai -->
            <a-tab-pane key="1">
              <template #tab>
                <div class="flex items-center ">
                  <span class="mr-1">Gautieji</span>
                  <a-badge :count="props.receivedChallenges.length">
                  </a-badge>
                </div>
              </template>
              <div class="flex flex-col h-screen">
                <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="challenge in props.receivedChallenges" :key="challenge"
                      class="bg-white rounded-lg shadow p-6 relative">
                      <h2 style="color:#0F4C81" class="text-xl font-bold mb-2">{{ challenge.challenge.title }}</h2>
                      <div class="flex items-center absolute top-4 right-4">
                        <div class="mr-1 w-9">
                          <img :src="'/storage/' + $page.props.auth.user.avatar"
                            class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                            style="padding-top:1px;padding-bottom:1px">
                        </div>
                        {{ challenge.challenge.challenge_author.username }}
                      </div>
                      <p class="text-gray-700 mb-8">{{ challenge.challenge.description }}</p>
                      <div class="flex justify-between">
                        <div class="absolute right-4 bottom-4">
                          <a-button type="primary" @click="showModal(challenge.challenge)">Plačiau</a-button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a-tab-pane>
            <!-- Privatūs mesti iššūkiai pabaiga-->
            <!-- Privatūs gauti iššūkiai -->
            <a-tab-pane key="2" tab="Išsiųsti">
              <div class="flex flex-col h-screen">
                <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="challenge in props.authorPrivateChallenges" :key="challenge"
                      class="bg-white rounded-lg shadow p-6 relative">
                      <h2 style="color:#0F4C81" class="text-xl font-bold mb-2">{{ challenge.title }}</h2>
                      <p class="text-gray-700 mb-8">{{ challenge.description }}</p>
                      <div class="flex justify-between">
                        <span class="text-gray-700 absolute left-6 bottom-4">Skiriama <span class="font-bold">{{
                          challenge.xpGiven }}</span> <span v-if="challenge.xpGiven === 1">patirties taškas</span><span
                            v-if="challenge.xpGiven === 10">patirties taškų</span><span
                            v-if="challenge.xpGiven !== 1 && challenge.xpGiven !== 10">patirties taškai</span></span>
                        <div class="absolute right-4 bottom-4">
                          <a-button type="primary" @click="showModal(challenge)">Plačiau</a-button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a-tab-pane>
            <!-- Privatūs gauti iššūkiai pabaiga -->
          </a-tabs>
        </div>
      </div>
      <a-modal v-model:visible="visible" :title="title" :footer="dayjs(created_at).format('YYYY-MM-DD')">
        <p>{{ description }}</p>
        <div>
          <a-button type="primary">Priimti</a-button>
        </div>
      </a-modal>
    </div>
    <a-button @click="test">AAA</a-button>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { DashboardOutlined, HomeOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ publicChallenges: Object, authorPrivateChallenges: Object, receivedChallenges: Object });
const visible = ref(false);
const title = ref('');
const description = ref('');
const checked = ref(true);
const activeKey = ref('1');
const created_at = ref();

const test = () => {
  console.log(props.receivedChallenges);
};

const showModal = (challenge) => {
  title.value = challenge.title;
  description.value = challenge.description;
  visible.value = true;
};

</script>
<style></style>
