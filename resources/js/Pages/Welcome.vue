<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { CalendarOutlined, BellOutlined, BarChartOutlined } from '@ant-design/icons-vue';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="" />
  <div class="min-h-screen bg-gray-100">
    <div v-if="canLogin" class="hidden px-6 py-4 sm:block">
      <div v-if="$page.props.auth.user" class="flex justify-end pt-3">
        <Link :href="route('dashboard')"><a-button type="primary" size="small">Pagrindinis</a-button></Link>
      </div>
      <template v-else>
        <div class="flex justify-end pt-3">
          <Link :href="route('login')" class="pr-3"><a-button type="primary" style="background:#84cc16;border-color:#84cc16" size="small">Prisijungti</a-button>
          </Link>
          <Link v-if="canRegister" :href="route('register')"><a-button type="primary" size="small">Registruotis</a-button>
          </Link>
        </div>
      </template>
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5">
          <div class="flex justify-center">
            <img :src="'/storage/fullLogo.png'" alt="logo" class="h-44 w-auto">
          </div>
          <div>
            <p class="text-md mt-8 text-gray-400 text-center">Efektyvesnis ir patogesnis būdas ugdyti įpročius</p>
            <div class="text-center">
              <Link v-if="canRegister" :href="route('register')">
              <a-button type="primary" size="large">Pradėti ugdyti įpročius</a-button></Link>
            </div>
            <a-row class="flex items-center py-10">
              <a-col :span="12" class="p-5">
                <div>
                  <h1 class="text-xl font-bold text-sky-500"><calendar-outlined /> Automatiškai sugeneruojamas užduočių
                    tvarkaraštis</h1>
                  <p>
                    Ši funkcija taupo naudotojų laiką, nes reikia tiesiog įvesti savo užduotis ir leisti
                    sistemai sugeneruoti tvarkaraštį. Tvarkaraštis tai pat turi mėnesio suvestinę, kuri
                    parodo naudotojo progresą atitinkamais mėnesiais. Taip pat, tvarkaraštyje naudotojas
                    gali sužymėti savo atliktas užduotis.
                  </p>
                </div>
              </a-col>
              <a-col :span="12" class="p-5">
                <div class="border border-gray-300 rounded-md p-2"><img src="/storage/image1.png"></div>
              </a-col>
            </a-row>
            <a-row class="flex items-center py-10">
              <a-col :span="12" class="p-5">
                <div class="border border-gray-300 rounded-md p-2"><img src="/storage/image2.png"></div>
              </a-col>
              <a-col :span="12" class="p-5">
                <div>
                  <h1 class="text-xl font-bold text-lime-500"><bell-outlined /> Užduočių priminimai</h1>
                  <p>
                    Ši funkcija padeda naudotojams išlikti organizuotiems ir neatsilikti nuo savo
                    tvarkaraščio, nes užtikrina, kad jie nepamirš svarbių užduočių. Tai gali būti ypač
                    naudinga asmenims, kurių dienotvarkė įtempta, arba tiems, kurie lengvai išsiblaško.
                  </p>
                </div>
              </a-col>
            </a-row>
            <a-row class="flex items-center py-10">
              <a-col :span="12" class="p-5">
                <div>
                  <h1 class="text-xl font-bold text-sky-500"><bar-chart-outlined /> Progreso ataskaitos</h1>
                  <p>
                    Ši funkcija padeda naudotojams vizualizuoti ir suprasti savo pažangą siekiant
                    tikslų. Tai gali būti ypač naudinga asmenims, kurie siekia ilgalaikių tikslų, arba
                    tiems, kurie bando stebėti kelis rodiklius.
                  </p>
                </div>
              </a-col>
              <a-col :span="12" class="p-5">
                <div class="border border-gray-300 rounded-md p-2"><img src="/storage/image3.png"></div>
              </a-col>
            </a-row>
          </div>
          <p class="text-end mr-2 text-gray-200">Martynas Gaulia IFAi-9</p>
        </div>
      </div>
    </div>
  </div>
</template>
