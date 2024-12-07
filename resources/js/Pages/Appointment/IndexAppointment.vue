<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  appointments: Array,
});
</script>

<template>
  <AppLayout title="Citas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Lista de Citas
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-end mb-6">
            <Link
              :href="route('appointments.create')"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            >
              Nueva Cita
            </Link>
          </div>

          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fecha
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Hora
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Doctor
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Paciente
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Estado
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="appointment in appointments" :key="appointment.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ appointment.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ appointment.time }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ appointment.doctor?.user?.name || 'No disponible' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  {{ appointment.patient?.user?.name || 'No disponible' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="{
                    'px-2 py-1 rounded-full text-xs font-semibold': true,
                    'bg-green-100 text-green-800': appointment.status === 'completed',
                    'bg-yellow-100 text-yellow-800': appointment.status === 'scheduled',
                    'bg-red-100 text-red-800': appointment.status === 'cancelled'
                  }">
                    {{ appointment.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <Link
                    :href="route('appointments.edit', appointment.id)"
                    class="text-indigo-600 hover:text-indigo-900 mr-3"
                  >
                    Editar
                  </Link>
                  <Link
                    :href="route('appointments.destroy', appointment.id)"
                    method="delete"
                    as="button"
                    class="text-red-600 hover:text-red-900"
                  >
                    Cancelar
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
