<template>
    <Head title="Žurnalas" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <read-outlined style="font-size:20px" />
                    Žurnalas
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="pl-6 py-6 max-w-2xl">
                        <!-- <a-comment class="p-4 max-w-2xl">
                        <template #actions>
                        </template>
                        <template #author><span>{{ $page.props.auth.user.username }}</span></template>
                        <template #avatar>
                            <img :src="'/storage/' + $page.props.auth.user.avatar"
                                class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                                style="padding-top:1px;padding-bottom:1px">
                        </template>
                        <template #content>
                            <p>
                                We supply a series of design principles, practical patterns and high quality design
                                resources (Sketch and Axure), to help people create their product prototypes beautifully and
                                efficiently.
                            </p>
                        </template>
                        <template #datetime>
                            <a-tooltip :title="dayjs().format('YYYY-MM-DD HH:mm:ss')">
                                <span>{{ dayjs().fromNow() }}</span>
                            </a-tooltip>
                        </template>
                    </a-comment> -->
                        <h1 class="text-xl pl-2">Naujas užrašas:</h1>
                        <a-comment>
                            <template #avatar>
                            </template>
                            <template #content>
                                <a-form-item>
                                    <a-textarea v-model:value="value" :rows="3" />
                                </a-form-item>
                                <a-form-item>
                                    <a-button html-type="submit" :loading="form.processing" type="primary"
                                        @click="handleSubmit">
                                        Pridėti užrašą
                                    </a-button>
                                </a-form-item>
                            </template>
                        </a-comment>
                        <h1 class="text-xl pl-2">Jūsų užrašai:</h1>
                        <div class="pl-4">
                        <a-list v-if="notes.data.length" :data-source="notes.data" item-layout="horizontal">
                            <template #renderItem="{ item }">
                                <a-list-item>
                                    <a-comment :content="item.description">
                                        <template #author><span>{{ $page.props.auth.user.username }}</span></template>
                                        <template #avatar>
                                            <img :src="'/storage/' + $page.props.auth.user.avatar"
                                                class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                                                style="padding-top:1px;padding-bottom:1px">
                                        </template>
                                        <template #datetime>
                                            <a-tooltip :title="dayjs().format('YYYY-MM-DD HH:mm:ss')">
                                                <span>{{ dayjs(item.updated_at).fromNow() }}</span>
                                            </a-tooltip>
                                        </template>
                                    </a-comment>
                                </a-list-item>
                            </template>
                        </a-list>
                        </div>
                        <div class="flex justify-end"><a-pagination v-model:current="currentPage"
                                @change="triggerPagination" :total="notes.total" :defaultPageSize="notes.per_page"
                                :hideOnSinglePage="true" :size="'small'" /></div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import {
  Head, Link, useForm, router,
} from '@inertiajs/vue3';
import {
  HomeOutlined, ReadOutlined,
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { ref, defineProps } from 'vue';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

dayjs.extend(relativeTime);
const props = defineProps({
  notes: Object,
});
const value = ref('');
const currentPage = ref(props.notes.current_page);
const form = useForm({
  value,
});
const triggerPagination = (page) => {
  router.get('/journal', { page });
};
const handleSubmit = () => {
  form.post(
    '/journal',
    {
      preserveScroll: true,
      onSuccess: () => message.success('Užrašas sėkmingai pridėtas'),
      onError: () => message.error('Klaida pridedant užrašą'),
    },
  );
  value.value = '';
};
</script>
