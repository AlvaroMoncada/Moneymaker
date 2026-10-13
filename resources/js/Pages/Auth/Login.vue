<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-purple-300 to-purple-500 p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="bg-purple-600 text-white text-center py-4">
        <h2 class="text-2xl font-bold">MoneyMaker</h2>
      </div>
      
      <div class="p-6">
        <Head title="Login" />
        <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

        <form @submit.prevent="submit" class="space-y-5">
          <div>
            <InputLabel for="email" value="Correo Electrónico" class="text-gray-700" />
            <TextInput
              id="email"
              type="email"
              v-model="form.email"
              required
              class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-400 focus:outline-none"
            />
            <InputError :message="form.errors.email" class="mt-1 text-red-500 text-sm" />
          </div>

          <div>
            <InputLabel for="password" value="Contraseña" class="text-gray-700" />
            <TextInput
              id="password"
              type="password"
              v-model="form.password"
              required
              class="w-full mt-2 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-400 focus:outline-none"
            />
            <InputError :message="form.errors.password" class="mt-1 text-red-500 text-sm" />
          </div>

          <PrimaryButton class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg">Iniciar Sesión</PrimaryButton>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">
          <Link :href="route('register')" class="text-purple-600 hover:underline font-medium">Regístrate</Link>
        </p>
      </div>
    </div>
  </div>
</template>
