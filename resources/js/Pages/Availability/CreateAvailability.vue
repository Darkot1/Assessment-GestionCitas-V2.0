<!-- CreateAvailability.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    doctor_id: '',
    start_date: '',
    end_date: '',
    start_time: '',
    end_time: '',
});

const submit = () => {
    console.log(form);
    form.post(route('availabilities.store'));
};
</script>

<template>
    <AppLayout title="Crear Disponibilidad">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Seleccionar Horario
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="start_date" value="Fecha Inicio" />
                                <TextInput
                                    id="start_date"
                                    v-model="form.start_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.start_date" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="end_date" value="Fecha Fin (Opcional)" />
                                <TextInput
                                    id="end_date"
                                    v-model="form.end_date"
                                    type="date"
                                    class="mt-1 block w-full"
                                />
                                <InputError :message="form.errors.end_date" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="start_time" value="Hora Inicio" />
                                <TextInput
                                    id="start_time"
                                    v-model="form.start_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.start_time" class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="end_time" value="Hora Fin" />
                                <TextInput
                                    id="end_time"
                                    v-model="form.end_time"
                                    type="time"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.end_time" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Guardar Horario
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
