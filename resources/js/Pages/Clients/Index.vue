<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputGroup from '@/Components/InputGroup.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

const props = defineProps({
  clients: { type: Object },
});

const form = useForm({ name: '' });
const selectedClient = ref({});
const showModal = ref({ form: false, delete: false });
const title = ref('');
const operation = ref(1);
const msj = ref('');
const classMsj = ref('hidden');

const openModal = (type, client = {}) => {
  if (type === 'form') {
    operation.value = client.id ? 2 : 1;
    title.value = client.id ? 'Editar cliente' : 'Crear cliente';
    form.name = client.name || '';
    selectedClient.value.id = client.id || null;
    showModal.value.form = true;
  } else if (type === 'delete') {
    selectedClient.value.id = client.id;
    showModal.value.delete = true;
  }
};

const closeModal = (type) => {
  showModal.value[type] = false;
  if (type === 'form') form.reset();
};

const saveClient = () => {
  const routeAction = operation.value === 1 ? 'clients.store' : 'clients.update';
  const routeId = operation.value === 2 ? selectedClient.value.id : null;

  form[operation.value === 1 ? 'post' : 'put'](route(routeAction, routeId), {
    onSuccess: () => {
      closeModal('form');
      showSuccessMessage('Cliente añadido');
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
      <div class="flex justify-between items-center w-full">
        <h1 class="text-lg font-bold">Clientes</h1>
        <PrimaryButton @click="openModal('form')">
          Añadir
        </PrimaryButton>
      </div>
    </template>

    <!-- Tabla de clientes -->
    <div class="w-full overflow-hidden rounded-lg border shadow-md">
      <div class="w-full overflow-x-auto bg-white">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="text-xs font-semibold text-gray-500 bg-purple-50">
              <th class="px-4 py-3">Nombre</th>
              <th class="px-4 py-3 text-center">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="client in clients.data" :key="client.id" class="text-gray-700">
              <td class="px-4 py-3 text-sm">{{ client.name }}</td>
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
        <h2 class="text-lg font-medium">{{ title }}</h2>
        <div class="space-y-4 mt-4">
          <InputGroup :text="'Cliente'" v-model="form.name" />
        </div>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4">
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

