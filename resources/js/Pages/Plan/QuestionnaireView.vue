<template>
    <Head title="Dashboard" />

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
                    <reconciliation-outlined style="font-size:20px" />
                    Alternatyvos pasirinkimas
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">Apklausa</a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <h3 class="pb-2 text-xl">Atsakykite į klausimus:</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ReconciliationOutlined, HomeOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>
<style scoped></style>
