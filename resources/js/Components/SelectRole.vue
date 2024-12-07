<script setup>
import { router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const { props } = usePage();

const selectRole = (role) => {
    const userRole = props.auth.user.role;

    if (userRole === 'doctor' || userRole === 'patient') {
        router.get('/dashboard');
        return;
    }

    if (role === 'doctor') {
        router.get('/doctor/registrar');
    } else if (role === 'patient') {
        router.get('/paciente/registrar');
    } else {
        router.get('/dashboard');
    }
};
</script>

<template>
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div v-if="$page.props.auth.user.role === 'doctor' || $page.props.auth.user.role === 'patient'"
             class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
            <p>Ya estás registrado como {{ $page.props.auth.user.role === 'doctor' ? 'doctor' : 'paciente' }}.</p>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Selecciona tu rol</h1>
        <div class="flex space-x-4">
            <PrimaryButton
                @click="selectRole('doctor')"
                :disabled="$page.props.auth.user.role === 'patient'"
                :class="{ 'opacity-50 cursor-not-allowed': $page.props.auth.user.role === 'patient' }"
            >
                Soy Doctor
            </PrimaryButton>
            <PrimaryButton
                @click="selectRole('patient')"
                :disabled="$page.props.auth.user.role === 'doctor'"
                :class="{ 'opacity-50 cursor-not-allowed': $page.props.auth.user.role === 'doctor' }"
            >
                Soy Paciente
            </PrimaryButton>
        </div>
    </div>
</template>


