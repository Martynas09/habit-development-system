<template>
    <div class="pl-12">
        <h1 class="font-bold">Jūsų personažas:</h1>
        <div class="max-h-[900px] min-h-[750px]">
            <div class="relative">
                <div class="absolute top-[680px] left-[55px]"><img :src="'/storage/' + shoes"></div>
                <div class="absolute top-[333px] left-[71px]"><img :src="'/storage/' + bottom"></div>
                <div class="absolute top-[13px] left-[75px]"><img :src="'/storage/' + head"></div>
                <div class="absolute top-[141px] left-[21px]"><img :src="'/storage/' + top"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
  head: String, top: String, bottom: String, shoes: String,
});
</script>
<style></style>
