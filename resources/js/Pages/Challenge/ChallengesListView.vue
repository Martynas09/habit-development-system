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
        <div class="relative">
          <div class="absolute right-24 z-10">
            <Link :href="route('Challenge.ChallengeSendView')">
            <a-button type="primary">Mesti naują iššūkį<send-outlined /></a-button>
            </Link>
          </div>
          Privatūs iššūkiai
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
              <div class="flex flex-col">
                <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="challenge in props.receivedChallenges" :key="challenge"
                      class="bg-white rounded-lg shadow p-6 relative min-w-[380px] min-h-[220px]">
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
                          <a-button v-if="challenge.status==='pending'" type="primary" @click="showModal(challenge.challenge)">Plačiau</a-button>
                          <span v-else>Iššūkis priimtas</span>
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
              <div class="flex flex-col">
                <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
                  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div v-for="challenge in props.authorPrivateChallenges" :key="challenge"
                      class="bg-white rounded-lg shadow p-6 relative min-w-[380px] min-h-[220px]">
                      <h2 style="color:#0F4C81" class="text-xl font-bold mb-2">{{ challenge.title }}</h2>
                      <span class="text-gray-700 font-bold">Gavėjai:</span>
                      <div v-for="user in challenge.challenged_users" :key="user" class="flex items-center">
                        <div class="mr-1 w-8 mb-1">
                          <img :src="'/storage/' + user.user.avatar"
                            class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                            style="padding-top:1px;padding-bottom:1px">
                        </div>
                        {{ user.user.username }}
                        <span v-if="user.status === 'pending'" class="text-yellow-500 font-bold ml-1">Laukiama</span>
                        <span v-if="user.status === 'rejected'" class="text-red-500 font-bold ml-1">Atmetė</span>
                        <span v-if="user.status === 'accepted'" class="text-green-500 font-bold ml-1">Priėmė</span>
                      </div>
                      <div class="flex justify-between">
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
        <!-- Vieši iššūkiai -->
        <div>Vieši iššūkiai
          <div class="flex flex-col">
            <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div v-for="challenge in props.publicChallenges" :key="challenge"
                  class="bg-white rounded-lg shadow p-6 relative min-w-[380px] min-h-[220px]">
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
      </div>
      <a-modal v-model:visible="visible" :title="title" :footer="dayjs(created_at).format('YYYY-MM-DD')">
        <p>{{ description }}</p>
        <div>
          <Link :href="route('Challenge.ChallengeAcceptView', id)">
          <a-button type="primary">Priimti</a-button>
          </Link>
        </div>
      </a-modal>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { DashboardOutlined, HomeOutlined, SendOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ publicChallenges: Object, authorPrivateChallenges: Object, receivedChallenges: Object });
const visible = ref(false);
const title = ref('');
const description = ref('');
const activeKey = ref('1');
const created_at = ref();
const id = ref();

const showModal = (challenge) => {
  console.log(props.authorPrivateChallenges);
  title.value = challenge.title;
  description.value = challenge.description;
  id.value = challenge.id;
  visible.value = true;
};

</script>
<style></style>
