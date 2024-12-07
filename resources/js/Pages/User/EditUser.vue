<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  user: Object,
});

const form = useForm({
  name: props.user.name,
  email: props.user.email,
  role: props.user.role,
});

const submit = () => {
  form.put(route('users.update', props.user.id));
};
</script>

<template>
  <AppLayout title="Editar Usuario">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Editar Usuario
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
              <InputLabel for="role" value="Rol" />
              <select
                id="role"
                v-model="form.role"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                required
              >
                <option value="admin">Administrador</option>
                <option value="doctor">Doctor</option>
                <option value="patient">Paciente</option>
              </select>
              <InputError :message="form.errors.role" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Actualizar Usuario
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
