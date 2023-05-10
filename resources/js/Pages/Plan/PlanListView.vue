<template>
  <Head title="Planų valdymas" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item href="" class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <reconciliation-outlined style="font-size:20px" />
          Planų valdymas
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex pb-1">
          <h3 class="pl-2 text-xl flex-grow">Jūsų planai:</h3>
          <div class="flex-shrink-0 justify-end pr-2">
            <Link :href="route('Plan.ChooseAlternativeView')">
            <a-button type="primary">Sudaryti naują planą</a-button>
            </Link>
          </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-4">
            <a-config-provider :locale="ltLT">
            <a-table :columns="columns" :data-source="plans" :pagination="false">
              <template #bodyCell="{ column, record }">
                <template v-if="column.key === 'active'">
                  <span class="text-green-500" v-if="record.active === 1">
                    Aktyvus
                  </span>
                  <span class="text-red-500" v-else-if="record.active === 0">
                    Neaktyvus
                  </span>
                  <a-switch :checked="record.active === 1" @change="updateActive(record)"/>
                </template>
                <template v-if="column.key === 'color'">
                  <div class="flex justify-start">
                    <div class="w-14 h-5 border border-gray-400" :style="'background-color: ' + record.color"
                      @click="planColor = '#ffccc7'"></div>
                  </div>
                </template>
                <template v-if="column.key === 'created_at'">
                  <div class="min-w-[78px]">
                    <span>
                      {{ formatDate(record.created_at) }}
                    </span>
                  </div>
                </template>
                <template v-if="column.key === 'updated_at'">
                  <div class="min-w-[78px]">
                    <span>
                      {{ formatDate(record.updated_at) }}
                    </span>
                  </div>
                </template>
                <template v-if="column.key === 'action'">
                  <div class="flex">
                    <div class="pr-2">
                      <Link :href="route('Plan.PlanEditView', record.id)">
                      <a-button type="primary" shape="circle">
                        <template #icon>
                          <edit-outlined />
                        </template>
                      </a-button>
                      </Link>
                    </div>
                    <a-popconfirm title="Ar tikrai norite pašalinti planą?" ok-text="Taip" cancel-text="Ne"
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
          </a-config-provider>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import {
  ReconciliationOutlined, HomeOutlined, EditOutlined, DeleteOutlined,
} from '@ant-design/icons-vue';
import { defineProps } from 'vue';
import { message } from 'ant-design-vue';
import ltLT from 'ant-design-vue/es/locale/lt_LT';
import dayjs from 'dayjs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ plans: Object });
const columns = [{
  title: 'Pavadinimas',
  dataIndex: 'title',
  key: 'title',
}, {
  title: 'Statusas',
  dataIndex: 'active',
  key: 'active',
}, {
  title: 'Spalva',
  dataIndex: 'color',
  key: 'color',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
}, {
  title: 'Atnaujinimo data',
  dataIndex: 'updated_at',
  key: 'updated_at',
}, {
  title: 'Veiksmai',
  key: 'action',
}];
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const confirm = (id) => {
  router.post(
    '/planDelete',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Planas pašalintas'),
      onError: () => message.error('Klaida pašalinant planą'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
function updateActive(record) {
  router.post(
    '/planUpdateActive',
    {
      id: record.id,
      active: record.active,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Planas atnaujintas'),
      onError: () => message.error('Klaida atnaujinant planą'),
    },
  );
}
</script>
<style>
.ant-switch-checked {
    background-color: #22c55e;
}
</style>
