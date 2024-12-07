<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';
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
  specialty: props.user.doctor?.specialty || '',
  phone: props.user.doctor?.phone || props.user.patient?.phone || '',
  address: props.user.doctor?.address || props.user.patient?.address || '',
  date_of_birth: props.user.patient?.date_of_birth || '',
});

// Observar cambios en el rol para mantener datos
watch(() => form.role, (newRole, oldRole) => {
  if (newRole === oldRole) return;

  // Mantener datos comunes al cambiar roles
  if ((oldRole === 'doctor' || oldRole === 'patient') &&
      (newRole === 'doctor' || newRole === 'patient')) {
    form.phone = form.phone || '';
    form.address = form.address || '';
  }
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
            <!-- Campos básicos de usuario -->
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

            <!-- Campos de Doctor -->
            <template v-if="form.role === 'doctor'">
              <div class="mb-4">
                <InputLabel for="specialty" value="Especialidad" />
                <TextInput
                  id="specialty"
                  v-model="form.specialty"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.specialty" class="mt-2" />
              </div>
            </template>

            <!-- Campos de Paciente -->
            <template v-if="form.role === 'patient'">
              <div class="mb-4">
                <InputLabel for="date_of_birth" value="Fecha de Nacimiento" />
                <TextInput
                  id="date_of_birth"
                  v-model="form.date_of_birth"
                  type="date"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.date_of_birth" class="mt-2" />
              </div>
            </template>

            <!-- Campos comunes para Doctor y Paciente -->
            <template v-if="form.role === 'doctor' || form.role === 'patient'">
              <div class="mb-4">
                <InputLabel for="phone" value="Teléfono" />
                <TextInput
                  id="phone"
                  v-model="form.phone"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.phone" class="mt-2" />
              </div>

              <div class="mb-4">
                <InputLabel for="address" value="Dirección" />
                <TextInput
                  id="address"
                  v-model="form.address"
                  type="text"
                  class="mt-1 block w-full"
                  required
                />
                <InputError :message="form.errors.address" class="mt-2" />
              </div>
            </template>

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
