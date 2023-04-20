<template>
    <Head title="Pagrindinis" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('profile.edit')">
                    <user-outlined style="font-size:20px;margin-right:5px" />
                    <span>Profilis</span>
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <property-safety-outlined style="font-size:20px" />
                    Jūsų pasiekimai
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="achievement-list">
                        <div class="p-6">
                            <a-row :gutter="16">
                                <a-col v-for="achievement in achievements" :key="achievement.id" :span="6" class="p-2">
                                    <a-card :bordered="true" class="min-h-[250px] max-h-[250px]">
                                        <template #title>
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="max-w-[45px]">
                                                    <img src="/storage/achievement.png">
                                                </div>{{ achievement.get_achievement.title }}
                                            </div>
                                        </template>
                                        <div>
                                            <p class="max-h-[45px] overflow-scroll">{{
                                                achievement.get_achievement.description }}</p>
                                            <p>{{ achievement.get_achievement.rewardXP }} patirties taškai</p>
                                            <p class="text-gray-400">{{ formatDate(achievement.get_achievement.created_at)
                                            }}</p>
                                        </div>
                                    </a-card>
                                </a-col>
                            </a-row>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { PropertySafetyTwoTone, PropertySafetyOutlined, HomeOutlined } from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ achievements: Object });
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
</script>
