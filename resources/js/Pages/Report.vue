<template>
  <Head title="Pagrindinis" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item class="text-xl">
          <home-outlined style="font-size:20px" />
          Pagrindinis
        </a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-4 flex flex-col items-center">
            Pasirinkite rodomus mėnesius:
            <a-select v-model:value="selectedMonths" mode="multiple" style="width: 50%" placeholder="Pasirinkite mėnesius"
              @change="handleChange">
              <a-select-option v-for="month in months" :key="month" :value="month">
                {{ month }}
              </a-select-option>
            </a-select>
          </div>
          <div v-if="chartDrawn" class="p-8">
            <canvas id="myChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import { HomeOutlined } from '@ant-design/icons-vue';
import Chart from 'chart.js/auto';
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({ completedTasks: Array });
const months = ['Sausis', 'Vasaris', 'Kovas', 'Balandis', 'Gegužė', 'Birželis', 'Liepa', 'Rugpjūtis', 'Rugsėjis', 'Spalis', 'Lapkritis', 'Gruodis'];
const selectedMonths = ref([]);
const filteredData = computed(() => props.completedTasks.filter((value, index) => selectedMonths.value.includes(months[index])));
const largestValue = Math.max(...Object.values(props.completedTasks));
const chartDrawn = ref(false);
const handleChange = () => {
  const ctx = document.getElementById('myChart');
  const chartInstance = Chart.getChart(ctx);
  if (chartInstance) {
    chartInstance.destroy();
  }

  const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: months.filter((month) => selectedMonths.value.includes(month)),
      datasets: [{
        label: 'Atliktos užduotys',
        data: filteredData.value,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1,
      }],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          max: largestValue + 1,
          ticks: {
            precision: 0,
          },
        },
      },
    },
  });
  chartDrawn.value = true;
  if (selectedMonths.value.length === 0) {
    chartDrawn.value = false;
  }
};
</script>
