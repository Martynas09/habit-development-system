<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { DownOutlined } from '@ant-design/icons-vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import UserStats from '@/Components/UserStats.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                <ApplicationLogo class="block h-11 w-auto fill-current text-blue-500" />
                                </Link>
                            </div>

                            <!-- Navigation Links User-->
                            <div v-if="!$page.props.auth.user.is_admin" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Pagrindinis
                                </NavLink>
                                <NavLink :href="''" :active="route().current('Plan.PlanListView') || route().current('MyGoals')">
                                    <a-dropdown>
                                        <span class="ant-dropdown-link" @click.prevent>Mano planai
                                            <DownOutlined />
                                        </span>
                                        <template #overlay>
                                            <a-menu>
                                                <a-menu-item>
                                                    <Link :href="route('Plan.PlanListView')">
                                                    Planų valdymas
                                                    </Link>
                                                </a-menu-item>
                                                <a-menu-item>
                                                    <Link :href="route('MyGoals')">
                                                    Tikslų valdymas
                                                    </Link>
                                                </a-menu-item>
                                            </a-menu>
                                        </template>
                                    </a-dropdown>
                                </NavLink>
                                <NavLink :href="route('Schedule')" :active="route().current('Schedule')">
                                    Tvarkaraštis
                                </NavLink>
                                <NavLink :href="route('Challenge.ChallengesListView')"
                                    :active="route().current('Challenge.ChallengesListView')">
                                    Iššūkiai
                                </NavLink>
                                <NavLink :href="route('Leaderboard')" :active="route().current('Leaderboard')">
                                    Lyderių lentelė
                                </NavLink>
                                <NavLink :href="route('Journal')" :active="route().current('Journal')">
                                    Žurnalas
                                </NavLink>
                                <NavLink :href="route('Report')" :active="route().current('Report')">
                                    Ataskaita
                                </NavLink>
                            </div>
                             <!-- Navigation Links Admin-->
                             <div v-if="$page.props.auth.user.is_admin" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('Achievements')" :active="route().current('Achievements')">
                                    Pasiekimų valdymas
                                </NavLink>
                                <NavLink :href="route('Users')" :active="route().current('Users')">
                                    Naudotojų valdymas
                                </NavLink>
                                <NavLink :href="route('CharacterItems')" :active="route().current('CharacterItems')">
                                    Personažo daiktų valdymas
                                </NavLink>
                                <NavLink :href="route('Questions')" :active="route().current('Questions')">
                                    Klausimų valdymas
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <UserStats v-if="!$page.props.auth.user.is_admin"></UserStats>
                            <a-divider v-if="!$page.props.auth.user.is_admin" type="vertical" style="height: 28px;margin:15px;background-color: #d9d9d9" />
                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span>
                                            <button type="button"
                                                class="inline-flex items-center pr-2 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                                <span v-if="!$page.props.auth.user.is_admin" class="max-w-[70px]">
                                                    {{ $page.props.auth.user.username }}
                                                </span>
                                                <span v-else>
                                                    {{ $page.props.auth.user.username }}
                                                </span>
                                                <div v-if="!$page.props.auth.user.is_admin" class="flex items-center justify-center ml-2 w-10">
                                                    <img :src="'/storage/' + $page.props.auth.user.avatar"
                                                        class="rounded-sm border px-1 border-solid border-gray-300 shadow-sm"
                                                        style="padding-top:1px;padding-bottom:1px">
                                                </div>
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink v-if="!$page.props.auth.user.is_admin" :href="route('profile.edit')"> Profilis </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Atsijungti
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex': !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex': showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.username }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profilis </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Atsijungti
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<style>
::-webkit-scrollbar {
    display: none;
}
</style>
