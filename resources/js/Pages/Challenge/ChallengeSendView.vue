<template>
    <Head title="Iššūkiai" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('Challenge.ChallengesListView')">
                    <dashboard-outlined style="font-size:20px" />
                    Iššūkiai
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">Naujo iššūkio metimas</a-breadcrumb-item>
            </a-breadcrumb>
        </template>
        <div class="pt-12 pb-12">
            <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="m-6">
                        <a-form>
                            <a-form-item style="margin-top:0px;margin-bottom:0px" name="title" label="Iššūkio pavadinimas:"
                                :rules="[{ required: true }]"></a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:10px"><a-input v-model:value="title"
                                    placeholder="Įrašykite pavadinimą" style="width: 95%; margin-left: 8px" /></a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:0px" name="description" label="Aprašymas:"
                                :rules="[{ required: true }]"></a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:10px" :name="['challenge', 'description']">
                                <a-textarea v-model:value="description" style="width: 95%;height: 10vh; margin-left: 8px;"
                                    placeholder="Įrašykite" />
                            </a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:0px" name="duration" label="Iššūkio trukmė:"
                                :rules="[{ required: true }]"></a-form-item>
                            <a-input-number style="margin-left: 8px;margin-right: 5px" v-model:value="duration" :min="1"
                                :max="360" placeholder="Įrašykite" /> min.
                            <a-form-item style="margin-top:10px;margin-bottom:0px" name="repetition"
                                label="Iššūkio pasikartojimas (kartai per savaitę)"
                                :rules="[{ required: true }]"></a-form-item>
                            <a-form-item style="width: 95%; margin-left: 8px"><a-slider v-model:value="timesPerWeek"
                                    :min="1" :max="7" :marks="{
                                        1: '1',
                                        2: '2',
                                        3: '3',
                                        4: '4',
                                        5: '5',
                                        6: '6',
                                        7: '7',
                                    }" /></a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:0px" name="xpGiven"
                                label="Skiriami patirties taškai:" :rules="[{ required: true }]"></a-form-item>
                            <a-form-item style="width: 95%; margin-left: 8px" name="xpGiven">
                                <a-slider v-model:value="xpGiven" :min="1" :max="10" :marks="{
                                    1: '1',
                                    10: '10',
                                }" />
                            </a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:0px" name="receivers"
                                label="Pasirinkite iššūkio gavėjus:" :rules="[{ required: true }]"></a-form-item>
                            <a-form-item style="margin-top:0px;margin-bottom:0px">
                                <a-select v-model:value="receivers" show-search placeholder="Pasirinkite gavėjus"
                                    style="width: 95%; margin-left: 8px;" mode="multiple" :filter-option="filterOption"
                                    @focus="handleFocus" @blur="handleBlur" @change="handleChange">
                                    <a-select-option v-for="user in users" v-bind:key="user.id">{{ user.value
                                    }}</a-select-option>
                                </a-select>
                            </a-form-item>
                            <div class="flex justify-end my-6 mr-4">
                                <a-button type="primary" @click="sendChallenge">Mesti iššūkį<send-outlined /></a-button>
                            </div>
                        </a-form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { DashboardOutlined, HomeOutlined, SendOutlined } from '@ant-design/icons-vue';
import { defineProps, ref } from 'vue';
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({ users: Object });
const title = ref('');
const description = ref('');
const xpGiven = ref();
const receivers = ref([]);
const timesPerWeek = ref();
const duration = ref();

const handleChange = (value) => {
  console.log(`selected ${value}`);
};
const handleBlur = () => {
  console.log('blur');
};
const handleFocus = () => {
  console.log('focus');
};
const form = useForm({
  title,
  description,
  duration,
  timesPerWeek,
  xpGiven,
  receivers,
});
const sendChallenge = () => {
  form.post(
    '/challenges/send',
    {
      preserveScroll: true,
      onSuccess: () => message.success('Iššūkis sėkmingai mestas'),
      onError: () => message.error('Klaida metant iššūkį'),
    },
  );
};
const filterOption = (input, option) => option.value.toLowerCase().indexOf(input.toLowerCase()) >= 0;
</script>
<style></style>
