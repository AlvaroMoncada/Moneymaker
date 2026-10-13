<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section class="p-6 bg-white rounded-lg shadow-md">
      <header class="border-b pb-4 mb-6">
        <h2 class="text-lg font-semibold text-gray-800">Información del Perfil</h2>
        <p class="mt-1 text-sm text-gray-600">
          Actualiza la información de tu perfil y la dirección de correo electrónico de tu cuenta.
        </p>
      </header>
  
      <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6">
        <div>
          <InputLabel for="name" value="Nombre" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2 text-red-600" :message="form.errors.name" />
        </div>
  
        <div>
          <InputLabel for="email" value="Correo Electrónico" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none"
            v-model="form.email"
            required
            autocomplete="username"
          />
          <InputError class="mt-2 text-red-600" :message="form.errors.email" />
        </div>
  
        <div class="flex items-center gap-4">
            <div class="ml-auto">
                <PrimaryButton :disabled="form.processing">Guardar</PrimaryButton>
            </div>
  
          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-if="form.recentlySuccessful" class="text-sm text-green-600">Guardado.</p>
          </Transition>
        </div>
      </form>
    </section>
  </template>
  
