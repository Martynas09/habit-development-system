<template>
    <Head title="Naudotojų valdymas" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <team-outlined style="font-size:20px" />
                    Naudotojų valdymas
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex pb-1">
                    <h3 class="pl-2 text-xl flex-grow">Naudotojai:</h3>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-4 flex justify-center">
                        <a-table :columns="columns" :data-source="users" :pagination="false">
                            <template #bodyCell="{ column, record, index }">
                                <template v-if="column.key === 'username'">
                                    <div v-if="editingList[index] === false">{{ record.username }}</div>
                                    <div v-else>
                                        <a-input v-model:value="record.username" style="margin-left: 0px;margin-top:0px" />
                                    </div>
                                </template>
                                <template v-if="column.key === 'email'">
                                    <div v-if="editingList[index] === false">{{ record.email }}</div>
                                    <div v-else>
                                        <a-input v-model:value="record.email" style="margin-left: 0px;margin-top:0px" />
                                    </div>
                                </template>
                                <template v-if="column.key === 'xp'">
                                    <div v-if="editingList[index] === false">{{ record.xp }} XP</div>
                                    <div v-else class="flex items-center gap-2">
                                        <a-input-number v-model:value="record.xp"
                                            style="margin-left: 0px;margin-top:0px;" />XP
                                    </div>
                                </template>
                                <template v-if="column.key === 'created_at'">
                                    <div class="min-w-[77px]">
                                        <span>
                                            {{ formatDate(record.created_at) }}
                                        </span>
                                    </div>
                                </template>
                                <template v-if="column.key === 'action'">
                                    <div class="flex">
                                        <div class="pr-2">
                                            <a-button v-if="editingList[index] === false" @click="editingList[index] = true"
                                                type="primary" shape="circle">
                                                <template #icon>
                                                    <edit-outlined />
                                                </template>
                                            </a-button>
                                            <a-button v-else html-type="submit" type="primary" shape="circle"
                                                @click="handleEdit(record.username, record.email, record.xp, record.id, index)"><template
                                                    #icon>
                                                    <save-outlined />
                                                </template></a-button>
                                        </div>
                                        <a-popconfirm title="Ar tikrai norite pašalinti naudotoją?" ok-text="Taip"
                                            cancel-text="Ne" @confirm="confirm(record.id)" @cancel="cancel">
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
                                @change="triggerPagination" :total="users.total"
                                :defaultPageSize="users.per_page" :hideOnSinglePage="true" :size="'small'" /></div> -->
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
  HomeOutlined, TeamOutlined, DeleteOutlined, EditOutlined, SaveOutlined,
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
  users: Object,
});
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const editingList = ref([
]);
// const currentPage = ref(props.users.current_page);
const columns = [{
  title: 'Slapyvardis',
  dataIndex: 'username',
  key: 'username',
}, {
  title: 'El. paštas',
  dataIndex: 'email',
  key: 'email',
}, {
  title: 'Patirties taškai',
  dataIndex: 'xp',
  key: 'xp',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
}, {
  title: 'Veiksmai',
  key: 'action',
}];

onMounted(() => {
  for (let i = 0; i < props.users.length; i += 1) {
    editingList.value.push(false);
  }
});

// const triggerPagination = (page) => {
//   router.get('/achievements', { page });
// };
const confirm = (id) => {
  router.post(
    '/userDelete/',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Naudotojas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant naudotoją'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
const handleEdit = (username, email, xp, id, index) => {
  router.post(
    '/userEdit/',
    {
      username,
      email,
      xp,
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Naudotojas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant naudotoją'),
    },
  );
  editingList.value[index] = false;
};

</script>
