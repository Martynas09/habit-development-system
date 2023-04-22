<template>
  <Head title="Kūrimas nuo nulio" />

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
          <a-config-provider :locale="ltLT">
            <p class="text-lg pt-5 pl-6">Planavimas yra sunumeruotas, todėl norint išlaikyti sklandų plano kūrimą, reikia
              laikytis numatytos eigos.</p>
            <a-row>
              <!-- Left side -->
              <a-col :span="12">
                <a-form ref="formRef" name="dynamic_form_item" :model="dynamicValidateForm"></a-form>
                <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" label="1. Plano pavadinimas:"
                    :rules="[{ required: true }]"></a-form-item></div>
                <div class="pl-4">
                  <a-form-item style="margin-top:0px;margin-bottom:10px"><a-input v-model:value="planTitle"
                      placeholder="Įrašykite pavadinimą" style="width: 50%; margin-left: 8px" /></a-form-item>
                  <span v-if="planTitleError != ''" class="text-red-500 pl-3">{{ planTitleError }}</span>
                </div>

                <!-- Tikslų forma -->
                <div class="pl-6">
                  <a-form ref="formRef" name="dynamic_form_item" :model="dynamicValidateForm">
                    <div class="pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="goal"
                        label="3. Tikslai kurių sieksite:" :rules="[{ required: true }]"></a-form-item></div>
                    <div>
                      <a-form-item v-for="(goal, index) in dynamicValidateForm.goals" :key="goal.key" v-bind="index === 0"
                        :name="['goals', index, 'value']" style="margin-top:0px;margin-bottom:10px">
                        <a-input v-model:value="goal.value" placeholder="Įrašykite tikslą"
                          style="width: 50%; margin-left: 0px" />
                        <minus-circle-two-tone two-tone-color="#ef4444" v-if="dynamicValidateForm.goals.length > 1"
                          class="pl-2" :disabled="dynamicValidateForm.goals.length === 1" @click="removeGoal(goal)" />
                      </a-form-item>
                    </div>
                    <a-form-item>
                      <a-button type="primary" @click="addGoal">
                        <PlusOutlined />
                        Pridėti tikslą
                      </a-button>
                    </a-form-item>
                  </a-form>
                  <span v-if="planGoalsError != ''" class="text-red-500">{{ planGoalsError }}</span>
                </div>
                <!-- Tikslų formos pabaiga -->
                <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="tasks"
                    label="5. Užduotys kurias vykdysite:" :rules="[{ required: true }]"></a-form-item></div>
                <a-form-item>
                  <div class="pt-2 px-6 max-w-[630px]">
                    <div class="border border-zinc-300 min-h-[150px]">
                      <h3 class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Užduočių sąrašas</h3>
                      <draggable class="list-group" handle=".handle" itemKey="id" :list="listTasks" :clone="handleClone"
                        :group="{ name: 'people', pull: 'clone', put: false }" @change="log">
                        <template #item="{ element, index }">
                          <div class="list-group-item flex p-1 items-center hover:bg-zinc-50">
                            <i class="handle px-2"><unordered-list-outlined /></i>
                            <a-input style="width: 40%" v-model:value="element.value"
                              placeholder="Užduoties pavadinimas" />
                            <a-input-number style="margin-left: 8px;margin-right: 5px" v-model:value="element.duration"
                              :min="1" :max="360" placeholder="Trukmė" /> min.
                            <minus-circle-two-tone v-if="element.canDelete" two-tone-color="#ef4444" class="pl-2"
                              @click="removeTask(index)" />
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
                </a-form-item>
                <span v-if="planTasksListError != ''" class="text-red-500 pl-6">{{ planTasksListError }}</span>
              </a-col>
              <!-- Right side -->
              <a-col :span="12">
                <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="color"
                    label="2. Plano spalva:" :rules="[{ required: true }]"></a-form-item>
                </div>
                <a-form-item style="margin-top:0px;margin-bottom:10px">
                  <a-popover placement="right" trigger="click">
                    <template #content>
                      <div class="flex justify-center items-center">
                        <div class="grid grid-cols-3 gap-3">
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #ffccc7"
                            @click="planColor = '#ffccc7'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #ffd8bf"
                            @click="planColor = '#ffd8bf'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #ffe7ba"
                            @click="planColor = '#ffe7ba'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #fff1b8"
                            @click="planColor = '#fff1b8'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #ffffb8"
                            @click="planColor = '#ffffb8'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #f4ffb8"
                            @click="planColor = '#f4ffb8'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #d9f7be"
                            @click="planColor = '#d9f7be'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #b5f5ec"
                            @click="planColor = '#b5f5ec'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #bae0ff"
                            @click="planColor = '#bae0ff'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #d6e4ff"
                            @click="planColor = '#d6e4ff'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #efdbff"
                            @click="planColor = '#efdbff'"></div>
                          <div class="w-6 h-6 border border-gray-400" style="backgroundColor: #ffd6e7"
                            @click="planColor = '#ffd6e7'"></div>
                        </div>
                      </div>
                    </template>
                    <template #title>
                      <div class="text-center">
                        <span>Pasirinkite spalvą</span>
                      </div>
                    </template>
                    <div class="pl-6 w-[170px]">
                      <a-button :style="{ backgroundColor: planColor }">Pasirinkti spalvą</a-button>
                    </div>
                  </a-popover>
                </a-form-item>
                <span v-if="planColorError != ''" class="text-red-500 pl-6">{{ planColorError }}</span>
                <!-- Įpročių forma -->
                <a-form ref="formRef" name="dynamic_form_item" :model="dynamicValidateForm">
                  <div class="pl-6 pt-3">
                    <a-form-item style="margin-top:0px;margin-bottom:0px" name="habit"
                      label="4. Įpročiai kuriuos ugdysite:" :rules="[{ required: true }]"></a-form-item>
                  </div>
                  <div class="pl-4">
                    <a-form-item v-for="(habit, index) in dynamicValidateForm.habits" :key="habit.key"
                      v-bind="index === 0" :name="['habits', index, 'value']" style="margin-top:0px;margin-bottom:10px">
                      <a-input v-model:value="habit.value" placeholder="Įrašykite įprotį"
                        style="width: 50%; margin-left: 8px" />
                      <minus-circle-two-tone two-tone-color="#ef4444" v-if="dynamicValidateForm.habits.length > 1"
                        class="pl-2" :disabled="dynamicValidateForm.habits.length === 1" @click="removeHabit(habit)" />
                    </a-form-item>
                  </div>
                  <div class="pl-6">
                    <a-form-item>
                      <a-button type="primary" @click="addHabit">
                        <PlusOutlined />
                        Pridėti įprotį
                      </a-button>
                    </a-form-item>
                    <span v-if="planHabitsError != ''" class="text-red-500">{{ planHabitsError }}</span>
                  </div>
                </a-form>
                <!-- Įpročių formos pabaiga -->
              </a-col>
            </a-row>
            <!-- Tvarkaraščio forma -->
            <div class="">
              <p style="margin-top:0px;margin-bottom:0px"></p>
              <div class="pl-6 pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="category"
                  label="6. Sudėkite aukščiau esančias užduotis į norimas savaitės dienas ir nustatykite jų atlikimo laiką"
                  :rules="[{ required: true }]"></a-form-item></div>
              <div class="grid grid-cols-7 py-4 px-6">
                <div class="border border-zinc-300 min-h-[150px]">
                  <h3 style="margin-top:0px;margin-bottom:0px"
                    class="text-center border-b border-zinc-300 font-bold bg-zinc-50">Pirmadienis</h3>
                  <draggable class="list-group" :list="listMonday" group="people" @change="log" itemKey="id">
                    <template #item="{ element, index }">
                      <div class="list-group-item hover:bg-zinc-50">
                        <a-time-picker :minute-step="5"
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listMonday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listTuesday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listWednesday')"><minus-circle-two-tone
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listThursday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listFriday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" placeholder="Laikas" />{{ element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listSaturday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
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
                        <a-time-picker
                          style="width: 41%; margin-bottom:7px;margin-top:7px;margin-left:5px;margin-right:5px"
                          v-model:value="element.time" format="HH:mm" valueFormat="HH:mm" placeholder="Laikas" />{{
                            element.value }}
                        <button @click="removeFromDay(index, element.fatherId, 'listSunday')"><minus-circle-two-tone
                            two-tone-color="#ef4444" /></button>
                        <a-divider style="margin-top:0px;margin-bottom:0px" />
                      </div>
                    </template>
                  </draggable>
                </div>
              </div>
              <span v-if="planTasksError!=''" class="text-red-500 pl-6">{{planTasksError}}</span>
            </div>
            <!-- Tvarkaraščio formavimo formos pabaiga -->
            <div class="pl-6">
              <a-form ref="formRef" name="dynamic_form_nest_item" :model="dynamicValidateForm">
                <div class="pt-4"><a-form-item style="margin-top:0px;margin-bottom:0px" name="category"
                    label="7. Plano prizai (neprivaloma, tačiau rekomenduojama)"
                    :rules="[{ required: false }]"></a-form-item></div>
                <a-space v-for="(prize, index) in dynamicValidateForm.prizes" :key="prize.id"
                  style="display: flex; margin-bottom: 8px" align="baseline">
                  <a-form-item :name="['prizes', index, 'title']"
                    :rules="{ required: true, message: 'Prašome įvesti prizo pavadinimą' }">
                    <div><a-form-item style="margin-top:0px;margin-bottom:0px" name="title" label="Prizo pavadinimas:"
                        :rules="[{ required: true }]"></a-form-item></div>
                    <a-input v-model:value="prize.title" placeholder="Įrašykite pavadinimą" />
                  </a-form-item>
                  <a-form-item :name="['prizes', index, 'category']" :rules="{ required: true, }">
                    <div><a-form-item style="margin-top:0px;margin-bottom:0px" name="category" label="Skiriamas už:"
                        :rules="[{ required: true }]"></a-form-item></div>
                    <a-select v-model:value="prize.category" style="width: 200px" placeholder="Pasirinkite">
                      <a-select-option value="goal">Tikslo pasiekimą</a-select-option>
                      <a-select-option value="task">Užduoties įvykdymą</a-select-option>
                      <a-select-option value="habit">Įpročio išsiugdymą</a-select-option>
                      <a-select-option value="plan">Plano užbaigimą</a-select-option>
                    </a-select>
                    <minus-circle-two-tone v-if="prize.category === 'plan' || prize.category === undefined" class="ml-2"
                      two-tone-color="#ef4444" @click="removePrize(prize)" />
                  </a-form-item>
                  <a-form-item :name="['prizes', index, 'receiverTitle']" :rules="{ required: true, }">
                    <div v-if="prize.category === 'goal'">
                      <div>
                        <a-form-item style="margin-top:0px;margin-bottom:0px" name="title" label="Tikslas:"
                          :rules="[{ required: true }]"></a-form-item>
                      </div>
                      <a-select v-model:value="prize.receiverTitle" style="width: 200px" placeholder="Pasirinkite">
                        <a-select-option v-for="goal in dynamicValidateForm.goals" :key="goal.key" :value="goal.value">
                          {{ goal.value }}
                        </a-select-option>
                      </a-select>
                      <minus-circle-two-tone class="ml-2" two-tone-color="#ef4444" @click="removePrize(prize)" />
                    </div>
                    <div v-if="prize.category === 'task'">
                      <div>
                        <a-form-item style="margin-top:0px;margin-bottom:0px" name="title" label="Užduotis:"
                          :rules="[{ required: true }]"></a-form-item>
                      </div>
                      <a-select v-model:value="prize.receiverTitle" style="width: 200px" placeholder="Pasirinkite">
                        <a-select-option v-for="task in listTasks" :key="task.id" :value="task.value">
                          {{ task.value }}
                        </a-select-option>
                      </a-select>
                      <minus-circle-two-tone class="ml-2" two-tone-color="#ef4444" @click="removePrize(prize)" />
                    </div>
                    <div v-if="prize.category === 'habit'">
                      <div>
                        <a-form-item style="margin-top:0px;margin-bottom:0px" name="title" label="Įprotis:"
                          :rules="[{ required: true }]"></a-form-item>
                      </div>
                      <a-select v-model:value="prize.receiverTitle" style="width: 200px" placeholder="Pasirinkite">
                        <a-select-option v-for="habit in dynamicValidateForm.habits" :key="habit.key"
                          :value="habit.value">
                          {{ habit.value }}
                        </a-select-option>
                      </a-select>
                      <minus-circle-two-tone class="ml-2" two-tone-color="#ef4444" @click="removePrize(prize)" />
                    </div>
                  </a-form-item>
                </a-space>
                <a-form-item>
                  <a-button type="primary" @click="addPrize">
                    <PlusOutlined />
                    Pridėti prizą
                  </a-button>
                </a-form-item>
              </a-form>
            </div>
            <div class="pl-6">
              <div><a-form-item style="margin-top:0px;margin-bottom:0px" name="category" label="8. Plano priminimai"
                  :rules="[{ required: true }]"></a-form-item></div>
              <a-select v-model:value="reminderType" style="width: 250px" placeholder="Pasirinkite">
                <a-select-option value="self">Savarankiški</a-select-option>
                <a-select-option value="system">Sisteminiai (<span
                    class="text-sm text-green-500">rekomenduojama</span>)</a-select-option>
              </a-select>
            </div>
            <span v-if="planRemindersError!=''" class="text-red-500 pl-6">{{planRemindersError}}</span>
          </a-config-provider>
          <div class="text-end m-6">
            <a-button @click="saveToDB" type="primary">Sukurti planą</a-button>
          </div>
          <!--Modalas-->
          <div>
            <a-modal v-model:visible="visible" title="" footer="" :closable="false" :maskClosable="false">
              <div class="flex flex-col items-center justify-center p-2">
                <check-circle-filled v-if="defaultPercent === 100" style="font-size: 40px; color: #52c41a;" />
                <a-spin size="large" v-else />
                <a-progress :percent="defaultPercent" :status="progressStatus" />
                <p class="mt-2" v-if="defaultPercent < 100">Planas kuriamas...</p>
                <p class="mt-2" v-else>Planas sukurtas</p>
                <Link :href="route('Schedule')">
                <a-button v-if="defaultPercent === 100" type="primary" class="mt-4">Peržiūrėti
                  tvarkaraštį</a-button>
                </Link>
              </div>
            </a-modal>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
  ReconciliationOutlined, HomeOutlined, GoldOutlined, PlusOutlined, MinusCircleTwoTone, UnorderedListOutlined, CheckCircleFilled,
} from '@ant-design/icons-vue';
import { v4 as uuidv4 } from 'uuid';
import {
  onMounted, reactive, ref, onUnmounted,
} from 'vue';
import draggable from 'vuedraggable';
import dayjs from 'dayjs';
import ltLT from 'ant-design-vue/es/locale/lt_LT';
import 'dayjs/locale/lt';
import relativeTime from 'dayjs/plugin/relativeTime';
import { message } from 'ant-design-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

