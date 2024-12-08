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
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="max-w-md w-full bg-white shadow-md rounded-lg p-6 text-center ">
            <div v-if="$page.props.auth.user.role === 'admin'"
                 class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-4 rounded-lg">
                <p class="font-semibold">Eres administrador.</p>
            </div>
            <div v-else-if="$page.props.auth.user.role === 'doctor' || $page.props.auth.user.role === 'patient'"
                 class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 p-4 mb-4 rounded-lg">
                <p class="font-semibold">Ya estás registrado como {{ $page.props.auth.user.role === 'doctor' ? 'doctor' : 'paciente' }}.</p>
            </div>
            <div v-else>
                <h1 class="text-xl font-semibold text-gray-800 mb-4">No tienes rol, regístrate como:</h1>
                <div class="flex justify-center space-x-4">
                    <PrimaryButton
                        @click="selectRole('doctor')"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full transition duration-300"
                    >
                        Soy Doctor
                    </PrimaryButton>
                    <PrimaryButton
                        @click="selectRole('patient')"
                        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full transition duration-300"
                    >
                        Soy Paciente
                    </PrimaryButton>
                </div>
            </div>
        </div>
    </div>
</template>


