<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  patient: Object,
});

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};
</script>

<template>
  <AppLayout title="Detalle del Paciente">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detalle del Paciente
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
            <div class="mt-4">
              <p class="text-sm text-gray-600">
                <span class="font-semibold">Nombre:</span>
                {{ patient?.user?.name || 'No disponible' }}
              </p>
              <p class="text-sm text-gray-600">
                <span class="font-semibold">Email:</span>
                {{ patient?.user?.email || 'No disponible' }}
              </p>
              <p class="text-sm text-gray-600">
                <span class="font-semibold">Fecha de Nacimiento:</span>
                {{ formatDate(patient?.date_of_birth) || 'No disponible' }}
              </p>
              <p class="text-sm text-gray-600">
                <span class="font-semibold">Teléfono:</span>
                {{ patient?.phone || 'No disponible' }}
              </p>
              <p class="text-sm text-gray-600">
                <span class="font-semibold">Dirección:</span>
                {{ patient?.address || 'No disponible' }}
              </p>
            </div>
          </div>

          <div class="flex justify-end space-x-4">
            <Link
              v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.id === patient?.user_id"
              :href="route('patients.edit', patient.id)"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700"
            >
              Editar
            </Link>
            <Link
              :href="route('appointment-histories.index', { patient_id: patient.id })"
              class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700"
            >
              Historial Médico
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
