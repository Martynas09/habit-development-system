<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <a-breadcrumb>
        <a-breadcrumb-item href="" class="text-xl">
          <Link :href="route('dashboard')">
          <home-outlined style="font-size:20px" />
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <Link :href="route('Plan.PlanListView')">
          <reconciliation-outlined style="font-size:20px" />
          Planų valdymas
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">
          <Link :href="route('Plan.ChooseAlternativeView')">
          <gold-outlined style="font-size:20px" />
          Alternatyvos pasirinkimas
          </Link>
        </a-breadcrumb-item>
        <a-breadcrumb-item class="text-xl">Kūrimas nuo nulio</a-breadcrumb-item>
      </a-breadcrumb>
    </template>

    <div class="pt-12 pb-24">
      <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
        <h3 class="pb-2 text-xl">Plano parametrai:</h3>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <!-- Tikslų forma -->
          <a-form ref="formRef" name="dynamic_form_item" :model="dynamicValidateForm">
            <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="goal"
                label="Tikslai kurių sieksite:" :rules="[{ required: true }]"></a-form-item></div>
            <div class="pl-4">
              <a-form-item v-for="(goal, index) in dynamicValidateForm.goals" :key="goal.key" v-bind="index === 0"
                :name="['goals', index, 'value']" :rules="{
                  required: true,
                  message: 'goal can not be null',
                  trigger: 'change',
                }" style="margin-top:0px;margin-bottom:10px">
                <a-input v-model:value="goal.value" placeholder="Įrašykite tikslą" style="width: 40%; margin-left: 8px" />
                <minus-circle-two-tone two-tone-color="#ef4444" v-if="dynamicValidateForm.goals.length > 1" class="pl-2"
                  :disabled="dynamicValidateForm.goals.length === 1" @click="removeGoal(goal)" />
              </a-form-item>
            </div>
            <div class="pl-6">
              <a-form-item>
                <a-button type="primary" @click="addGoal">
                  <PlusOutlined />
                  Pridėti tikslą
                </a-button>
              </a-form-item>
            </div>
          </a-form>
          <!-- Tikslų formos pabaiga -->
          <!-- Įpročių forma -->
          <a-form ref="formRef" name="dynamic_form_item" :model="dynamicValidateForm">
            <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="habit"
                label="Įpročiai kuriuos ugdysite:" :rules="[{ required: true }]"></a-form-item></div>
            <div class="pl-4">
              <a-form-item v-for="(habit, index) in dynamicValidateForm.habits" :key="habit.key" v-bind="index === 0"
                :name="['habits', index, 'value']" :rules="{
                  required: true,
                  message: 'habit can not be null',
                  trigger: 'change',
                }" style="margin-top:0px;margin-bottom:10px">
                <a-input v-model:value="habit.value" placeholder="Įrašykite įprotį"
                  style="width: 40%; margin-left: 8px" />
                <minus-circle-two-tone two-tone-color="#ef4444" v-if="dynamicValidateForm.habits.length > 1" class="pl-2"
                  :disabled="dynamicValidateForm.habits.length === 1" @click="removeHabit(habit)" />
              </a-form-item>
            </div>
            <div class="pl-6">
              <a-form-item>
                <a-button type="primary" @click="addHabit">
                  <PlusOutlined />
                  Pridėti įprotį
                </a-button>
              </a-form-item>
            </div>
          </a-form>
          <!-- Įpročių formos pabaiga -->
          <!-- Tvarkaraščio forma -->
          <div class="">
            <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="habit"
                label="Užduotys kurias vykdysite:" :rules="[{ required: true }]"></a-form-item></div>
            <div class="pt-2 px-6 max-w-[630px]">
              <div class="border border-zinc-300 min-h-[150px]">
                <h3 class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Užduočių sąrašas</h3>
                <draggable class="list-group" handle=".handle" itemKey="id" :list="listTasks" :clone="handleClone"
                  :group="{ name: 'people', pull: 'clone', put: false }" @change="log">
                  <template #item="{ element,index }">
                    <div class="list-group-item flex p-1 items-center hover:bg-zinc-50">
                      <i class="handle px-2"><unordered-list-outlined /></i>
                      <a-input style="width: 40%" v-model:value="element.task" placeholder="Užduoties pavadinimas" />
                      <a-input-number style="margin-left: 8px;margin-right: 5px" v-model:value="element.duration" :min="1"
                        :max="360" placeholder="Trukmė" /> min.
                      <minus-circle-two-tone two-tone-color="#ef4444" v-if="listTasks.length > 1" class="pl-2"
                        :disabled="listTasks.length === 1" @click="removeTask(index)" />
                    </div>
                  </template>
                </draggable>
                <div class="pl-2 py-2">
                  <a-button type="primary" @click="addTask">
                    <PlusOutlined />
                    Pridėti užduotį
                  </a-button>
                </div>
              </div>
            </div>
            <p class="pl-6 pt-4" style="margin-top:0px;margin-bottom:0px">Sudėkite užduotis į norimas savaitės dienas ir nustatykite jų laiką</p>
            <div class="grid grid-cols-7 py-4 px-6">
              <div class="border border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Pirmadienis</h3>
                <draggable class="list-group" :list="listMonday" group="people" @change="log" itemKey="id">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtMonday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Antradienis</h3>
                <draggable class="list-group" :list="listTuesday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtTuesday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Trečiadienis</h3>
                <draggable class="list-group" :list="listWednesday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtWednesday(index)"><minus-circle-two-tone
                          two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Ketvirtadienis</h3>
                <draggable class="list-group" :list="listThursday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtThursday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Penktadienis</h3>
                <draggable class="list-group" :list="listFriday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtFriday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Šeštadienis</h3>
                <draggable class="list-group" :list="listSaturday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtSaturday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
              <div class="border-r border-y border-zinc-300 min-h-[150px]">
                <h3 style="margin-top:0px;margin-bottom:0px"
                  class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Sekmadienis</h3>
                <draggable class="list-group" :list="listSunday" group="people" @change="log" itemKey="name">
                  <template #item="{ element, index }">
                    <div class="list-group-item hover:bg-zinc-50">
                      <a-time-picker style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                        v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.task }}
                      <button @click="removeAtSunday(index)"><minus-circle-two-tone two-tone-color="#ef4444" /></button>
                      <a-divider style="margin-top:0px;margin-bottom:0px" />
                    </div>
                  </template>
                </draggable>
              </div>
            </div>
          </div>
          <!-- Tvarkaraščio formavimo formos pabaiga -->
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {
  ReconciliationOutlined, HomeOutlined, GoldOutlined, PlusOutlined, MinusCircleTwoTone, UnorderedListOutlined,
} from '@ant-design/icons-vue';
import { v4 as uuidv4 } from 'uuid';
import { onMounted, reactive, ref } from 'vue';
import draggable from 'vuedraggable';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const listTasks = ref([
]);
const listMonday = ref([
]);
const listTuesday = ref([
]);
const listWednesday = ref([
]);
const listThursday = ref([
]);
const listFriday = ref([
]);
const listSaturday = ref([
]);
const listSunday = ref([
]);

