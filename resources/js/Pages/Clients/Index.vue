<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputGroup from '@/Components/InputGroup.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

const props = defineProps({
  clients: { type: Object },
});

const form = useForm({
  name: '',
  address: '',
});
const selectedClient = ref({});
const showModal = ref({ form: false, delete: false });
const title = ref('');
const operation = ref(1);
const msj = ref('');
const classMsj = ref('hidden');

const getGoogleMapsUrl = (address) => {
  return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
};

const openModal = (type, client = {}) => {
  if (type === 'form') {
    operation.value = client.id ? 2 : 1;
    title.value = client.id ? 'Editar cliente' : 'Crear cliente';
    form.name = client.name || '';
    form.address = client.address || '';
    selectedClient.value.id = client.id || null;
    showModal.value.form = true;
  } else if (type === 'delete') {
    selectedClient.value.id = client.id;
    showModal.value.delete = true;
  }
};

const closeModal = (type) => {
  showModal.value[type] = false;
  if (type === 'form') {
    form.reset();
  }
};

const saveClient = () => {
  const routeAction = operation.value === 1 ? 'clients.store' : 'clients.update';
  const routeId = operation.value === 2 ? selectedClient.value.id : null;

  form[operation.value === 1 ? 'post' : 'put'](route(routeAction, routeId), {
    onSuccess: () => {
      closeModal('form');
      showSuccessMessage(operation.value === 1 ? 'Cliente añadido' : 'Cliente actualizado');
    },
  });
};

const deleteClient = () => {
  form.delete(route('clients.destroy', selectedClient.value.id), {
    onSuccess: () => {
      closeModal('delete');
      showSuccessMessage('Cliente eliminado');
    },
  });
};

const showSuccessMessage = (message) => {
  msj.value = message;
  classMsj.value = 'opacity-100 scale-100'; // Aparece la alerta
  setTimeout(() => {
    classMsj.value = 'opacity-0 scale-90'; // Oculta la alerta después de 5 segundos
  }, 5000);
};
</script>

<template>
  <Head title="Clientes" />
  <AuthenticatedLayout>
    <div
      :class="`fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg transition transform duration-300 ease-in-out ${classMsj}`"
      role="alert"
    >
      <div class="flex items-center">
        <span>{{ msj }}</span>
      </div>
    </div>

    <template #header>
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 w-full">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Directorio de Clientes</h1>
          <p class="text-xs text-gray-500 mt-0.5">Administra los datos de contacto y ubicaciones de tus clientes</p>
        </div>
        <div class="flex items-center space-x-3">
          <Link :href="route('dashboard')">
            <SecondaryButton class="flex items-center space-x-1.5 !py-2 !px-4">
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Pantalla Principal</span>
            </SecondaryButton>
          </Link>
          <PrimaryButton @click="openModal('form')" class="flex items-center space-x-1.5 !py-2 !px-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            <span>Añadir Cliente</span>
          </PrimaryButton>
        </div>
      </div>
    </template>

    <!-- Tabla de clientes -->
    <div class="w-full overflow-hidden rounded-lg border shadow-md">
      <div class="w-full overflow-x-auto bg-white">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="text-xs font-semibold text-gray-500 bg-purple-50">
              <th class="px-4 py-3 text-left">Nombre</th>
              <th class="px-4 py-3 text-left">Dirección</th>
              <th class="px-4 py-3 text-center">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="client in clients.data" :key="client.id" class="text-gray-700 border-b last:border-0 hover:bg-gray-50 transition">
              <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ client.name }}</td>
              <td class="px-4 py-3 text-sm">
                <div v-if="client.address" class="flex items-center space-x-2">
                  <span class="text-gray-700">{{ client.address }}</span>
                  <a
                    :href="getGoogleMapsUrl(client.address)"
                    target="_blank"
                    rel="noopener noreferrer"
                    title="Ver en Google Maps"
                    class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 hover:bg-purple-100 hover:text-purple-800 transition shadow-sm border border-purple-200"
                  >
                    <!-- Icono Map Pin -->
                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Google Maps</span>
                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                  </a>
                </div>
                <span v-else class="text-xs text-gray-400 italic">Sin dirección</span>
              </td>
              <td class="px-4 py-3 flex justify-center space-x-2">
                <PrimaryButton @click="openModal('form', client)">Editar</PrimaryButton>
                <DangerButton @click="openModal('delete', client)">Eliminar</DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Paginación centrada -->
      <div class="px-4 py-3 bg-purple-50 border-t">
        <div class="flex justify-center">
          <Pagination :links="clients.links" />
        </div>
      </div>
    </div>

    <!-- Modal Formulario -->
    <Modal :show="showModal.form" @close="closeModal('form')">
      <div class="p-6">
        <h2 class="text-lg font-medium text-purple-900 border-b pb-2">{{ title }}</h2>
        <div class="space-y-4 mt-4">
          <div>
            <InputLabel value="Nombre del cliente" class="font-medium text-gray-700 mb-1" />
            <InputGroup :text="'Nombre del cliente'" :required="'required'" v-model="form.name" />
          </div>
          <div>
            <InputLabel value="Dirección" class="font-medium text-gray-700 mb-1" />
            <InputGroup :text="'Ej. Av. de la Constitución 14, Sevilla'" v-model="form.address" />
          </div>
        </div>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4 bg-gray-50 rounded-b-lg">
        <SecondaryButton @click="closeModal('form')">Cancelar</SecondaryButton>
        <PrimaryButton @click="saveClient">Guardar</PrimaryButton>
      </div>
    </Modal>

    <!-- Modal Eliminar -->
    <Modal :show="showModal.delete" @close="closeModal('delete')">
      <div class="p-6 text-center">
        <p class="text-lg text-gray-600">Los datos se eliminarán</p>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4">
        <SecondaryButton @click="closeModal('delete')">Cancelar</SecondaryButton>
        <DangerButton @click="deleteClient">Eliminar</DangerButton>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>

