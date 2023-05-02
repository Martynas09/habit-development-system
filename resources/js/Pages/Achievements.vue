<template>
  <Head title="Pasiekimų valdymas" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <star-outlined style="font-size:20px" />
          Pasiekimų valdymas
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex pb-1">
          <h3 class="pl-2 text-xl flex-grow">Pasiekimai:</h3>
          <div class="flex-shrink-0 justify-end pr-2">
            <a-button @click="showModal" type="primary">Pridėti pasiekimą</a-button>
            <a-modal v-model:visible="visible" title="Naujas pasiekimas" @ok="handleOk" ok-text="Sukurti"
              cancel-text="Atšaukti">
              <a-form>
                <a-form-item label="Pavadinimas" :label-col="{ span: 9 }" :wrapper-col="{ span: 12 }">
                  <a-input v-model:value="achievementTitle" />
                </a-form-item>
                <a-form-item label="Aprašymas" :label-col="{ span: 9 }" :wrapper-col="{ span: 12 }">
                  <a-textarea v-model:value="achievementDescription" />
                </a-form-item>
                <a-form-item label="Skiriami patirties taškai" :label-col="{ span: 9 }" :wrapper-col="{ span: 12 }">
                  <a-input-number v-model:value="achievementRewardXP" />
                </a-form-item>
              </a-form>
            </a-modal>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
          <div class="p-4 flex justify-center">
            <a-table :columns="columns" :data-source="achievements" :pagination="false">
              <template #bodyCell="{ column, record, index }">
                <template v-if="column.key === 'title'">
                  <div v-if="editingList[index] === false">{{ record.title }}</div>
                  <div v-else>
                    <a-textarea v-model:value="record.title" style="margin-left: 0px;margin-top:0px" />
                  </div>
                </template>
                <template v-if="column.key === 'description'">
                  <div v-if="editingList[index] === false">{{
                    record.description }}</div>
                  <div v-else>
                    <a-textarea v-model:value="record.description" style="margin-left: 0px;margin-top:0px" />
                  </div>
                </template>
                <template v-if="column.key === 'rewardXP'">
                  <div v-if="editingList[index] === false">{{ record.rewardXP }} XP</div>
                  <div v-else class="flex items-center gap-2">
                    <a-input-number v-model:value="record.rewardXP" style="margin-left: 0px;margin-top:0px;" />XP
                  </div>
                </template>
                <template v-if="column.key === 'created_at'">
                  <div class="min-w-[78px]">
                    <span>
                      {{ formatDate(record.created_at) }}
                    </span>
                  </div>
                </template>
                <template v-if="column.key === 'action'">
                  <div class="flex">
                    <div class="pr-2">
                      <a-button v-if="editingList[index] === false" @click="editingList[index] = true" type="primary"
                        shape="circle">
                        <template #icon>
                          <edit-outlined />
                        </template>
                      </a-button>
                      <a-button v-else html-type="submit" type="primary" shape="circle"
                        @click="handleEdit(record.rewardXP, record.description, record.title, record.id, index)"><template
                          #icon>
                          <save-outlined />
                        </template></a-button>
                    </div>
                    <a-popconfirm title="Ar tikrai norite pašalinti tikslą?" ok-text="Taip" cancel-text="Ne"
                      @confirm="confirm(record.id)" @cancel="cancel">
                      <a-button type="danger" shape="circle">
                        <template #icon>
                          <delete-outlined />
                        </template>
                      </a-button>
                    </a-popconfirm>
                  </div>
                </template>
              </template>
            </a-table>
            <!-- <div class="flex justify-end"><a-pagination v-model:current="currentPage"
                                @change="triggerPagination" :total="achievements.total"
                                :defaultPageSize="achievements.per_page" :hideOnSinglePage="true" :size="'small'" /></div> -->
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
import {
  HomeOutlined, StarOutlined, DeleteOutlined, EditOutlined, SaveOutlined,
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { ref, defineProps, onMounted } from 'vue';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message } from 'ant-design-vue';
import 'dayjs/locale/lt';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

dayjs.extend(relativeTime);
dayjs.locale('lt');

const props = defineProps({
  achievements: Object,
});
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
console.log(props.achievements);
const editingList = ref([
]);
// const currentPage = ref(props.achievements.current_page);
const columns = [{
  title: 'Pavadinimas',
  dataIndex: 'title',
  key: 'title',
}, {
  title: 'Aprašymas',
  dataIndex: 'description',
  key: 'description',
}, {
  title: 'Skiriami patirties taškai',
  dataIndex: 'rewardXP',
  key: 'rewardXP',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
}, {
  title: 'Veiksmai',
  key: 'action',
}];

onMounted(() => {
  for (let i = 0; i < props.achievements.length; i += 1) {
    editingList.value.push(false);
  }
});

// const triggerPagination = (page) => {
//   router.get('/achievements', { page });
// };
const visible = ref(false);
const achievementTitle = ref('');
const achievementDescription = ref('');
const achievementRewardXP = ref();
const showModal = () => {
  visible.value = true;
};
const handleOk = () => {
  router.post(
    '/achievementAdd',
    {
      title: achievementTitle.value,
      description: achievementDescription.value,
      rewardXP: achievementRewardXP.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas sėkmingai pridėtas'),
      onError: () => message.error('Klaida pridedant pasiekimą'),
    },
  );
  achievementTitle.value = '';
  achievementDescription.value = '';
  achievementRewardXP.value = 0;
  editingList.value.push(false);
  visible.value = false;
};
const confirm = (id) => {
  router.post(
    '/achievementDelete/',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant pasiekimą'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
const handleEdit = (rewardXP, description, title, id, index) => {
  router.post(
    '/achievementEdit/',
    {
      rewardXP,
      title,
      description,
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant pasiekimą'),
    },
  );
  editingList.value[index] = false;
};

</script>
