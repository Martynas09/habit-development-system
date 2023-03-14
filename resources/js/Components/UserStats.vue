<template>
    <div>
        <div class="flex relative" style="font-size: 12px; align-items: center;">
            <div style="margin-right: 6px;">
                <div class="absolute top-2" style="font-size: 13px;"><span class="font-bold">{{ xp }}</span> patirties taškai</div>
                <div class="pt-5">
                    <a-progress :percent="percents" size="small" :status="status" :showInfo="false"
                        style="margin-bottom:0px;margin-top: 0px; width: 140px;" />
                    <div style="display: flex; justify-content: space-between;">
                        <span v-if="!isMaxLevel">{{xpStart}}</span>
                        <span v-else >Maksimalus lygis</span>
                        <span v-if="!isMaxLevel">{{xpEnd}}</span>
                    </div>
                </div>
            </div>
            <div>
            <img class="w-8" :src="'/storage/level'+level+'.png'">
          </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref } from 'vue';

const props = defineProps({
  user: Object,
});
const percents = ref(0);
const level = ref(0);
const xp = ref(0);
const status = ref('active');
const isMaxLevel = ref(false);
const xpStart = ref(0);
const xpEnd = ref(0);
onMounted(() => {
  xp.value = props.user.xp;
  if (xp.value < 100) {
    level.value = 1;
    xpStart.value = 0;
    xpEnd.value = 100;
    percents.value = (xp.value / 100) * 100;
  }
  if (xp.value >= 100 && xp.value < 200) {
    level.value = 2;
    xpStart.value = 100;
    xpEnd.value = 200;
    percents.value = ((xp.value - 100) / 100) * 100;
  }
  if (xp.value >= 200 && xp.value < 300) {
    level.value = 3;
    xpStart.value = 200;
    xpEnd.value = 300;
    percents.value = ((xp.value - 200) / 100) * 100;
  }
  if (xp.value >= 300 && xp.value < 400) {
    level.value = 4;
    xpStart.value = 300;
    xpEnd.value = 400;
    percents.value = ((xp.value - 300) / 100) * 100;
  }
  if (xp.value >= 400) {
    level.value = 5;
    percents.value = 100;
    status.value = 'success';
    isMaxLevel.value = true;
  }
});

</script>
