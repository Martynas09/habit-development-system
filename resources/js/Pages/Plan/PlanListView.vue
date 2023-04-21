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
                    <div class="p-4 flex justify-center">
            <a-table :columns="columns" :data-source="plans" :pagination="false">
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
          </div>
                    <a-divider style="margin-top:0px;margin-bottom:0px" />
                    <a-list>
                        <template v-for="plan in props.plans" v-bind:key="plan">
                            <div class="pl-4">
                                <div class="flex items-center pt-3">
                                    <a-list-item>
                                        <p class="text-lg">{{ plan.title }}</p>
                                        <p v-if="plan.active === 1" class="text-green-500 pl-2">Aktyvus</p>
                                        <p v-else class="text-red-500 pl-2">Neaktyvus</p>
                                        <Link :href="route('Plan.PlanEditView', plan.id)">
                                        <a-button type="primary" class="ml-4"><edit-outlined />Redaguoti</a-button>
                                        </Link>
                                        <a-popconfirm title="Ar tikrai norite pašalinti planą?" ok-text="Taip" cancel-text="Ne"
                                            @confirm="confirm(plan.id)" @cancel="cancel">
                                        <a-button type="danger" class="ml-4"><delete-outlined />Pašalinti</a-button>
                                        </a-popconfirm>
                                    </a-list-item>
                                </div>
                            </div>
                            <a-divider style="margin-top:0px;margin-bottom:0px" />
                        </template>
                    </a-list>
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
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ plans: Object });

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

const confirm = (id) => {
  router.post(
    '/planDelete/',
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
</script>
<style></style>
