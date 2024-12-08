
<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    doctors: Array
});

const selectedDoctor = ref(null);
const selectedAvailability = ref(null);

const form = useForm({
    doctor_id: '',
    availability_id: '',
    reason: '',
    notes: ''
});

const availableSlots = computed(() => {
    return selectedDoctor.value?.availabilities?.filter(a => a.status === 'available') || [];
});

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    date.setDate(date.getDate() + 1);
    return new Intl.DateTimeFormat('es-ES', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
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
    if (selectedDoctor.value && selectedAvailability.value) {
        form.doctor_id = selectedDoctor.value.id;
        form.availability_id = selectedAvailability.value.id;
        form.post(route('appointments.store'));
    }
};
</script>

<template>
    <AppLayout title="Programar Cita">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <!-- Selección de Doctor -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Seleccione un Doctor</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="doctor in doctors"
                                     :key="doctor.id"
                                     class="border p-4 rounded-lg cursor-pointer"
                                     :class="{'border-blue-500': selectedDoctor?.id === doctor.id}"
                                     @click="selectedDoctor = doctor">
                                    <h4 class="font-medium">Dr. {{ doctor.user.name }}</h4>
                                    <p class="text-sm text-gray-600">{{ doctor.specialty }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Horarios Disponibles -->
                        <div v-if="selectedDoctor" class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Horarios Disponibles</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div v-for="availability in availableSlots"
                                     :key="availability.id"
                                     class="border p-4 rounded-lg cursor-pointer"
                                     :class="{'border-blue-500': selectedAvailability?.id === availability.id}"
                                     @click="selectedAvailability = availability">
                                    <p>{{ formatDate(availability.start_date) }}</p>
                                    <p>{{ formatTime(availability.start_time) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de la cita -->
                        <div v-if="selectedAvailability" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Motivo de la consulta</label>
                                <textarea
                                    v-model="form.reason"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    rows="3"
                                    required
                                ></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Notas adicionales</label>
                                <textarea
                                    v-model="form.notes"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    rows="2"
                                ></textarea>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700"
                                >
                                    Programar Cita
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
