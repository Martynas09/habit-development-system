<template>
  <div>
    <div class="flex relative" style="font-size: 12px; align-items: center;">
      <div style="margin-right: 6px;">
        <div class="absolute top-2" style="font-size: 13px;">
          <span class="font-bold">{{ xp }}</span>
          <span v-if="xp===0"> patirties taškų</span>
          <span v-else> patirties taškai</span>
        </div>
        <div class="pt-5">
          <a-progress :percent="displayPercent" size="small" :status="xp <= 400?'active':'success'" :showInfo="false"
            style="margin-bottom:0px;margin-top: 0px; width: 140px;" />
          <div style="display: flex; justify-content: space-between;">
            <span v-if="xp <= 400">{{ displayLeftSide }}</span>
            <span v-else>Maksimalus lygis</span>
            <span v-if="xp <= 400">{{ displayRightSide }}</span>
          </div>
        </div>
      </div>
      <div>
        <img class="w-8" :src="'/storage/level' + displayLevel + '.png'">
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, computed } from 'vue';
import useExperience from '../Composables/useExperience';

const { xp } = useExperience();
const { pullXP } = useExperience();
const displayLevel = computed(() => {
  if (xp.value < 100) {
    return 1;
  }
  if (xp.value >= 100 && xp.value < 200) {
    return 2;
  }
  if (xp.value >= 200 && xp.value < 300) {
    return 3;
  }
  if (xp.value >= 300 && xp.value < 400) {
    return 4;
  }
  if (xp.value >= 400) {
    return 5;
  }
  return 1;
});
const displayLeftSide = computed(() => {
  if (xp.value < 100) {
    return 0;
  }
  if (xp.value >= 100 && xp.value < 200) {
    return 100;
  }
  if (xp.value >= 200 && xp.value < 300) {
    return 200;
  }
  if (xp.value >= 300 && xp.value < 400) {
    return 300;
  }
  return 0;
});
const displayRightSide = computed(() => {
  if (xp.value < 100) {
    return 100;
  }
  if (xp.value >= 100 && xp.value < 200) {
    return 200;
  }
  if (xp.value >= 200 && xp.value < 300) {
    return 300;
  }
  if (xp.value >= 300 && xp.value < 400) {
    return 400;
  }
  return 0;
});
const displayPercent = computed(() => {
  if (xp.value < 100) {
    return (xp.value / 100) * 100;
  }
  if (xp.value >= 100 && xp.value < 200) {
    return ((xp.value - 100) / 100) * 100;
  }
  if (xp.value >= 200 && xp.value < 300) {
    return ((xp.value - 200) / 100) * 100;
  }
  if (xp.value >= 300 && xp.value < 400) {
    return ((xp.value - 300) / 100) * 100;
  }
  if (xp.value >= 400) {
    return 100;
  }
  return 0;
});
onMounted(() => {
  pullXP();
});

</script>
