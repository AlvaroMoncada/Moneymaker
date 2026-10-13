<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="p-6 bg-white rounded-lg shadow-md space-y-6">
      <header class="border-b pb-4">
        <h2 class="text-lg font-semibold text-gray-800">Eliminar Cuenta</h2>
        <p class="mt-1 text-sm text-gray-600">
          Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de
          eliminar tu cuenta, descarga cualquier dato o información que desees conservar.
        </p>
      </header>
  
      <div class="flex justify-end">
        <DangerButton @click="confirmUserDeletion">Eliminar Cuenta</DangerButton>
      </div>
      
  
      <Modal :show="confirmingUserDeletion" @close="closeModal">
        <div class="p-6">
          <h2 class="text-lg font-semibold text-gray-800">
            Confirmar eliminación
          </h2>
          <p class="mt-1 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor,
            ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.
          </p>
  
          <div class="mt-6">
            <InputLabel for="password" value="Contraseña" class="sr-only" />
            <TextInput
              id="password"
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-3/4 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-red-300 focus:outline-none"
              placeholder="Contraseña"
              @keyup.enter="deleteUser"
            />
            <InputError :message="form.errors.password" class="mt-2 text-red-600" />
          </div>
  
          <div class="mt-6 flex justify-end space-x-3">
            <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
            <DangerButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
              @click="deleteUser"
            >
              Eliminar Cuenta
            </DangerButton>
          </div>
        </div>
      </Modal>
    </section>
  </template>
  