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
                                            @confirm="confirm" @cancel="cancel">
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

const confirm = () => {
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
const cancel = () => {
  message.error('Click on No');
};
</script>
<style></style>
