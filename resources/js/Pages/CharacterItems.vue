<template>
  <Head title="Personažo daiktų valdymas" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <database-outlined style="font-size:20px" />
          Personažo daiktų valdymas
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="flex pb-1">
          <h3 class="pl-2 text-xl flex-grow">Personažo daiktai:</h3>
          <div class="flex-shrink-0 justify-end pr-2">
            <a-button @click="showModal" type="primary">Pridėti daiktą</a-button>
            <a-modal v-model:visible="visible" title="Naujas personažo daiktas" @ok="handleOk" ok-text="Sukurti"
              cancel-text="Atšaukti">
              <a-form>
                <a-form-item label="Pavadinimas" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-input v-model:value="itemTitle" placeholder="Įrašykite pavadinimą" />
                </a-form-item>
                <a-form-item label="Retumas" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-select v-model:value="itemRarity" placeholder="Pasirinkite retumą">
                    <a-select-option value="common">Įprastas</a-select-option>
                    <a-select-option value="uncommon">Neįprastas</a-select-option>
                    <a-select-option value="rare">Retas</a-select-option>
                    <a-select-option value="very rare">Labai retas</a-select-option>
                  </a-select>
                </a-form-item>
                <a-form-item label="Kategorija" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-select v-model:value="itemCategory" placeholder="Pasirinkite kategoriją">
                    <a-select-option value="head">Veidas</a-select-option>
                    <a-select-option value="top">Viršutinė dalis</a-select-option>
                    <a-select-option value="bottom">Apatinė dalis</a-select-option>
                    <a-select-option value="shoes">Batai</a-select-option>
                  </a-select>
                </a-form-item>
                <a-form-item label="Paveikslėlis" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <div class="clearfix">
                    <a-upload :disabled="disabledUpload" @change="handleUploadChange" v-model:file-list="fileList"
                      action="/api/addImage" list-type="picture" accept="image/*" class="upload-list-inline"
                      :max-count="3">
                      <a-button :disabled="fileList.length === 1">
                        <PictureOutlined></PictureOutlined>
                        Įkelti paveikslėlį
                      </a-button>
                    </a-upload>
                  </div>
                  <span v-if="itemCategory === 'head'">Rekomenduojami išmatavimai: 125x152</span>
                  <span v-if="itemCategory === 'top'">Rekomenduojami išmatavimai: 268x345</span>
                  <span v-if="itemCategory === 'bottom'">Rekomenduojami išmatavimai: 154x300</span>
                  <span v-if="itemCategory === 'shoes'">Rekomenduojami išmatavimai: 178x103</span>
                </a-form-item>
                <a-form-item label="Lygis" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                  <a-select v-model:value="itemLevel" placeholder="Pasirinkite lygį">
                    <a-select-option v-for="level in levels" :key="level" :value="level.id">{{ level.title
                    }}</a-select-option>
                  </a-select>
                </a-form-item>
              </a-form>
            </a-modal>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
          <div class="p-4 flex justify-center">
            <a-table :columns="columns" :data-source="items" :pagination="false">
              <template #bodyCell="{ column, record, index }">
                <template v-if="column.key === 'title'">
                  <div v-if="editingList[index] === false">{{ record.title }}</div>
                  <div v-else>
                    <a-input v-model:value="record.title" style="margin-left: 0px;margin-top:0px" />
                  </div>
                </template>
                <template v-if="column.key === 'picture'">
                  <div v-if="editingList[index] === false">
                    <div class="flex items-center justify-center ml-2 w-16">
                      <img :src="'/storage/' + record.picture" style="padding-top:1px;padding-bottom:1px">
                    </div>
                  </div>
                  <div v-else>
                    <a-upload :disabled="disabledUpload" @change="handleUploadChange" v-model:file-list="fileList"
                      action="/api/addImage" list-type="picture" accept="image/*" class="upload-list-inline"
                      :max-count="3">
                      <a-button :disabled="fileList.length === 1">
                        <PictureOutlined></PictureOutlined>
                        Pakeisti
                      </a-button>
                    </a-upload>
                  </div>
                </template>
                <template v-if="column.key === 'rarity'">
                  <div v-if="editingList[index] === false">
                    <span v-if="record.rarity === 'common'">Įprastas</span>
                    <span v-if="record.rarity === 'uncommon'">Neįprastas</span>
                    <span v-if="record.rarity === 'rare'">Retas</span>
                    <span v-if="record.rarity === 'very rare'">Labai retas</span>
                  </div>
                  <div v-else>
                    <a-select v-model:value="record.rarity" placeholder="Pasirinkite retumą">
                      <a-select-option value="common">Įprastas</a-select-option>
                      <a-select-option value="uncommon">Neįprastas</a-select-option>
                      <a-select-option value="rare">Retas</a-select-option>
                      <a-select-option value="very rare">Labai retas</a-select-option>
                    </a-select>
                  </div>
                </template>
                <template v-if="column.key === 'category'">
                  <div v-if="editingList[index] === false">
                    <span v-if="record.category === 'head'">Veidas</span>
                    <span v-if="record.category === 'top'">Viršutinė dalis</span>
                    <span v-if="record.category === 'bottom'">Apatinė dalis</span>
                    <span v-if="record.category === 'shoes'">Batai</span>
                  </div>
                  <div v-else>
                    <a-select v-model:value="record.category" placeholder="Pasirinkite kategoriją">
                      <a-select-option value="head">Veidas</a-select-option>
                      <a-select-option value="top">Viršutinė dalis</a-select-option>
                      <a-select-option value="bottom">Apatinė dalis</a-select-option>
                      <a-select-option value="shoes">Batai</a-select-option>
                    </a-select>
                  </div>
                </template>
                <template v-if="column.key === 'fk_level'">
                  <div v-if="editingList[index] === false">
                    <span>
                      {{ record.fk_level }} lygis
                    </span>
                  </div>
                  <div v-else>
                    <a-select v-model:value="record.fk_level" placeholder="Pasirinkite lygį">
                      <a-select-option v-for="level in levels" :key="level" :value="level.id">{{ level.title
                      }}</a-select-option>
                    </a-select>
                  </div>
                </template>
                <template v-if="column.key === 'created_at'">
                  <div class="min-w-[77px]">
                    <span>
                      {{ formatDate(record.created_at) }}
                    </span>
                  </div>
                </template>
                <template v-if="column.key === 'updated_at'">
                  <div class="min-w-[77px]">
                    <span>
                      {{ formatDate(record.updated_at) }}
                    </span>
                  </div>
                </template>
                <template v-if="column.key === 'action'">
                  <div class="flex">
                    <div class="pr-2">
                      <a-button v-if="editingList[index] === false" @click="editingList[index] = true" type="primary"
                        shape="circle">
                        <template #icon>
                          <edit-outlined />
                        </template>
                      </a-button>
                      <a-button v-else html-type="submit" type="primary" shape="circle"
                        @click="handleEdit(record.title, record.picture, record.rarity, record.category,record.fk_level ,record.id, index)"><template #icon>
                          <save-outlined />
                        </template></a-button>
                    </div>
                    <a-popconfirm title="Ar tikrai norite pašalinti naudotoją?" ok-text="Taip" cancel-text="Ne"
                      @confirm="confirm(record.id)" @cancel="cancel">
                      <a-button type="danger" shape="circle">
                        <template #icon>
                          <delete-outlined />
                        </template>
                      </a-button>
                    </a-popconfirm>
                  </div>
                </template>
              </template>
            </a-table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import {
  Head, Link, router,
} from '@inertiajs/vue3';
import {
  HomeOutlined, DatabaseOutlined, DeleteOutlined, EditOutlined, SaveOutlined, PictureOutlined,
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
  items: Object, levels: Object,
});
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const editingList = ref([
]);
// const currentPage = ref(props.users.current_page);
const columns = [{
  title: 'Pavadinimas',
  dataIndex: 'title',
  key: 'title',
}, {
  title: 'Paveikslėlis',
  dataIndex: 'picture',
  key: 'picture',
}, {
  title: 'Retumas',
  dataIndex: 'rarity',
  key: 'rarity',
}, {
  title: 'Kategorija',
  dataIndex: 'category',
  key: 'category',
}, {
  title: 'Reikalingas lygis',
  dataIndex: 'fk_level',
  key: 'fk_level',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
},
{
  title: 'Atnaujinimo data',
  dataIndex: 'updated_at',
  key: 'updated_at',
},
{
  title: 'Veiksmai',
  key: 'action',
}];

