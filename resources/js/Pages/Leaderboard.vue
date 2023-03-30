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
                <a-breadcrumb-item class="text-xl">
                    <trophy-outlined style="font-size:20px" />
                    Lyderių lentelė
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="">
                        <div class="flex flex-col items-center p-5">
                            <div class="text-xl mb-4">Jūsų pozicija: {{ props.rank }}</div>
                            <table class="table-auto border border-gray-300 shadow">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 border-b border-gray-300">Pozicija</th>
                                        <th class="border-b border-gray-300"></th>
                                        <th class="px-4 py-2 border-b border-gray-300 text-left">Slapyvardis</th>
                                        <th class="px-4 py-2 border-b border-gray-300">Patirties taškai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(user, index) in props.top10" :key="user.username">
                                        <td v-if="props.user.username === user.username"
                                            class="px-4 py-2 border-b border-gray-300 bg-sky-200 text-center">
                                            <template v-if="index === 0">
                                                <trophy-two-tone two-tone-color="#ffc53d" />
                                            </template>
                                            <template v-else-if="index === 1">
                                                <trophy-two-tone two-tone-color="#d9d9d9" />
                                            </template>
                                            <template v-else-if="index === 2">
                                                <trophy-two-tone two-tone-color="#AD8A56" />
                                            </template>
                                            {{ index + 1 }}
                                        </td>
                                        <td v-else class="px-4 py-2 border-b border-gray-300 text-center">
                                            <template v-if="index === 0">
                                                <trophy-two-tone two-tone-color="#ffc53d" />
                                            </template>
                                            <template v-else-if="index === 1">
                                                <trophy-two-tone two-tone-color="#d9d9d9" />
                                            </template>
                                            <template v-else-if="index === 2">
                                                <trophy-two-tone two-tone-color="#AD8A56" />
                                            </template>
                                            {{ index + 1 }}
                                        </td>
                                        <td v-if="props.user.username === user.username"
                                            class="border-b border-gray-300 bg-sky-200">
                                            <div class="mx-1 w-8"><img :src="'/storage/' + user.avatar"
                                                    class="rounded-sm px-1" style="padding-top:1px;padding-bottom:1px">
                                            </div>
                                        </td>
                                        <td v-else class="border-b border-gray-300">
                                            <div class="mx-1 w-8"><img :src="'/storage/' + user.avatar"
                                                    class="rounded-sm px-1" style="padding-top:1px;padding-bottom:1px">
                                            </div>
                                        </td>

                                        <td v-if="props.user.username === user.username"
                                            class="px-4 py-2 border-b border-gray-300 bg-sky-200">{{ user.username }}
                                        </td>
                                        <td v-else class="px-4 py-2 border-b border-gray-300">{{ user.username }}</td>
                                        <td v-if="props.user.username === user.username"
                                            class="px-4 py-2 border-b border-gray-300 bg-sky-200 text-center">{{ user.xp
                                            }}</td>
                                        <td v-else class="px-4 py-2 border-b border-gray-300 text-center">{{ user.xp }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { TrophyTwoTone, TrophyOutlined, HomeOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ top10: Object, rank: Number, user: Object });
</script>