dayjs.extend(relativeTime);
dayjs.locale('lt');
const props = defineProps({ plan_id: Number });

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
const listMap = {
  listMonday,
  listTuesday,
  listWednesday,
  listThursday,
  listFriday,
  listSaturday,
  listSunday,
};

const removeFromDay = (idx, fatherId, listName) => {
  const list = listMap[listName];
  list.value.splice(idx, 1);
  const exists = (element) => element.fatherId === fatherId;

  if (!listMonday.value.some(exists) && !listTuesday.value.some(exists) && !listWednesday.value.some(exists) && !listThursday.value.some(exists) && !listFriday.value.some(exists) && !listSaturday.value.some(exists) && !listSunday.value.some(exists)) {
    const father = listTasks.value.find((x) => x.id === fatherId);
    father.canDelete = true;
  }
};

const formRef = ref();
const visible = ref(false);
const defaultPercent = ref(0);
const progressStatus = ref();
const newPlanId = ref(0);
const planTitle = ref('');
const planColor = ref('white');
const reminderType = ref();
const interval = null;

const dynamicValidateForm = reactive({
  goals: [],
  habits: [],
  prizes: [],
});
// VALIDATION
const planTitleError = ref('');
const planColorError = ref('');
const planGoalsError = ref('');
const planHabitsError = ref('');
const planTasksListError = ref('');
const planTasksError = ref('');
const planRemindersError = ref('');

