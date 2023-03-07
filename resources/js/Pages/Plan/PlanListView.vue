<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item class="text-xl">
                    <reconciliation-outlined style="font-size:20px" />
                    Planų valdymas
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="pb-4">
                    <Link :href="route('Plan.ChooseAlternativeView')">
                    <a-button type="primary">Sudaryti naują planą</a-button>
                    </Link>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h3 :style="{ margin: '16px' }">Jūsų planai:</h3>
                    <a-list bordered :data-source="data">
                        <template v-for="plan in props.plans" v-bind:key="plan">
                            <Link :href="route('Plan.PlanView', { id: plan.id })">
                            <a-list-item>{{ plan.title }}</a-list-item>
                            </Link>
                        </template>
                    </a-list>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ReconciliationOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ plans: Object });
</script>
<style></style>
