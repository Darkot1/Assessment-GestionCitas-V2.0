<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    availabilities: Array,
    isAdmin: Boolean
});

const page = usePage();
const userRole = computed(() => page.props.auth.user.role);

const pageTitle = computed(() => {
    return userRole.value === 'admin' ? 'Panel de Disponibilidades' : 'Mis Horarios';
});

// Agrupar por doctor y fecha
const groupedByDoctor = computed(() => {
    if (!props.availabilities) return [];

    const groups = props.availabilities.reduce((acc, availability) => {
        const doctorId = availability.doctor?.id;
        if (!doctorId) return acc;

        if (!acc[doctorId]) {
            acc[doctorId] = {
                doctor: availability.doctor,
                availabilities: []
            };
        }

        acc[doctorId].availabilities.push(availability);
        return acc;
    }, {});

    Object.values(groups).forEach(group => {
        group.availabilities.sort((a, b) => {
            const dateCompare = new Date(a.start_date) - new Date(b.start_date);
            return dateCompare === 0 ?
                   a.start_time.localeCompare(b.start_time) :
                   dateCompare;
        });
    });

    return Object.values(groups);
});

// Estadísticas por doctor
const getDoctorStats = (availabilities) => {
    return {
        total: availabilities.length,
        available: availabilities.filter(a => a.status === 'available').length,
        booked: availabilities.filter(a => a.status === 'booked').length
    };
};

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
        console.error('Error formateando fecha:', error);
        return '';
    }
};

const formatTime = (time) => {
    if (!time || typeof time !== 'string') return '';
    try {
        const [hours, minutes] = time.split(':');
        return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
    } catch {
        return '';
    }
};

const getStatusClass = (status) => ({
    'available': 'bg-green-100 text-green-800',
    'booked': 'bg-yellow-100 text-yellow-800',
    'unavailable': 'bg-red-100 text-red-800'
}[status] || 'bg-gray-100 text-gray-800');

const confirmDelete = (id) => {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede revertir",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('availabilities.destroy', id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Disponibilidad eliminada',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
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
                    v-if="userRole === 'admin' || userRole === 'doctor'"
                    :href="route('availabilities.create')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                    Nuevo Horario
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="groupedByDoctor.length === 0" class="text-center py-8">
                    <p class="text-gray-500">No hay horarios disponibles.</p>
                </div>

                <div v-else class="grid grid-cols-1 gap-6">
                    <div v-for="group in groupedByDoctor" :key="group.doctor.id"
                         class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <!-- Cabecera del Doctor -->
                        <div class="p-6 bg-gray-50 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ userRole === 'admin' ? `Dr. ${group.doctor.user?.name}` : 'Mis Horarios' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ group.doctor.specialty }}
                                    </p>
                                </div>
                                <div class="flex space-x-4">
                                    <div class="text-sm">
                                        <span class="text-green-600 font-semibold">
                                            {{ getDoctorStats(group.availabilities).available }}
                                        </span> disponibles
                                    </div>
                                    <div class="text-sm">
                                        <span class="text-yellow-600 font-semibold">
                                            {{ getDoctorStats(group.availabilities).booked }}
                                        </span> reservadas
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Disponibilidades -->
                        <div class="overflow-x-auto">
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
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="availability in group.availabilities" :key="availability.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ formatDate(availability.start_date) || 'Sin fecha' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatTime(availability.start_time) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatTime(availability.end_time) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="`px-2 py-1 text-xs rounded-full ${getStatusClass(availability.status)}`">
                                                {{ availability.status === 'available' ? 'Disponible' :
                                                   availability.status === 'booked' ? 'Reservado' : 'No disponible' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex space-x-2">
                                                <Link
                                                    v-if="isAdmin || page.props.auth.user?.doctor?.id === availability.doctor_id"
                                                    :href="route('availabilities.edit', availability.id)"
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm"
                                                >
                                                    Editar
                                                </Link>
                                                <button
                                                    v-if="isAdmin || page.props.auth.user?.doctor?.id === availability.doctor_id"
                                                    @click="confirmDelete(availability.id)"
                                                    class="text-red-600 hover:text-red-900 text-sm"
                                                >
                                                    Eliminar
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
