<template>
    <nav class="bg-blue-400 border-b border-blue-400">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                        </svg>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Inicio
                        </NavLink>
                        <NavLink v-if="isPatient || isAdmin" :href="route('appointments.index')" :active="route().current('appointments.index')">
                            Citas
                        </NavLink>
                        <NavLink v-if="isAdmin || isDoctor" :href="route('patients.index')" :active="route().current('patients.index')">
                            Pacientes
                        </NavLink>
                        <NavLink v-if="isAdmin || isDoctor || isPatient" :href="route('doctors.index')" :active="route().current('doctors.index')">
                            Doctores
                        </NavLink>
                        <NavLink v-if="isAdmin || isDoctor" :href="route('availabilities.index')" :active="route().current('availabilities.index')">
                            Disponibilidad
                        </NavLink>
                        <NavLink v-if="isAdmin" :href="route('users.index')" :active="route().current('users.index')">
                            Usuarios
                        </NavLink>
                        <NavLink v-if="isPatient && patient" :href="route('patients.show', patient.id)" :active="route().current('patients.show')">
                            Perfil
                        </NavLink>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                </button>

                                <span v-else class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:text-gray-200 focus:outline-none focus:bg-blue-700 active:bg-blue-700 transition ease-in-out duration-150">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <!-- Account Management -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Gestión de cuenta
                                </div>

                                <DropdownLink :href="route('profile.show')">
                                    Perfil
                                </DropdownLink>
                                <div class="border-t border-gray-200" />

                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <DropdownLink as="button">
                                        Cerrar sesión
                                    </DropdownLink>
                                </form>
                            </template>
                        </Dropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-gray-200 hover:bg-blue-700 focus:outline-none focus:bg-blue-700 focus:text-gray-200 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Inicio
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isPatient || isAdmin" :href="route('appointments.index')" :active="route().current('appointments.index')">
                    Citas
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isAdmin || isDoctor" :href="route('patients.index')" :active="route().current('patients.index')">
                    Pacientes
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isAdmin || isDoctor || isPatient" :href="route('doctors.index')" :active="route().current('doctors.index')">
                    Doctores
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isAdmin || isDoctor" :href="route('availabilities.index')" :active="route().current('availabilities.index')">
                    Disponibilidad
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isAdmin" :href="route('users.index')" :active="route().current('users.index')">
                    Usuarios
                </ResponsiveNavLink>
                <ResponsiveNavLink v-if="isPatient && patient" :href="route('patients.show', patient.id)" :active="route().current('patients.show')">
                    Perfil
                </ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                        <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                    </div>

                    <div>
                        <div class="font-medium text-base text-white">{{ $page.props.auth.user.name }}</div>
                        <div class="font-medium text-sm text-gray-200">{{ $page.props.auth.user.email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                        Perfil
                    </ResponsiveNavLink>

                    <!-- Authentication -->
                    <form method="POST" @submit.prevent="logout">
                        <ResponsiveNavLink as="button">
                            Cerrar sesión
                        </ResponsiveNavLink>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

export default {
    components: {
        NavLink,
        Dropdown,
        DropdownLink,
        ResponsiveNavLink,
    },
    data() {
        return {
            showingNavigationDropdown: false,
        };
    },
    computed: {
        isAdmin() {
            return this.$page.props.auth.user.role === 'admin';
        },
        isDoctor() {
            return this.$page.props.auth.user.role === 'doctor';
        },
        isPatient() {
            return this.$page.props.auth.user.role === 'patient';
        },
        patient() {
            return this.$page.props.auth.user.patient || null;
        }
    },
    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
    },
};
</script>

<style scoped>
nav {
    transition: background-color 0.3s ease;
}

button {
    transition: transform 0.2s ease;
}

button:hover {
    transform: scale(1.05);
}
</style>
