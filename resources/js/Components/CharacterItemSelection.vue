<template>
    <div>
        <h1 class="font-bold">Veidai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsHead" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture" @click="emit('changeBodyPart',{category: item.category, partID: item.id,picture: item.picture})">
            </div>
        </div>
        <h1 class="font-bold">Viršutiniai drabužiai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsTop" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture" @click="emit('changeBodyPart',{category: item.category, partID: item.id,picture: item.picture})">
            </div>
        </div>
        <h1 class="font-bold">Apatiniai drabužiai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsBottom" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture" @click="emit('changeBodyPart',{category: item.category, partID: item.id,picture: item.picture})">
            </div>
        </div>
        <h1 class="font-bold">Batai:</h1>
        <div class="grid grid-cols-5">
            <div v-for="item in props.itemsShoes" v-bind:key="item" class="w-24">
                <img :src="'/storage/' + item.picture" @click="emit('changeBodyPart',{category: item.category, partID: item.id,picture: item.picture})">
            </div>
        </div>
    </div>
</template>

<script setup>
const emit = defineEmits(['changeBodyPart']);
const props = defineProps({ itemsHead: Object, itemsTop: Object, itemsBottom: Object, itemsShoes: Object });
</script>

<style>

</style>