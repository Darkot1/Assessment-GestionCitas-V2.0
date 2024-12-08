<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  patients: Array,
});
</script>

<template>
  <AppLayout title="Pacientes">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Lista de Pacientes
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="flex justify-end mb-6">
          </div>

          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Nacimiento</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="patient in patients" :key="patient.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.date_of_birth }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.phone }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ patient.address }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <Link
                    :href="route('patients.show', patient.id)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Ver
                  </Link>
                  <Link
                    :href="route('appointment-histories.index', { patient_id: patient.id })"
                    class="text-green-600 hover:text-green-900 mr-3"
                  >
                    Historial Médico
                  </Link>
                  <Link
                    v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.id === patient.user_id"
                    :href="route('patients.edit', patient.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Editar
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
