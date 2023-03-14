<template>
    <div class="pl-12">
        <h1 class="font-bold">Jūsų personažas:</h1>
        <div class="relative min-h-screen">
            <div class="absolute bottom-[90px] left-[50px]"><img :src="'/storage/' + shoes"></div>
            <div class="absolute bottom-[185px] left-[62px]"><img :src="'/storage/' + bottom"></div>
            <div class="absolute top-[155px] left-[3px]"><img :src="'/storage/' + top"></div>
            <div class="absolute top-[13px] left-[75px]"><img :src="'/storage/' + head"></div>
        </div>
    </div>
</template>

<script setup>
defineProps({
  head: String, top: String, bottom: String, shoes: String,
});
</script>
<style></style>