const log = (evt) => {
  window.console.log(evt);
};
const removeAtMonday = (idx) => {
  listMonday.value.splice(idx, 1);
};
const removeAtTuesday = (idx) => {
  listTuesday.value.splice(idx, 1);
};
const removeAtWednesday = (idx) => {
  listWednesday.value.splice(idx, 1);
};
const removeAtThursday = (idx) => {
  listThursday.value.splice(idx, 1);
};
const removeAtFriday = (idx) => {
  listFriday.value.splice(idx, 1);
};
const removeAtSaturday = (idx) => {
  listSaturday.value.splice(idx, 1);
};
const removeAtSunday = (idx) => {
  listSunday.value.splice(idx, 1);
};

const formRef = ref();

const dynamicValidateForm = reactive({
  goals: [],
  habits: [],
  tasks: [],
});

onMounted(() => {
  dynamicValidateForm.goals.push({
    value: '',
    key: Date.now(),
  });
  dynamicValidateForm.habits.push({
    value: '',
    key: Date.now(),
  });
  dynamicValidateForm.tasks.push({
    name: '',
    duration: '',
    key: Date.now(),
  });
});

const removeGoal = (item) => {
  const index = dynamicValidateForm.goals.indexOf(item);
  if (index !== -1) {
    dynamicValidateForm.goals.splice(index, 1);
  }
};
const addGoal = () => {
  dynamicValidateForm.goals.push({
    value: '',
    key: Date.now(),
  });
};
const removeHabit = (item) => {
  const index = dynamicValidateForm.habits.indexOf(item);
  if (index !== -1) {
    dynamicValidateForm.habits.splice(index, 1);
  }
};
const addHabit = () => {
  dynamicValidateForm.habits.push({
    value: '',
    key: Date.now(),
  });
};
const removeTask = (item) => {
  listTasks.value.splice(item, 1);
};
const addTask = () => {
  listTasks.value.push({ id: uuidv4(), task: '', duration: '' });
};
const handleClone = (item) => ({ id: uuidv4(), task: item.task, duration: item.duration });

// const submitForm = () => {
//   formRef.value.validate().then(() => {
//     console.log('values', dynamicValidateForm.domains);
//   }).catch((error) => {
//     console.log('error', error);
//   });
// };
</script>
<style scoped></style>