onMounted(() => {
});
onUnmounted(() => {
  clearInterval(interval);
});

// VALIDATION
const validateBeforeSubmit = () => {
  planTitleError.value = planTitle.value.length < 3 ? 'Pavadinimas turi būti ilgesnis nei 3 simboliai' : '';
  planTitleError.value = planTitle.value.length < 1 ? 'Pavadinimas yra privalomas' : '';
  planColorError.value = planColor.value === 'white' ? 'Pasirinkite plano spalvą' : '';
  planGoalsError.value = dynamicValidateForm.goals.length < 1 ? 'Planas turi turėti bent vieną tikslą' : '';
  planHabitsError.value = dynamicValidateForm.habits.length < 1 ? 'Planas turi turėti bent vieną įprotį' : '';
  planTasksListError.value = listTasks.value.length < 1 ? 'Planas turi turėti bent vieną užduotį' : '';
  planRemindersError.value = reminderType.value === undefined ? 'Pasirinkite priminimų tipą' : '';

  const hasItem = (list) => list.value.length > 0;

  const hasPlanItem = !!((
    hasItem(listMonday)
  || hasItem(listTuesday)
  || hasItem(listWednesday)
  || hasItem(listThursday)
  || hasItem(listFriday)
  || hasItem(listSaturday)
  || hasItem(listSunday)
  ));

  planTasksError.value = hasPlanItem ? '' : 'Tvarkaraštis turi turėti bent vieną užduotį';
  return !planTitleError.value && !planColorError.value && !planGoalsError.value && !planHabitsError.value && !planTasksListError.value && !planTasksError.value && !planRemindersError.value;
};

