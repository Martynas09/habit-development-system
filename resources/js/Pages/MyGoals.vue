<template>
  <Head title="Tikslų valdymas" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item href="" class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <aim-outlined style="font-size:20px" />
          Tikslų valdymas
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex pb-1">
          <h3 class="pl-2 text-xl flex-grow">Jūsų tikslai:</h3>
          <div class="flex-shrink-0 justify-end pr-2">
            <a-button @click="showModal" type="primary">Sukurti naują tikslą</a-button>
            <a-modal v-model:visible="visible" title="Naujas tikslas" @ok="handleOk" ok-text="Sukurti"
              cancel-text="Atšaukti">
              <a-form>
                <a-form-item label="Pavadinimas" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-input v-model:value="goalTitle" />
                </a-form-item>
                <!-- <a-form-item label="Būsena" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-select v-model:value="status" placeholder="Pasirinkite būseną">
                    <a-select-option value="not in progress">Nevykdomas</a-select-option>
                    <a-select-option value="in progress">Vykdomas</a-select-option>
                    <a-select-option value="completed">Užbaigtas</a-select-option>
                  </a-select>
                </a-form-item>
                <a-form-item label="Priklauso planams" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-select v-model:value="plans" mode="multiple" placeholder="Pasirinkite planus">
                    <a-select-option v-for="plan in plansList" :key="plan.id" :value="plan.id">
                      {{ plan.title }}
                    </a-select-option>
                  </a-select>
                </a-form-item> -->
              </a-form>
            </a-modal>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-4">
            <a-table :columns="columns" :data-source="goals" :pagination="false">
              <template #bodyCell="{ column, record, index }">
                <template v-if="column.key === 'title'">
                  <div v-if="editingList[index] === false">{{ record.title }}</div>
                  <div v-else class="flex gap-4">
                    <a-input v-model:value="record.title" style="margin-left: 0px;margin-top:0px" />
                    <a-button html-type="submit" type="primary" @click="handleEdit(record.title, record.id, index)">
                      Išsaugoti
                    </a-button>
                  </div>
                </template>
                <template v-if="column.key === 'status'">
                  <span class="text-red-500" v-if="record.status = 'not in progress'">
                    Nevykdomas
                  </span>
                  <span class="text-yellow-500" v-else-if="record.status = 'in progress'">
                    Vykdomas
                  </span>
                  <span class="text-green-500" v-else-if="record.status = 'completed'">
                    Užbaigtas
                  </span>
                </template>
                <template v-if="column.key === 'created_at'">
                  <span>
                    {{ formatDate(record.created_at) }}
                  </span>
                </template>
                <template v-if="column.key === 'plans'">
                  <span>
                    <span v-if="record.plan_goal.length===0" >Nėra</span>
                    <a-tag v-for="plan in record.plan_goal" :key="plan.id" :color="plan.plan.color">
                      <span class="text-black">{{ plan.plan.title }}</span>
                    </a-tag>
                  </span>
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
            <div class="flex justify-end">
            <a-pagination v-model:current="currentPage" @change="triggerPagination" :total="goals.total"
              :defaultPageSize="goals.per_page" :hideOnSinglePage="true" :size="'small'" />
          </div>
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
  HomeOutlined, AimOutlined, EditOutlined, DeleteOutlined,
} from '@ant-design/icons-vue';
import { defineProps, ref, onMounted } from 'vue';
import dayjs from 'dayjs';
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  goals: Object,
});
const columns = [{
  title: 'Pavadinimas',
  dataIndex: 'title',
  key: 'title',
}, {
  title: 'Būsena',
  dataIndex: 'status',
  key: 'status',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
}, {
  title: 'Priklauso planams',
  key: 'plans',
}, {
  title: 'Veiksmai',
  key: 'action',
}];
const currentPage = ref(props.goals.current_page);
const editingList = ref([
]);
const triggerPagination = (page) => {
  router.get('/goals', { page });
};
onMounted(() => {
  editingList.value = props.goals.map(() => false);
  console.log(props.goals);
});
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const confirm = (id) => {
  router.post(
    '/goalDelete/',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Tikslas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant tikslą'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
const handleEdit = (title, id, index) => {
  router.post(
    '/goalEdit/',
    {
      title,
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Tikslas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant tikslą'),
    },
  );
  editingList.value[index] = false;
};

const visible = ref(false);
const goalTitle = ref('');
const showModal = () => {
  visible.value = true;
};
const handleOk = () => {
  router.post(
    '/goalAdd/',
    {
      title: goalTitle.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Tikslas sėkmingai pridėtas'),
      onError: () => message.error('Klaida pridedant tikslą'),
    },
  );
  editingList.value.push(false);
  visible.value = false;
  goalTitle.value = '';
};
</script>
