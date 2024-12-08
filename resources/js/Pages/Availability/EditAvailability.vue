<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    availability: {
        type: Object,
        required: true
    }
});

const form = useForm({
    status: props.availability.status,
    reason: props.availability.reason || '',
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
        console.error('Error formateando fecha:', error);
        return 'Fecha no válida';
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

const submit = () => {
    form.put(route('availabilities.update', props.availability.id));
};
</script>

<template>
    <AppLayout title="Editar Disponibilidad">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                Editar Disponibilidad
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Información de la cita -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Detalles de la Disponibilidad</h3>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Fecha:</span> {{ formatDate(props.availability.start_date) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Horario:</span>
                            {{ formatTime(props.availability.start_time) }} - {{ formatTime(props.availability.end_time) }}
                        </p>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="mb-6">
                            <InputLabel for="status" value="Estado" />
                            <select
                                id="status"
                                v-model="form.status"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                                <option value="available">Disponible</option>
                                <option value="booked">Reservado</option>
                                <option value="completed">Completada</option>
                                <option value="cancelled">Cancelada</option>
                                <option value="unavailable">No disponible</option>
                            </select>
                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <InputLabel for="reason" value="Razón del cambio" />
                            <textarea
                                id="reason"
                                v-model="form.reason"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                :required="form.status === 'unavailable'"
                                rows="3"
                                placeholder="Indique la razón del cambio de estado..."
                            ></textarea>
                            <InputError :message="form.errors.reason" class="mt-2" />
                            <p v-if="form.status === 'unavailable'" class="mt-1 text-sm text-gray-500">
                                * Obligatorio al marcar como no disponible
                            </p>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <Link
                                :href="route('availabilities.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-400"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton :disabled="form.processing">
                                Actualizar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
