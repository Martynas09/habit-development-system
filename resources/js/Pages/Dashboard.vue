<script setup>
import { Head } from '@inertiajs/vue3';
import { HomeOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

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
                    <div>
                        <h1 class="text-xl font-bold text-sky-500 p-5 text-center">Sužaidybinta įpročių ugdymo sistema</h1>
                        <a-row class="flex items-center py-10">
                            <a-col :span="12" class="p-5">
                                <div>
                                    <h1 class="text-xl font-bold">Pavadinimas</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem quas voluptate sit
                                        qui mollitia vel modi odio, repudiandae tenetur. Sint, numquam! Pariatur qui ea
                                        aperiam non nostrum minima corrupti eum quis vel quas explicabo saepe rerum, eaque
                                        minus eos, optio deserunt ut, necessitatibus tenetur. Ipsum voluptate eum explicabo?
                                        Placeat repellat atque blanditiis sequi illo! Ea, fuga laboriosam? Quasi, aut
                                        dolorum.</p>
                                </div>
                            </a-col>
                            <a-col :span="12" class="p-5"><div class="border border-gray-300 rounded-md p-2"><img :src="'/storage/first.png'"></div></a-col>
                        </a-row>
                        <a-row class="flex items-center py-10">
                            <a-col :span="12" class="p-5"><div class="border border-gray-300 rounded-md p-2"><img :src="'/storage/second.png'"></div></a-col>
                            <a-col :span="12" class="p-5">
                                <div>
                                    <h1 class="text-xl font-bold">Pavadinimas</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem quas voluptate sit
                                        qui mollitia vel modi odio, repudiandae tenetur. Sint, numquam! Pariatur qui ea
                                        aperiam non nostrum minima corrupti eum quis vel quas explicabo saepe rerum, eaque
                                        minus eos, optio deserunt ut, necessitatibus tenetur. Ipsum voluptate eum explicabo?
                                        Placeat repellat atque blanditiis sequi illo! Ea, fuga laboriosam? Quasi, aut
                                        dolorum.</p>
                                </div>
                            </a-col>
                        </a-row>
                        <a-row class="flex items-center py-10">
                            <a-col :span="12" class="p-5">
                                <div>
                                    <h1 class="text-xl font-bold">Pavadinimas</h1>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quidem quas voluptate sit
                                        qui mollitia vel modi odio, repudiandae tenetur. Sint, numquam! Pariatur qui ea
                                        aperiam non nostrum minima corrupti eum quis vel quas explicabo saepe rerum, eaque
                                        minus eos, optio deserunt ut, necessitatibus tenetur. Ipsum voluptate eum explicabo?
                                        Placeat repellat atque blanditiis sequi illo! Ea, fuga laboriosam? Quasi, aut
                                        dolorum.</p>
                                </div>
                            </a-col>
                            <a-col :span="12" class="p-5"><div class="border border-gray-300 rounded-md p-2"><img :src="'/storage/third.png'"></div></a-col>
                        </a-row>
                    </div>
                    <p class="text-end mr-2 text-gray-200">Martynas Gaulia IFAi-9</p>
                </div>
            </div>
    </div>
</AuthenticatedLayout></template>
