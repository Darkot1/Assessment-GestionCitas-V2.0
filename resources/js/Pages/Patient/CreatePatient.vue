<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Alert from '@/Components/Alert.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const page = usePage();
const flash = page.props.flash || {};

const form = useForm({
  date_of_birth: '',
  phone: '',
  address: '',
});

const submit = () => {
  form.post(route('patients.store'));
};
</script>

<template>
  <AppLayout title="Registro de Paciente">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Registro de Paciente
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Alert
          v-if="$page.props.errors?.authorization"
          :message="$page.props.errors.authorization"
          type="error"
        />
        <Alert
          v-if="flash?.error"
          :message="flash.error"
          type="error"
        />
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit">
            <div class="mb-6">
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

            <div class="mb-6">
              <InputLabel for="phone" value="Teléfono" />
              <TextInput
                id="phone"
                v-model="form.phone"
                type="text"
                class="mt-1 block w-full"
              />
              <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <div class="mb-6">
              <InputLabel for="address" value="Dirección" />
              <TextInput
                id="address"
                v-model="form.address"
                type="text"
                class="mt-1 block w-full"
              />
              <InputError :message="form.errors.address" class="mt-2" />
            </div>

            <div class="flex items-center justify-end">
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Registrar
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
