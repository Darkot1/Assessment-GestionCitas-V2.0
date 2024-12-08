<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    appointments: Array
});

const page = usePage();
const userRole = computed(() => page.props.auth.user.role);

const pageTitle = computed(() => {
    return userRole.value === 'admin' ? 'Panel de Citas' : 'Mis Citas';
});

const groupedAppointments = computed(() => {
    if (!props.appointments || props.appointments.length === 0) return [];

    if (userRole.value !== 'admin') {
        return [{
            patient: props.appointments[0]?.patient || null,
            appointments: props.appointments
        }];
    }

    const groups = props.appointments.reduce((acc, appointment) => {
        const patientId = appointment.patient?.id;
        if (!patientId) return acc;

        if (!acc[patientId]) {
            acc[patientId] = {
                patient: appointment.patient,
                appointments: []
            };
        }

        acc[patientId].appointments.push(appointment);
        return acc;
    }, {});

    return Object.values(groups);
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }).format(date);
    } catch (error) {
        return '';
    }
};

const formatTime = (time) => {
    if (!time) return '';
    try {
        const [hours, minutes] = time.split(':');
        return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
    } catch {
        return '';
    }
};

const getStatusClass = (status) => ({
    'scheduled': 'bg-yellow-100 text-yellow-800',
    'completed': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
}[status] || 'bg-gray-100 text-gray-800');

const cancelAppointment = (appointmentId) => {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        router.post(route('appointments.cancel', appointmentId), {
            _method: 'put'
        });
    }
};
</script>

<template>
    <AppLayout :title="pageTitle">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ pageTitle }}
                </h2>
                <Link
                    v-if="userRole === 'patient' || userRole === 'admin'"
                    :href="route('appointments.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    Nueva Cita
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="groupedAppointments.length === 0" class="text-center py-8">
                    <p class="text-gray-500">No hay citas programadas.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-6">
                    <div v-for="group in groupedAppointments" :key="group.patient.id"
                         class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Cabecera del Paciente -->
                        <div class="p-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ userRole === 'admin' ? `Paciente: ${group.patient.user?.name}` : 'Mis Citas' }}
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Citas -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="appointment in group.appointments" :key="appointment.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(appointment.date) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ formatTime(appointment.time) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            Dr. {{ appointment.doctor?.user?.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="`px-2 py-1 text-xs rounded-full ${getStatusClass(appointment.status)}`">
                                                {{ appointment.status === 'scheduled' ? 'Programada' :
                                                   appointment.status === 'completed' ? 'Completada' : 'Cancelada' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <Link
                                                    :href="route('appointments.edit', appointment.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    @click="cancelAppointment(appointment.id)"
                                                    class="text-red-600 hover:text-red-900 text-sm"
                                                >
                                                    Cancelar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
