<template>
    <Head title="Klausimų valdymas" />

    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px" />
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">
                    <question-circle-outlined style="font-size:20px" />
                    Klausimų valdymas
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="flex pb-1">
                    <h3 class="pl-2 text-xl flex-grow">Refleksijos klausimai:</h3>
                    <div class="flex-shrink-0 justify-end pr-2">
                        <a-button @click="showModal" type="primary">Pridėti klausimą</a-button>
                        <a-modal v-model:visible="visible" title="Naujas pasiekimas" @ok="handleOk" ok-text="Sukurti"
                            cancel-text="Atšaukti">
                            <a-form>
                                <a-form-item label="Pavadinimas" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                                    <a-input v-model:value="achievementTitle" />
                                </a-form-item>
                                <a-form-item label="Aprašymas" :label-col="{ span: 7 }" :wrapper-col="{ span: 12 }">
                                    <a-textarea v-model:value="achievementDescription" />
                                </a-form-item>
                                <a-form-item label="Skiriami patirties taškai" :label-col="{ span: 7 }"
                                    :wrapper-col="{ span: 12 }">
                                    <a-input-number v-model:value="achievementRewardXP" />
                                </a-form-item>
                            </a-form>
                        </a-modal>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                    <div class="p-4 flex justify-center">
                        <a-table :columns="columns" :data-source="questions" :pagination="false">
                            <template #bodyCell="{ column, record, index }">
                                <template v-if="column.key === 'number'">
                                    <div v-if="editingList[index] === false">{{ record.number }}</div>
                                    <div v-else>
                                        <a-input-number v-model:value="record.number"
                                            style="margin-left: 0px;margin-top:0px" />
                                    </div>
                                </template>
                                <template v-if="column.key === 'required'">
                                    <div v-if="editingList[index] === false">
                                        <span v-if="record.required === 1">Taip</span>
                                        <span v-if="record.required === 0">Ne</span>
                                    </div>
                                    <div v-else>
                                        <a-select v-model:value="record.required" style="margin-left: 0px;margin-top:0px">
                                            <a-select-option value="1">Taip</a-select-option>
                                            <a-select-option value="0">Ne</a-select-option>
                                        </a-select>
                                    </div>
                                </template>
                                <template v-if="column.key === 'answers'">
                                    <a-button @click="showAnswers(record.answers)">Peržiūrėti</a-button>
                                </template>
                                <template v-if="column.key === 'created_at'">
                                    <div class="min-w-[78px]">
                                        <span>
                                            {{ formatDate(record.created_at) }}
                                        </span>
                                    </div>
                                </template>
                                <template v-if="column.key === 'action'">
                                    <div class="flex">
                                        <div class="pr-2">
                                            <a-button v-if="editingList[index] === false" @click="editingList[index] = true"
                                                type="primary" shape="circle">
                                                <template #icon>
                                                    <edit-outlined />
                                                </template>
                                            </a-button>
                                            <a-button v-else html-type="submit" type="primary" shape="circle"
                                                @click="handleEdit(record.rewardXP, record.description, record.title, record.id, index)"><template
                                                    #icon>
                                                    <save-outlined />
                                                </template></a-button>
                                        </div>
                                        <a-popconfirm title="Ar tikrai norite pašalinti tikslą?" ok-text="Taip"
                                            cancel-text="Ne" @confirm="confirm(record.id)" @cancel="cancel">
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
                        <!-- <div class="flex justify-end"><a-pagination v-model:current="currentPage"
                                  @change="triggerPagination" :total="achievements.total"
                                  :defaultPageSize="achievements.per_page" :hideOnSinglePage="true" :size="'small'" /></div> -->
                    </div>
                    <a-modal v-model:visible="visibleAnswers" title="Atsakymai" @ok="handleOkAnswers" ok-text="Sukurti"
                        cancel-text="Atšaukti">
                        <a-list size="small" :data-source="answers">
                            <template #renderItem="{ item, index }">
                                <a-list-item>
                                    <div class="flex items-center">
                                        <div v-if="editingListAnswers[index] === false">{{ item.content }}</div>
                                        <div v-else>
                                            <a-textarea v-model:value="item.content"
                                                style="margin-left: 0px;margin-top:0px" :row="2"/>
                                        </div>
                                        <div class="pr-2">
                                            <a-button v-if="editingListAnswers[index] === false"
                                                @click="editingListAnswers[index] = true" type="primary" shape="circle">
                                                <template #icon>
                                                    <edit-outlined />
                                                </template>
                                            </a-button>
                                            <a-button v-else html-type="submit" type="primary" shape="circle"
                                                @click="handleEditAnswer(item.id, item.content, index)"><template
                                                    #icon>
                                                    <save-outlined />
                                                </template></a-button>
                                        </div>
                                        <a-popconfirm title="Ar tikrai norite pašalinti atsakymą?" ok-text="Taip"
                                            cancel-text="Ne" @confirm="confirmAnswer(item.id)" @cancel="cancel">
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
                        <template #footer>
                        </template>
                    </a-modal>
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
  HomeOutlined, DeleteOutlined, EditOutlined, SaveOutlined, QuestionCircleOutlined,
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
  questions: Object,
});
function formatDate(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
const answers = ref([]);
const editingListAnswers = ref([]);
const editingList = ref([
]);
// const currentPage = ref(props.achievements.current_page);
const columns = [{
  title: 'Eilės nr.',
  dataIndex: 'number',
  key: 'number',
}, {
  title: 'Klausimas',
  dataIndex: 'content',
  key: 'content',
}, {
  title: 'Privalomas',
  dataIndex: 'required',
  key: 'required',
}, {
  title: 'Atsakymai',
  dataIndex: 'answers',
  key: 'answers',
}, {
  title: 'Sukūrimo data',
  dataIndex: 'created_at',
  key: 'created_at',
}, {
  title: 'Veiksmai',
  key: 'action',
}];

onMounted(() => {
  for (let i = 0; i < props.questions.length; i += 1) {
    editingList.value.push(false);
  }
});

// const triggerPagination = (page) => {
//   router.get('/achievements', { page });
// };
const visible = ref(false);
const visibleAnswers = ref(false);
const achievementTitle = ref('');
const achievementDescription = ref('');
const achievementRewardXP = ref();
const showModal = () => {
  visible.value = true;
};
const handleOk = () => {
  router.post(
    '/achievementAdd',
    {
      title: achievementTitle.value,
      description: achievementDescription.value,
      rewardXP: achievementRewardXP.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas sėkmingai pridėtas'),
      onError: () => message.error('Klaida pridedant pasiekimą'),
    },
  );
  achievementTitle.value = '';
  achievementDescription.value = '';
  achievementRewardXP.value = 0;
  editingList.value.push(false);
  visible.value = false;
};
const confirm = (id) => {
  router.post(
    '/achievementDelete/',
    {
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas pašalintas sėkmingai'),
      onError: () => message.error('Klaida pašalinant pasiekimą'),
    },
  );
};
const cancel = () => {
  message.error('Click on No');
};
const handleEdit = (rewardXP, description, title, id, index) => {
  router.post(
    '/achievementEdit/',
    {
      rewardXP,
      title,
      description,
      id,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Pasiekimas sėkmingai paredaguotas'),
      onError: () => message.error('Klaida redaguojant pasiekimą'),
    },
  );
  editingList.value[index] = false;
};
const showAnswers = (recordAnswers) => {
  answers.value = recordAnswers;
  for (let i = 0; i < answers.value.length; i += 1) {
    editingListAnswers.value.push(false);
  }
  visibleAnswers.value = true;
};

</script>
