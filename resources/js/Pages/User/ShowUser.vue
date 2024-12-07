<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  user: Object,
});
</script>

<template>
  <AppLayout title="Ver Usuario">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detalles del Usuario
      </h2>
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
              <p><strong>Disponibilidad:</strong></p>
              <pre>{{ JSON.stringify(user.doctor.availability, null, 2) }}</pre>
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

          <div class="flex justify-end mt-6">
            <Link
              :href="route('users.edit', user.id)"
              class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 mr-3"
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
      </div>
    </div>
  </AppLayout>
</template>