const removeGoal = (item) => {
  const index = dynamicValidateForm.goals.indexOf(item);
  if (index !== -1) {
    dynamicValidateForm.goals.splice(index, 1);
  }
};
const addPrize = () => {
  dynamicValidateForm.prizes.push({
    title: '',
    key: Date.now(),
  });
};
const removePrize = (item) => {
  const index = dynamicValidateForm.prizes.indexOf(item);
  if (index !== -1) {
    dynamicValidateForm.prizes.splice(index, 1);
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
  listTasks.value.push({
    id: uuidv4(), value: '', duration: '', canDelete: true,
  });
};

function handleClone(item) {
  // eslint-disable-next-line no-param-reassign
  item.canDelete = false;
  return {
    id: uuidv4(), value: item.value, duration: item.duration, fatherId: item.id,
  };
}

const form = useForm({
  reminder: reminderType,
  prizes: dynamicValidateForm.prizes,
  title: planTitle,
  color: planColor,
  goals: dynamicValidateForm.goals,
  habits: dynamicValidateForm.habits,
  tasks: listTasks.value,
  monday: listMonday.value,
  tuesday: listTuesday.value,
  wednesday: listWednesday.value,
  thursday: listThursday.value,
  friday: listFriday.value,
  saturday: listSaturday.value,
  sunday: listSunday.value,
});

const saveToDB = () => {
  if (validateBeforeSubmit()) {
    console.log('passed validation');
    visible.value = true;
    form.post(
      '/plans/custom',
      {
        preserveScroll: true,
        onSuccess: () => { },
        onError: () => { progressStatus.value = 'exception'; },
      },
    );
    let i = 0;
    defaultPercent.value = 0;
    interval = setInterval(() => {
      i += 1;
      if (i < 99) { defaultPercent.value = i; }
      if (i === 99 && form.processing) { defaultPercent.value = 99; }
      if (i > 100 && !form.processing) {
        defaultPercent.value = 100;
        newPlanId.value = props.plan_id;
        clearInterval(interval);
      }
    }, 50);
  } else {
    message.error('Klaida! Peržiūrėkite klaidos pranešimus', 2);
  }
};

</script>
<style scoped></style>
