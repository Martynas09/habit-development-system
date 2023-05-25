<template>
    <Head title="Rekomenduojami planai" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('Plan.PlanListView')">
                    <reconciliation-outlined style="font-size:20px" />
                    Planų valdymas
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('Plan.ChooseAlternativeView')">
                    <gold-outlined style="font-size:20px" />
                    Alternatyvos pasirinkimas
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <dashboard-outlined style="font-size:20px" />
                    Rekomenduojami planai
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Vieši iššūkiai -->
                <div>
                    <div class="flex flex-col">
                        <div class="flex-grow px-4 pt-2 pb-8 mx-auto max-w-7xl">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="plan in plans" :key="plan"
                                    class="bg-white rounded-lg shadow p-6 relative min-w-[380px] min-h-[180px]">
                                    <h2 class="text-xl font-bold mb-2 text-lime-500">{{ plan.title }}</h2>
                                    <p class="text-gray-500 text-base mb-4">
                                        <span v-if="plan.title === 'Sportas'">Planas skirtas norintiems išsiugdyti sportinės
                                            veiklos įprotį</span>
                                        <span v-if="plan.title === 'Meditacija'">Planas skirtas norintiems sumažinti kasdieninį stresą</span>
                                        <span v-if="plan.title === 'Skaitymas'">Planas skirtas norintiems išsiugdyti skaitymo
                                            įprotį</span>
                                    </p>
                                    <div class="absolute right-4 bottom-4"><a-button type="primary"
                                            @click="handleSelect(plan.id)">Pasirinkti</a-button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Vieši iššūkiai pabaiga-->
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import {
  DashboardOutlined, HomeOutlined, ReconciliationOutlined, GoldOutlined,
} from '@ant-design/icons-vue';
import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ plans: Object });
const handleSelect = (id) => {
  router.post(
    '/plans/recommended',
    {
      id,
    },
    {
      preserveScroll: true,
    },
  );
};

</script>
<style></style>
