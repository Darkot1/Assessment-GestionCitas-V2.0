<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';

const props = defineProps({
    doctor: Object,
    availabilities: Array
});

const availableSlots = computed(() => {
    return props.availabilities
        .filter(a => a.status === 'available')
        .sort((a, b) => {
            const dateCompare = new Date(a.start_date) - new Date(b.start_date);
            return dateCompare === 0 ?
                   a.start_time.localeCompare(b.start_time) :
                   dateCompare;
        });
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        date.setDate(date.getDate() + 1);
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

const getStatusText = (status) => ({
    'available': 'Disponible',
    'booked': 'Reservado',
    'unavailable': 'No disponible'
}[status] || status);
</script>

<template>
    <AppLayout :title="`Disponibilidad - ${doctor.user.name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Horarios Disponibles - {{ doctor.user.name }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Información del Doctor</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-semibold">Especialidad:</span> {{ doctor.specialty }}
                        </p>
                    </div>

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hora Inicio
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hora Fin
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="availability in availableSlots" :key="availability.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(availability.start_date) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatTime(availability.start_time) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ formatTime(availability.end_time) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        {{ getStatusText(availability.status) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
