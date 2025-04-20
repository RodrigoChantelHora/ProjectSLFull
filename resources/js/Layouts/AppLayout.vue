<script setup>
import { computed, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import SideBar from '@/Components/SideBar.vue';
import Notify from '@/Components/Notify.vue';
import Alert from '@/Components/Alerts/Alert.vue';

const showingNavigationDropdown = ref(false);

defineProps({
    success: String,
    error: String,
    customMessage: String,
});

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

</script>

<template>
    <div>
        <Banner />

        <div class="flex w-full">
            <div class="hidden md:block lg:block"><SideBar /></div>
            <div class="min-h-screen w-full bg-gray-100 dark:bg-slate-700">
                <nav class="bg-white shadow">
                    <!-- Primary Navigation Menu -->
                    <div class="mx-auto px-4">
                        <div class="flex justify-between items-center h-16">

                            <div class="hidden sm:block w-full">
                                <Alert :success="success" :error="error" :customMessage="customMessage" />
                            </div>

                            <div class="hidden lg:flex sm:items-center">
                                <Notify />
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                    <svg
                                        class="h-6 w-6"
                                        stroke="currentColor"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <!-- Dashboard -->
                            <ResponsiveNavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                                icon="fa-solid fa-chart-pie">
                                Dashboard
                            </ResponsiveNavLink>

                            <!-- Relatórios -->
                            <ResponsiveNavLink
                                :href="route('report.index')"
                                :active="route().current('report.index')"
                                icon="fa-solid fa-list">
                                Relatórios
                            </ResponsiveNavLink>

                            <!-- Lotes -->
                            <ResponsiveNavLink
                                :href="route('email.status')"
                                :active="route().current('email.status')"
                                icon="fa-solid fa-envelopes-bulk">
                                Lotes
                            </ResponsiveNavLink>

                            <!-- Contatos -->
                            <ResponsiveNavLink
                                :href="route('contact.index')"
                                :active="route().current('contact.index')"
                                icon="fa-solid fa-users-line">
                                Contatos
                            </ResponsiveNavLink>
                        </div>

                        <!-- Responsive Settings Options -->
                        <div class="pt-4 pb-1 border-t border-gray-200">
                            <div class="flex items-center px-4">
                                <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                    <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                </div>

                                <div>
                                    <div class="font-medium text-base text-gray-800">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div class="font-medium text-sm text-gray-500">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <!-- Minha Conta -->
                                <ResponsiveNavLink
                                    :href="route('profile.show')"
                                    :active="route().current('profile.show')"
                                    icon="fa-solid fa-id-card">
                                    Minha Conta
                                </ResponsiveNavLink>

                                <!-- Configurações -->
                                <ResponsiveNavLink
                                    :href="route('connection.edit')"
                                    :active="route().current('connection.edit')"
                                    icon="fa-solid fa-gears">
                                    Configurações
                                </ResponsiveNavLink>

                                <!-- Logout -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button" icon="fa-solid fa-right-from-bracket">
                                        Log Out
                                    </ResponsiveNavLink>
                                </form>

                                <!-- Team Management -->
                                <template v-if="$page.props.jetstream.hasTeamFeatures">
                                    <div class="border-t border-gray-200" />

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Manage Team
                                    </div>

                                    <!-- Team Settings -->
                                    <ResponsiveNavLink
                                        :href="route('teams.show', $page.props.auth.user.current_team)"
                                        :active="route().current('teams.show')"
                                        icon="fa-solid fa-users-gear">
                                        Team Settings
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink
                                        v-if="$page.props.jetstream.canCreateTeams"
                                        :href="route('teams.create')"
                                        :active="route().current('teams.create')"
                                        icon="fa-solid fa-plus">
                                        Create New Team
                                    </ResponsiveNavLink>

                                    <!-- Team Switcher -->
                                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                                        <div class="border-t border-gray-200" />

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Switch Teams
                                        </div>

                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <ResponsiveNavLink as="button" icon="fa-solid fa-arrows-rotate">
                                                    <div class="flex items-center">
                                                        <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <div>{{ team.name }}</div>
                                                    </div>
                                                </ResponsiveNavLink>
                                            </form>
                                        </template>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main>
                    <div class="mx-auto flex">
                        <div class="flex-grow p-4 px-0 md:px-4 lg:p-5">
                            <slot />
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