onMounted(() => {
  for (let i = 0; i < props.items.length; i += 1) {
    editingList.value.push(false);
  }
});
const fileList = ref([]);
const loading = ref();
const disabledUpload = ref(false);
const handleUploadChange = (e) => {
  if (e.fileList.length >= 3) { disabledUpload.value = true; }

  switch (e.file.status) {
    case 'uploading':
      loading.value = true;
      break;
    case 'done':
      loading.value = false;
      break;
    default:
      loading.value = false;
      break;
  }
};
// const triggerPagination = (page) => {
//   router.get('/achievements', { page });
// };
const visible = ref(false);
const itemTitle = ref();
const itemCategory = ref();
const itemRarity = ref();
const itemLevel = ref();
const showModal = () => {
  visible.value = true;
};
const handleOk = () => {
  let fileArray = [];
  fileList.value.forEach((file) => {
    fileArray.push(file.response.file);
  });

  if (fileArray.length === 0) {
    fileArray = null;
  }
  router.post(
    '/characterItemAdd',
    {
      title: itemTitle.value,
      category: itemCategory.value,
      rarity: itemRarity.value,
      level: itemLevel.value,
      image: JSON.stringify(fileArray),
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Daiktas sėkmingai pridėtas'),
      onError: () => message.error('Klaida pridedant daiktą'),
    },
  );
  editingList.value.push(false);
  visible.value = false;
  fileList.value = [];
};
const confirm = (id) => {
  router.post(
    '/characterItemDelete',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Daiktas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant daiktą'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
const handleEdit = (title, picture, rarity, category, fk_level, id, index) => {
  let fileArray = [];
  fileList.value.forEach((file) => {
    fileArray.push(file.response.file);
  });

  if (fileArray.length === 0) {
    fileArray = null;
  }
  router.post(
    '/characterItemEdit',
    {
      id,
      title,
      picture,
      rarity,
      category,
      fk_level,
      image: JSON.stringify(fileArray),
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Daiktas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant daiktą'),
    },
  );
  editingList.value[index] = false;
  fileList.value = [];
};
</script>
