<template>
    <Head title="Pagrindinis" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item class="text-xl">
                    <home-outlined style="font-size:20px" />
                    Pagrindinis
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="pl-4">
                        <div class="py-4 text-xl">Jūsų pozicija: {{ props.rank }}</div>
                        <div class="text-lg">TOP 10</div>
                        <a-list>
                            <template v-for="(user, index) in props.top10" v-bind:key="user">
                                <div>
                                    <div class="flex items-center">
                                        <a-list-item>
                                            <div class="text-lg p-2">{{ index + 1 }}.</div>
                                            <div class="mx-1 w-8 mb-1">
                                                <img :src="'/storage/' + user.avatar"
                                                    class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                                                    style="padding-top:1px;padding-bottom:1px">
                                            </div>
                                            <div class="text-md"><span v-if="props.user.username===user.username" class="bg-yellow-400">{{ user.username }} &lt;--</span><span v-else>{{ user.username }}</span></div>
                                        </a-list-item>
                                    </div>
                                </div>
                            </template>
                        </a-list>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import { HomeOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ top10: Object, rank: Number, user: Object });
</script>
