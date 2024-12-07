<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
  doctor: Object,
});
</script>

<template>
  <AppLayout title="Detalle del Doctor">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Detalle del Doctor
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
            <div class="mt-4">
              <p class="text-sm text-gray-600"><span class="font-semibold">Nombre:</span> {{ doctor.user.name }}</p>
              <p class="text-sm text-gray-600"><span class="font-semibold">Email:</span> {{ doctor.user.email }}</p>
              <p class="text-sm text-gray-600"><span class="font-semibold">Especialidad:</span> {{ doctor.specialty }}</p>
              <p class="text-sm text-gray-600"><span class="font-semibold">Teléfono:</span> {{ doctor.phone }}</p>
              <p class="text-sm text-gray-600"><span class="font-semibold">Dirección:</span> {{ doctor.address }}</p>
            </div>
          </div>

          <div class="flex justify-end space-x-4">
            <Link
              v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.id === doctor.user_id"
              :href="route('doctors.edit', doctor.id)"
              class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700"
            >
              Editar
            </Link>
            <Link
              :href="route('doctors.index')"
              class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-gray-700 hover:bg-gray-400"
            >
              Volver
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
