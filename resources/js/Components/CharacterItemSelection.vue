<template>
    <div>
        <h1 class="font-bold">Veidai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsHead" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture"
                    @click="emit('changeBodyPart', { category: item.category, partID: item.id, picture: item.picture });activeHead = item.id" :class="{active: activeHead === item.id }">
            </div>
        </div>
        <h1 class="font-bold">Viršutiniai drabužiai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsTop" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture"
                    @click="emit('changeBodyPart', { category: item.category, partID: item.id, picture: item.picture });activeTop = item.id" :class="{active: activeTop === item.id }">
            </div>
        </div>
        <h1 class="font-bold">Kelnės:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsBottom" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture"
                    @click="emit('changeBodyPart', { category: item.category, partID: item.id, picture: item.picture });activeBottom = item.id" :class="{active: activeBottom === item.id }">
            </div>
        </div>
        <h1 class="font-bold">Batai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsShoes" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture"
                    @click="emit('changeBodyPart', { category: item.category, partID: item.id, picture: item.picture });activeShoes = item.id" :class="{active: activeShoes === item.id }">
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const emit = defineEmits(['changeBodyPart']);
const props = defineProps({
  itemsHead: Object, itemsTop: Object, itemsBottom: Object, itemsShoes: Object, character: Object,
});
const activeHead = ref();
const activeTop = ref();
const activeBottom = ref();
const activeShoes = ref();
onMounted(() => {
  activeHead.value = props.character.head;
  activeTop.value = props.character.top;
  activeBottom.value = props.character.bottom;
  activeShoes.value = props.character.shoes;
});
</script>

<style>
.active{
    border: 2px solid #0071c5;
}
</style>
