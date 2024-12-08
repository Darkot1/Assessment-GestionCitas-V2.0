<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
  name: '',
  email: '',
  password: '',
  role: '', 
});

const submit = () => {
  form.post(route('users.store'));
};
</script>

<template>
  <AppLayout title="Crear Usuario">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Crear Usuario
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="mb-4">
              <InputLabel for="name" value="Nombre" />
              <TextInput
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mb-4">
              <InputLabel for="email" value="Email" />
              <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <div class="mb-4">
              <InputLabel for="password" value="Contraseña" />
              <TextInput
                id="password"
                v-model="form.password"
                type="password"
                class="mt-1 block w-full"
                required
              />
              <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mb-4">
              <InputLabel for="role" value="Rol (Opcional)" />
              <select
                id="role"
                v-model="form.role"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              >
                <option value="">Sin rol asignado</option>
                <option value="admin">Administrador</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Paciente</option>
              </select>
              <p class="mt-1 text-sm text-gray-500">
                Puede asignar un rol ahora o más tarde
              </p>
              <InputError :message="form.errors.role" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Crear Usuario
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
