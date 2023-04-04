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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4">
                        <a-table :columns="columns" :data-source="goals" :pagination="false">
                            <template #bodyCell="{ column, record }">
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
                                        <a-tag  v-for="plan in record.plan_goal" :key="plan.id" :color="plan.plan.color">
                                            <span class="text-black">{{ plan.plan.title }}</span>
                                        </a-tag>
                                    </span>
                                </template>
                                <template v-if="column.key === 'action'">
                                    <span>
                                        <a-button type="primary"><edit-outlined />Redaguoti</a-button>
                                        <a-popconfirm title="Ar tikrai norite pašalinti planą?" ok-text="Taip"
                                            cancel-text="Ne" @confirm="confirm" @cancel="cancel">
                                            <a-button type="danger" class="ml-4"><delete-outlined />Pašalinti</a-button>
                                        </a-popconfirm>
                                    </span>
                                </template>
                            </template>
                        </a-table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
  HomeOutlined, AimOutlined, EditOutlined, DeleteOutlined,
} from '@ant-design/icons-vue';
import { defineProps } from 'vue';
import dayjs from 'dayjs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
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
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const confirm = (e) => {
  router.post(
    '/planDelete/',
    {
      head: headID.value,
      top: topID.value,
      bottom: bottomID.value,
      shoes: shoesID.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Personažas atnaujintas sėkmingai'),
      onError: () => message.error('Klaida atnaujinant personažą'),
    },
  );
};
const cancel = (e) => {
  console.log(e);
  message.error('Click on No');
};
</script>
