<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputGroup from '@/Components/InputGroup.vue';
import SelectInput from '@/Components/SelectInput.vue';
import MiniCalendar from '@/Components/MiniCalendar.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  sells: { type: Object },
  clients: { type: Object },
  totalEarnings: { type: Number },
  calendarEvents: { type: Array, default: () => [] },
});

const getTodayDate = () => new Date().toISOString().split('T')[0];

const form = useForm({
  title: '',
  price: '',
  comission: '',
  sell_date: getTodayDate(),
  next_visit: '',
  client_id: '',
});

const selectedSell = ref({});
const showModalForm = ref(false);
const showModalDel = ref(false);
const title = ref('');
const operation = ref(1);
const msj = ref('');
const classMsj = ref('hidden');
const selectedCalendarDate = ref(null);

const onCalendarSelectDay = (dateStr) => {
  selectedCalendarDate.value = dateStr;
};

// Visitas programadas para el día seleccionado en el calendario
const selectedDayEvents = computed(() => {
  if (!selectedCalendarDate.value || !props.calendarEvents) return [];
  return props.calendarEvents.filter((ev) => ev.date === selectedCalendarDate.value);
});

const openModal = (type, sell = {}) => {
  if (type === 'form') {
    operation.value = sell.id ? 2 : 1;
    title.value = sell.id ? 'Editar venta' : 'Crear venta';
    form.title = sell.title || '';
    form.price = sell.price || '';
    form.comission = sell.comission !== undefined && sell.comission !== null ? sell.comission : '';
    form.sell_date = sell.sell_date ? sell.sell_date.substring(0, 10) : getTodayDate();
    form.next_visit = sell.next_visit ? sell.next_visit.substring(0, 10) : '';
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
    form.sell_date = getTodayDate();
    form.next_visit = '';
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

// Formatear fecha para la tabla (DD/MM/YYYY)
const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const parts = dateStr.substring(0, 10).split('-');
  if (parts.length === 3) {
    return `${parts[2]}/${parts[1]}/${parts[0]}`;
  }
  return dateStr;
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
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 w-full">
        <div>
          <h1 class="text-2xl font-bold text-gray-800">Gestión de Ventas</h1>
          <p class="text-xs text-gray-500 mt-0.5">Crea, edita y consulta el historial completo de ventas</p>
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Añadir Venta</span>
          </PrimaryButton>
        </div>
      </div>
    </template>

    <!-- Tabla de Ventas -->
    <div class="w-full overflow-hidden rounded-lg border shadow-md">
      <div class="w-full overflow-x-auto bg-white">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 border-b bg-purple-50">
              <th class="px-4 py-3">Fecha</th>
              <th class="px-4 py-3">Próxima visita</th>
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
              <td class="px-4 py-3 text-sm whitespace-nowrap">{{ formatDate(sell.sell_date) }}</td>
              <td class="px-4 py-3 text-sm whitespace-nowrap">
                <span
                  v-if="sell.next_visit"
                  class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800"
                >
                  {{ formatDate(sell.next_visit) }}
                </span>
                <span v-else class="text-gray-400 text-xs">-</span>
              </td>
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
        <h2 class="text-lg font-semibold text-purple-900 border-b pb-2">{{ title }}</h2>
        <div class="space-y-4 mt-4">
          <div>
            <InputLabel value="Descripción de la venta" class="font-medium text-gray-700 mb-1" />
            <InputGroup :text="'Ej. Venta de servicio web'" :required="'required'" v-model="form.title" />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <InputLabel value="Precio (€)" class="font-medium text-gray-700 mb-1" />
              <InputGroup :text="'0.00'" :type="'number'" :required="'required'" v-model="form.price" />
            </div>
            <div>
              <InputLabel value="Comisión (%)" class="font-medium text-gray-700 mb-1" />
              <InputGroup :text="'0.00'" :type="'number'" :required="'required'" v-model="form.comission" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <InputLabel value="Fecha de la venta" class="font-medium text-gray-700 mb-1" />
              <InputGroup :text="'Fecha de la venta'" :type="'date'" :required="'required'" v-model="form.sell_date" />
            </div>
            <div>
              <InputLabel value="Próxima visita" class="font-medium text-gray-700 mb-1" />
              <InputGroup :text="'Próxima visita'" :type="'date'" v-model="form.next_visit" />
            </div>
          </div>

          <div>
            <InputLabel value="Cliente" class="font-medium text-gray-700 mb-1" />
            <SelectInput :text="'Selecciona un cliente'" :required="'required'" v-model="form.client_id" :options="clients" />
          </div>
        </div>
      </div>
      <div class="flex justify-end mt-4 space-x-2 p-4 bg-gray-50 rounded-b-lg">
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
