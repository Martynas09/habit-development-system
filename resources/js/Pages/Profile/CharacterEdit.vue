<template>

    <Head title="Personažo redagavimas" />
    <AuthenticatedLayout>
        <template #header>
            <a-breadcrumb>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('dashboard')">
                    <home-outlined style="font-size:20px"/>
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item href="" class="text-xl">
                    <Link :href="route('profile.edit')">
                    <user-outlined style="font-size:20px;margin-right:5px"/>
                    <span>Profilis</span>
                    </Link>
                </a-breadcrumb-item>
                <a-breadcrumb-item class="text-xl">Personažo redagavimas</a-breadcrumb-item>
            </a-breadcrumb>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="grid grid-cols-2">
                        <div>
                            <CharacterPreview :head="head" :top="top" :bottom="bottom" :shoes="shoes" />
                        </div>
                        <div>
                            <CharacterItemSelection :itemsHead="itemsHead" :itemsTop="itemsTop"
                                :itemsBottom="itemsBottom" :itemsShoes="itemsShoes"
                                @changeBodyPart="(e) => changeBodyPart(e.category, e.partID, e.picture)" />
                            <div class="mt-10">
                                <a-button @click="saveToDB" type="primary">Išsaugoti pakeitimus</a-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { message } from 'ant-design-vue';
import { HomeOutlined, UserOutlined } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CharacterPreview from '@/Components/CharacterPreview.vue';
import CharacterItemSelection from '@/Components/CharacterItemSelection.vue';

const props = defineProps({
  character: Object, itemsHead: Object, itemsTop: Object, itemsBottom: Object, itemsShoes: Object,
});
const head = ref();
const headID = ref();

const top = ref();
const topID = ref();

const bottom = ref();
const bottomID = ref();

const shoes = ref();
const shoesID = ref();

onMounted(() => {
  head.value = props.character.get_head[0].picture;
  top.value = props.character.get_top[0].picture;
  bottom.value = props.character.get_bottom[0].picture;
  shoes.value = props.character.get_shoes[0].picture;

  headID.value = props.character.get_head[0].id;
  topID.value = props.character.get_top[0].id;
  bottomID.value = props.character.get_bottom[0].id;
  shoesID.value = props.character.get_shoes[0].id;
});

function changeBodyPart(category, partID, partUrl) {
  switch (category) {
    case 'head':
      head.value = partUrl;
      headID.value = partID;
      break;
    case 'bottom':
      bottom.value = partUrl;
      bottomID.value = partID;
      break;
    case 'top':
      top.value = partUrl;
      topID.value = partID;
      break;
    case 'shoes':
      shoes.value = partUrl;
      shoesID.value = partID;
      break;
    default:
      break;
  }
}

const saveToDB = () => {
  router.post(
    '/profile/characterEdit',
    {
      head: headID.value,
      top: topID.value,
      bottom: bottomID.value,
      shoes: shoesID.value,
    },
    {
      preserveScroll: true,
      onSuccess: () => message.success('Personažas atnaujintas sėkmingai'),
      onError: () => message.error('Klaida atnaujinant personažą'),
    },
  );
};
</script>
