<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Personažo redagavimas</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="grid grid-cols-2">
                        <div>
                            <CharacterPreview 
                            :head="head"
                            :top="top"
                            :bottom="bottom"
                            :shoes="shoes" />
                        </div>
                        <div>
                            <CharacterItemSelection 
                            :itemsHead="itemsHead"
                            :itemsTop="itemsTop"
                            :itemsBottom="itemsBottom"
                            :itemsShoes="itemsShoes"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CharacterPreview from '@/Components/CharacterPreview.vue';
import CharacterItemSelection from '@/Components/CharacterItemSelection.vue';
import { ref, onMounted } from 'vue';
import { message } from 'ant-design-vue';
// import { Link } from '@inertiajs/inertia-vue3'; gali vaiksciot per puslapius no reload look github

const props = defineProps({ character: Object, itemsHead: Object, itemsTop: Object, itemsBottom: Object, itemsShoes: Object });
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
})

function changeBodyPart(part, partID, partUrl) {
    switch (part) {
        case "head":
            head.value = partUrl;
            headId.value = partID;
            break;
        case "body":
            // code block
            break;
        default:
        // code block

    }
}

const form = useForm({
    headID: headID.value,
    topID: topID.value,
    bottomID: bottomID.value,
    shoesID: shoesID.value,
});

const saveToDB = () => {
    form.post(
        '/api/saveChargerApperiance',
        {
            preserveScroll: true,
            onSuccess: () => message.success('Comment deleted successfully'),
            onError: () => message.error('Something went wrong'),
        },
    );
};

// public function saveChargerApperiance(Request $request)
//     {
//         $request->validate([
//             'headID' => 'required',
//             'topID' => 'required',
//         ]);
//         $post = new Character_item();
//         $post->head = $request->headID;
//         $post->content = $request->content;
//         $post->user_id = auth()->user()->id;
//         $post->files = $request->jsonFiles;
//         $post->save();
//     }



console.log(props.itemsHead)
</script>