<template>
    <div class="pl-12">
        <h1 class="font-bold">Jūsų personažas:</h1>
    <div class="w-36">
        <img :src="'/storage/' + head">
        <img :src="'/storage/' + top">
        <img :src="'/storage/' + bottom">
        <img :src="'/storage/' + shoes">
    </div>
</div>
</template>

<script setup>
const props = defineProps({ head: String, top: String, bottom: String, shoes: String });
</script>
<style>
</style>
