<!-- IndexAppointmentHistory.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    histories: Array,
    patient: Object
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
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

const getActionClass = (action) => ({
    'created': 'bg-green-100 text-green-800',
    'updated': 'bg-yellow-100 text-yellow-800',
    'cancelled': 'bg-red-100 text-red-800',
    'completed': 'bg-blue-100 text-blue-800'
}[action] || 'bg-gray-100 text-gray-800');

const downloadPdf = (historyId) => {
    window.open(route('appointment-histories.pdf', historyId), '_blank');
};
</script>

<template>
    <AppLayout :title="patient ? `Historial Médico - ${patient.user.name}` : 'Historial Médico'">
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ patient ? `Historial Médico - ${patient.user.name}` : 'Historial Médico' }}
                </h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-for="history in histories" :key="history.id" class="mb-8 border-b pb-6">
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-start">
                                <div class="grid grid-cols-2 gap-4 flex-grow">
                                    <div>
                                        <h3 class="text-lg font-semibold mb-2">Detalles de la Cita</h3>
                                        <p><span class="font-medium">Fecha:</span> {{ formatDate(history.appointment.date) }}</p>
                                        <p><span class="font-medium">Hora:</span> {{ formatTime(history.appointment.time) }}</p>
                                        <p><span class="font-medium">Doctor:</span> Dr. {{ history.doctor.user.name }}</p>
                                        <p><span class="font-medium">Paciente:</span> {{ history.patient.user.name }}</p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold mb-2">Estado de la Cita</h3>
                                        <p>
                                            <span :class="`px-2 py-1 text-xs rounded-full ${getActionClass(history.action)}`">
                                                {{ {
                                                    'created': 'Creada',
                                                    'updated': 'Actualizada',
                                                    'cancelled': 'Cancelada',
                                                    'completed': 'Completada'
                                                }[history.action] }}
                                            </span>
                                        </p>
                                        <p class="mt-2"><span class="font-medium">Fecha de actualización:</span> {{ formatDate(history.created_at) }}</p>
                                    </div>
                                </div>
                                <button
                                    v-if="history.action === 'completed'"
                                    @click="downloadPdf(history.id)"
                                    class="ml-4 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Descargar PDF
                                </button>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-medium mb-2">Motivo de la consulta</h4>
                            <p class="text-gray-700">{{ history.appointment.reason || 'No especificado' }}</p>
                        </div>

                        <div class="mt-4">
                            <h4 class="font-medium mb-2">Notas médicas</h4>
                            <p class="text-gray-700">{{ history.notes || 'Sin notas adicionales' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
