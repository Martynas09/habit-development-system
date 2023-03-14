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
                <a-breadcrumb-item class="text-xl">Alternatyvos pasirinkimas</a-breadcrumb-item>
            </a-breadcrumb>
        </template>

        <div class="py-12">
            <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
                <h3 class="pb-2 text-xl">Pasirinkite plano sudarymo alternatyvą:</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center px-4 py-4">
                        <div class="p-4 relative max-w-md">
                            <p class="flex justify-center font-bold">Apklausos alternatyva</p>
                            <p>Pasirinkus šią alternatyvą reikia užpildyti klausimyną. Klausimyne
                                surenkama svarbi informacija apie naudotojo tikslus, pageidavimus ir apribojimus. Tada
                                sistema naudoja šią informaciją, kad sukurtų naudotojo poreikius atitinkančius asmeninius
                                planus. Šis metodas idealiai tinka naudotojams, kurie nėra tikri, kokį planą pasirinkti,
                                arba kurie nori individualiai pritaikyto plano.
                            </p>
                            <ul class="pl-4 pb-8">
                                <li><plus-square-two-tone class="pr-3" />Renkama informacija naudojant klausimyną.</li>
                                <li><plus-square-two-tone class="pr-3" />Tinka naudotojams, kurie nori individualiai pritaikyto plano.</li>
                                <li><plus-square-two-tone class="pr-3" />Generuoja suasmenintą planą pagal naudotojo tikslus, pageidavimus ir apribojimus.</li>
                            </ul>
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <Link :href="route('Plan.QuestionnaireView')">
                                <a-button type="primary">Pasirinkti</a-button>
                                </Link>
                            </div>
                        </div>
                        <a-divider type="vertical" style="height: 220px; margin: auto; background-color: #69c0ff"/>
                        <div class="p-4 relative max-w-md">
                            <p class="flex justify-center font-bold">Siūlomų planų alternatyva</p>
                            <p>Pasirinkus šią alternatyvą planas pasirenkamas iš siūlomų planų sąrašo. Siūlomus planus
                                generuoja
                                sistema, remdamasi naudotojų atsiliepimais ir istoriniais duomenimis. Naudotojai gali
                                filtruoti sąrašą pagal įvairius kriterijus, pavyzdžiui, trukmę, sudėtingumo lygį ar
                                konkrečius tikslus. Šis metodas idealiai tinka naudotojams, kurie žino, ko nori, bet jiems
                                reikia patarimų ar įkvėpimo.
                            </p>
                            <ul class="pl-4 pb-8">
                                <li><plus-square-two-tone class="pr-3" />Sutaupo laiko, nes pateikiamas jau paruoštų planų sąrašas.</li>
                                <li><plus-square-two-tone class="pr-3" />Pateikiamas siūlomų planų sąrašas, pagrįstas naudotojų atsiliepimais ir istoriniais duomenimis.</li>
                            </ul>
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <Link>
                                <a-button type="primary">Pasirinkti</a-button>
                                </Link>
                            </div>
                        </div>
                        <a-divider type="vertical" style="height: 220px; margin: auto; background-color: #69c0ff" />
                        <div class="p-4 relative max-w-md">
                            <p class="flex justify-center font-bold">Kūrimo nuo nulio alternatyva</p>
                            <p class="pb-4">Pasirinkus šią alternatyvą planas kuriamas nuo nulio. Naudotojas gali nurodyti plano parametrus,
                                pavyzdžiui, trukmę, veiklą ir tikslus. Tada sistema pagal šiuos parametrus sukuria planą.
                                Šis metodas idealiai tinka naudotojams, kurie turi aiškią idėją, ko nori, ir nori visiškai
                                kontroliuoti planą.
                            </p>
                            <ul class="pl-4 pb-8">
                                <li><plus-square-two-tone class="pr-3" />Naudotojai gali visiškai kontroliuoti plano parametrus.</li>
                                <li><plus-square-two-tone class="pr-3" />Tinka naudotojams, kurie aiškiai žino, ko nori.</li>
                                <li><plus-square-two-tone class="pr-3" />Galimybė lanksčiai pritaikyti planą prie konkrečių poreikių ar pageidavimų.</li>
                            </ul>
                            <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                                <Link :href="route('Plan.CustomView')">
                                <a-button type="primary">Pasirinkti</a-button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ReconciliationOutlined, HomeOutlined, PlusSquareTwoTone } from '@ant-design/icons-vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>
<style scoped></style>
