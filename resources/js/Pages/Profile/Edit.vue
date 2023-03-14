<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="/dashboard" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size: 20px" />
                    </Link>
                </a-breadcrumb-item>
                <user-outlined style="font-size: 20px; margin-right: 5px; color: #262626" />
                <a-breadcrumb-item class="text-xl">Profilis</a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2">
                    <div>
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status"
                            class="max-w-xl" />
                    </div>
                    <div class="pl-16">
                        <div class="flex items-center justify-center p-10">
                            <img :src="'/storage/' + userAvatar.get_head[0].picture" class="w-36 rounded-sm border px-2 py-1 border-solid border-gray-300 shadow-sm">
                        </div>
                        <div class="flex items-center justify-center pt-2">
                            <Link :href="route('profile.characterEdit')">
                            <a-button type="primary">Redaguoti personažą</a-button>
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { HomeOutlined, UserOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

defineProps({
  mustVerifyEmail: Boolean,
  status: String,
  userAvatar: Object,
});
</script>
