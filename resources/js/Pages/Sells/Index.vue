<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputGroup from '@/Components/InputGroup.vue';
import SelectInput from '@/Components/SelectInput.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';

const props = defineProps({
  sells: { type: Object },
  clients: { type: Object },
  totalEarnings: { type: Number }, 
});

const form = useForm({ title: '', price: '', comission: '', client_id: '' });
const selectedSell = ref({});
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');
const operation = ref(1);
const msj = ref('');
const classMsj = ref('hidden');

const openModal = (type, sell = {}) => {
  if (type === 'form') {
    operation.value = sell.id ? 2 : 1;
    title.value = sell.id ? 'Editar venta' : 'Crear venta';
    form.title = sell.title || '';
    form.price = sell.price || '';
    form.comission = sell.comission !== undefined && sell.comission !== null ? sell.comission : '';
    form.client_id = sell.client_id || '';
    selectedSell.value.id = sell.id || null;
    showModalForm.value = true;
  } else if (type === 'delete') {
    selectedSell.value.id = sell.id;
    showModalDel.value = true;
  }
};

const closeModal = (type) => {
  if (type === 'form') {
    form.reset();
    showModalForm.value = false;
  }
  if (type === 'delete') showModalDel.value = false;
};

const saveSell = () => {
  const routeAction = operation.value === 1 ? 'sells.store' : 'sells.update';
  const routeId = operation.value === 2 ? selectedSell.value.id : null;

  if (operation.value === 1) {
    form.post(route(routeAction), {
      onSuccess: () => {
        closeModal('form');
        showSuccessMessage('Venta creada');
      },
    });
  } else {
    form.put(route(routeAction, routeId), {
      onSuccess: () => {
        closeModal('form');
        showSuccessMessage('Venta actualizada');
      },
    });
  }
};


const deleteSell = () => {
  form.delete(route('sells.destroy', selectedSell.value.id), {
    onSuccess: () => {
      closeModal('delete');
      showSuccessMessage('Venta eliminada');
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

// Función para calcular la ganancia basada en la comisión
const calculateEarnings = (sell) => {
  if (!sell.price || sell.comission === undefined || sell.comission === null) return 0;
  return (parseFloat(sell.price) * parseFloat(sell.comission)) / 100;
};

</script>


<template>
  <Head title="Ventas" />
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
        <h1 class="text-lg font-bold">Ventas</h1>
        <PrimaryButton @click="openModal('form')">
          Añadir
        </PrimaryButton>
      </div>
    </template>

    <!-- Tarjeta de Ganancia Total -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-4">
      <h2 class="text-lg font-bold text-gray-700">Ganancias Totales</h2>
      <p class="text-2xl text-green-600 font-semibold mt-2">
        {{ totalEarnings.toFixed(2) }} €
      </p>
    </div>

    <!-- Tabla de Ventas -->
    <div class="w-full overflow-hidden rounded-lg border shadow-md">
      <div class="w-full overflow-x-auto bg-white">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-purple-50">
              <th class="px-4 py-3">Descripción</th>
              <th class="px-4 py-3">Cliente</th>
              <th class="px-4 py-3">Precio</th>
              <th class="px-4 py-3">Comisión (%)</th>
              <th class="px-4 py-3">Ganancia</th>
              <th class="px-4 py-3 text-center">Opciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y">
            <tr v-for="sell in sells.data" :key="sell.id" class="text-gray-700">
              <td class="px-4 py-3 text-sm">{{ sell.title }}</td>
              <td class="px-4 py-3 text-sm">{{ sell.client?.name }}</td>
              <td class="px-4 py-3 text-sm">{{ sell.price }} €</td>
              <td class="px-4 py-3 text-sm">{{ sell.comission }} %</td>
              <td class="px-4 py-3 text-sm">{{ calculateEarnings(sell).toFixed(2) }} €</td>
              <td class="px-4 py-3 flex justify-center space-x-2">
                <PrimaryButton @click="openModal('form', sell)">
                  Editar
                </PrimaryButton>
                <DangerButton @click="openModal('delete', sell)">
                  Eliminar
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="px-4 py-3 bg-purple-50 border-t">
        <div class="flex justify-center">
          <Pagination :links="sells.links" />
        </div>
      </div>
    </div>

    <!-- Modal Formulario -->
    <Modal :show="showModalForm" @close="closeModal('form')">
      <div class="p-6">
        <h2 class="text-lg font-medium">{{ title }}</h2>
        <div class="space-y-6 mt-4">
          <InputGroup :text="'Descripción de la venta'" :required="'required'" v-model="form.title" />
          <InputGroup :text="'Precio (€)'" :type="'number'" :required="'required'" v-model="form.price" />
          <InputGroup :text="'Comisión (%)'" :type="'number'" :required="'required'" v-model="form.comission" />
          <SelectInput :text="'Cliente'" :required="'required'" v-model="form.client_id" :options="clients" />
        </div>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4">
        <SecondaryButton @click="closeModal('form')">Cancelar</SecondaryButton>
        <PrimaryButton @click="saveSell">Guardar</PrimaryButton>
      </div>
    </Modal>

    <!-- Modal Eliminar -->
    <Modal :show="showModalDel" @close="closeModal('delete')">
      <div class="p-6 text-center">
        <p class="text-lg text-gray-600">Confirmar eliminación</p>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4">
        <SecondaryButton @click="closeModal('delete')">Cancelar</SecondaryButton>
        <DangerButton @click="deleteSell">Eliminar</DangerButton>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>
