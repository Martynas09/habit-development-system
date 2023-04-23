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
            <h1 class="text-xl pl-2">Naujas užrašas:</h1>
            <a-comment>
              <template #avatar>
              </template>
              <template #content>
                <a-form ref="formRef" :model="form">
                  <a-form-item :rules="{ required: true, message: 'Prašome įvesti užrašą' }" name="value">
                    <a-textarea v-model:value="value" :rows="3" />
                  </a-form-item>
                </a-form>
                <a-form-item>
                  <a-button html-type="submit" :loading="form.processing" type="primary" @click="handleSubmit">
                    Pridėti užrašą
                  </a-button>
                </a-form-item>
              </template>
            </a-comment>
            <h1 class="text-xl pl-2">Jūsų užrašai:</h1>
            <div class="pl-4">
              <a-list v-if="notes.data.length" :data-source="notes.data" item-layout="horizontal">
                <template #renderItem="{ item, index }">
                  <a-list-item>
                    <a-comment>
                      <template #author><span>{{ $page.props.auth.user.username
                      }}</span></template>
                      <template #avatar>
                        <img :src="'/storage/' + $page.props.auth.user.avatar">
                      </template>
                      <template #content>
                        <div v-if="editingList[index] === false">{{ item.description }}</div>
                        <div v-else class="flex gap-4">
                          <a-input v-model:value="item.description" style="margin-left: 0px;margin-top:0px" />
                        </div>
                        <div v-if="item.description.length < 1" class="text-red-500">Prašome įvesti užrašą</div>
                      </template>
                      <template #datetime>
                        <a-tooltip :title="dayjs().format('YYYY-MM-DD HH:mm:ss')">
                          <span>{{ dayjs(item.updated_at).fromNow() }}</span>
                        </a-tooltip>
                      </template>
                    </a-comment>
                    <div class="flex">
                      <div class="pr-2">
                        <a-button v-if="editingList[index] === false" @click="editingList[index] = true" type="primary"
                          shape="circle">
                          <template #icon>
                            <edit-outlined />
                          </template>
                        </a-button>
                        <a-button v-else html-type="submit" :loading="form.processing" type="primary" shape="circle"
                          @click="handleEdit(item.description, item.id, index)">
                          <template #icon>
                            <save-outlined />
                          </template>
                        </a-button>
                      </div>
                      <a-popconfirm title="Ar tikrai norite pašalinti užrašą?" ok-text="Taip" cancel-text="Ne"
                        @confirm="confirm(item.id)" @cancel="cancel">
                        <a-button type="danger" shape="circle">
                          <template #icon>
                            <delete-outlined />
                          </template>
                        </a-button>
                      </a-popconfirm>
                    </div>
                  </a-list-item>
                </template>
              </a-list>
            </div>
            <div class="flex justify-end"><a-pagination v-model:current="currentPage" @change="triggerPagination"
                :total="notes.total" :defaultPageSize="notes.per_page" :hideOnSinglePage="true" :size="'small'" /></div>
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
  HomeOutlined, ReadOutlined, DeleteOutlined, EditOutlined, SaveOutlined,
} from '@ant-design/icons-vue';
import dayjs from 'dayjs';
import { ref, defineProps, onMounted } from 'vue';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message } from 'ant-design-vue';
import 'dayjs/locale/lt';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

dayjs.extend(relativeTime);
dayjs.locale('lt');

const props = defineProps({
  notes: Object,
});
const value = ref('');
const editingList = ref([
]);
const currentPage = ref(props.notes.current_page);
const form = useForm({
  value,
});
const formRef = ref();
onMounted(() => {
  editingList.value = props.notes.data.map(() => false);
});

const triggerPagination = (page) => {
  router.get('/journal', { page });
};
const handleSubmit = () => {
  formRef.value.validateFields()
    .then(() => {
      form.post(
        '/journal',
        {
          preserveScroll: true,
          onSuccess: () => message.success('Užrašas sėkmingai pridėtas'),
          onError: () => message.error('Klaida pridedant užrašą'),
        },
      );
      value.value = '';
      editingList.value.push(false);
    })
    .catch(() => {
    });
  // form.post(
  //   '/journal',
  //   {
  //     preserveScroll: true,
  //     onSuccess: () => message.success('Užrašas sėkmingai pridėtas'),
  //     onError: () => message.error('Klaida pridedant užrašą'),
  //   },
  // );
  // value.value = '';
  // editingList.value.push(false);
};
const confirm = (id) => {
  router.post(
    '/noteDelete/',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Užrašas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant užrašą'),
    },
  );
  if (currentPage.value > 1 && props.notes.data.length === 1) {
    router.get('/journal', { page: currentPage.value - 1 });
  }
};
const cancel = (e) => {
  console.log(e);
  message.error('Click on No');
};
const handleEdit = (description, id, index) => {
  router.post(
    '/noteEdit/',
    {
      description,
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Užrašas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant užrašą'),
    },
  );
  editingList.value[index] = false;
};
</script>
