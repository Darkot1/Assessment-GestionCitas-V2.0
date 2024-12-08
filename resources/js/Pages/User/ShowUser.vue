<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  user: Object,
});

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('es-ES');
};

const formatTime = (timeString) => {
  return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('es-ES', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getStatusText = (status) => {
  const statusMap = {
    'available': 'Disponible',
    'booked': 'Reservado',
    'unavailable': 'No Disponible',
    'cancelled': 'Cancelado',
    'completed': 'Completado'
  };
  return statusMap[status] || status;
};

const getStatusClass = (status) => {
  const statusClasses = {
    'available': 'bg-green-100 text-green-800',
    'booked': 'bg-blue-100 text-blue-800',
    'unavailable': 'bg-gray-100 text-gray-800',
    'cancelled': 'bg-red-100 text-red-800',
    'completed': 'bg-purple-100 text-purple-800'
  };
  return statusClasses[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
  <AppLayout title="Ver Usuario">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Detalles del Usuario
        </h2>
        <div class="flex space-x-3">
          <Link
            :href="route('users.edit', user.id)"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
          >
            Editar
          </Link>
          <Link
            :href="route('users.index')"
            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
          >
            Volver
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Información General</h3>
            <div class="mt-4">
              <p><strong>Nombre:</strong> {{ user.name }}</p>
              <p><strong>Email:</strong> {{ user.email }}</p>
              <p><strong>Rol:</strong> {{ user.role }}</p>
            </div>
          </div>

          <div v-if="user.doctor" class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Información de Doctor</h3>
            <div class="mt-4">
              <p><strong>Especialidad:</strong> {{ user.doctor.specialty }}</p>
              <p><strong>Teléfono:</strong> {{ user.doctor.phone }}</p>
              <p><strong>Dirección:</strong> {{ user.doctor.address }}</p>

              <!-- Sección de Horarios -->
              <div v-if="user.doctor.availabilities && user.doctor.availabilities.length > 0" class="mt-4">
                <h4 class="text-md font-medium text-gray-900 mb-2">Horarios Disponibles:</h4>
                <div class="grid gap-4">
                  <div v-for="availability in user.doctor.availabilities" :key="availability.id"
                       class="border rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                      <span class="font-medium">
                        {{ formatDate(availability.start_date) }}
                      </span>
                      <span :class="`px-2 py-1 rounded-full text-sm ${getStatusClass(availability.status)}`">
                        {{ getStatusText(availability.status) }}
                      </span>
                    </div>
                    <p>
                      <strong>Horario:</strong>
                      {{ formatTime(availability.start_time) }} - {{ formatTime(availability.end_time) }}
                    </p>
                    <p v-if="availability.reason" class="text-sm text-gray-600 mt-1">
                      <strong>Motivo:</strong> {{ availability.reason }}
                    </p>
                  </div>
                </div>
              </div>
              <p v-else class="text-gray-600 mt-2">No hay horarios configurados</p>
            </div>
          </div>

          <div v-if="user.patient" class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Información de Paciente</h3>
            <div class="mt-4">
              <p><strong>Fecha de Nacimiento:</strong> {{ user.patient.date_of_birth }}</p>
              <p><strong>Teléfono:</strong> {{ user.patient.phone }}</p>
              <p><strong>Dirección:</strong> {{ user.patient.address }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
