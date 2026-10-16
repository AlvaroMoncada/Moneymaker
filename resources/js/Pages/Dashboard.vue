<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import MiniCalendar from '@/Components/MiniCalendar.vue';
import { ref, computed } from 'vue';

const props = defineProps({
  totalEarnings: { type: Number, default: 0 },
  totalSellsCount: { type: Number, default: 0 },
  totalClientsCount: { type: Number, default: 0 },
  calendarEvents: { type: Array, default: () => [] },
});

const selectedCalendarDate = ref(null);

const onCalendarSelectDay = (dateStr) => {
  selectedCalendarDate.value = dateStr;
};

// Eventos/visitas correspondientes al día seleccionado
const selectedDayEvents = computed(() => {
  if (!selectedCalendarDate.value || !props.calendarEvents) return [];
  return props.calendarEvents.filter((ev) => ev.date === selectedCalendarDate.value);
});

// Formatear fecha para visualización (DD/MM/YYYY)
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
  <Head title="Inicio" />
  <AuthenticatedLayout>
    <!-- Contenedor principal con fondo de imagen personalizado y estilo Glassmorphism -->
    <div
      class="relative -m-6 p-4 min-h-[calc(100vh-4.5rem)] bg-cover bg-center bg-no-repeat bg-fixed flex flex-col justify-between"
      style="background-image: url('/images/Gemini_Generated_Image_arkkkzarkkkzarkk.jpg');"
    >
      <!-- Capa de oscurecimiento suave para garantizar contraste de textos y tarjetas -->
      <div class="absolute inset-0 bg-purple-950/25 pointer-events-none"></div>

      <!-- Contenido en primer plano -->
      <div class="relative z-10 space-y-3">
        <!-- Sección Principal: Ganancias Totales y Calendario -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
          <!-- Columna Izquierda: Tarjeta Grande de Ganancias y Detalle de Citas del Día -->
          <div class="lg:col-span-2 space-y-3">
            <!-- Cuadro de Ganancias Totales -->
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-md p-4 border border-white/60 flex flex-col justify-between">
              <div class="flex items-start justify-between">
                <div>
                  <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 mb-1">
                    Métricas de Comisión
                  </span>
                  <h2 class="text-base font-bold text-gray-800">Ganancias Totales Acumuladas</h2>
                  <p class="text-xs text-gray-500 mt-0.5">
                    Suma total de comisiones generadas por todas tus ventas registradas.
                  </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600 shadow-inner">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>

              <div class="mt-3 flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-3 pt-3 border-t border-gray-100">
                <div>
                  <span class="text-3xl sm:text-4xl font-black text-green-600 tracking-tight">
                    {{ Number(totalEarnings || 0).toFixed(2) }} €
                  </span>
                  <p class="text-xs text-gray-400 mt-0.5">Beneficio neto calculado por comisiones directas</p>
                </div>
                <Link :href="route('sells.index')">
                  <button
                    type="button"
                    class="inline-flex items-center space-x-1.5 text-sm font-semibold text-purple-700 hover:text-purple-900 transition underline underline-offset-4"
                  >
                    <span>Ver desglose en ventas</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                  </button>
                </Link>
              </div>
            </div>

            <!-- Detalle de Visitas para el día seleccionado en el Calendario -->
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-md p-3 border border-white/60">
              <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div class="flex items-center space-x-2">
                  <span class="w-3 h-3 rounded-full bg-purple-600 inline-block"></span>
                  <h3 class="text-base font-bold text-gray-800">
                    <span v-if="selectedCalendarDate">Visitas para el {{ formatDate(selectedCalendarDate) }}</span>
                    <span v-else>Próximas Visitas Agendadas</span>
                  </h3>
                </div>
                <button
                  v-if="selectedCalendarDate"
                  @click="selectedCalendarDate = null"
                  class="text-xs text-purple-700 hover:text-purple-900 font-semibold underline"
                >
                  Ver todas
                </button>
              </div>

              <!-- Lista de citas del día seleccionado -->
              <div v-if="selectedCalendarDate" class="mt-4">
                <div v-if="selectedDayEvents.length > 0" class="space-y-2.5">
                  <div
                    v-for="item in selectedDayEvents"
                    :key="item.id"
                    class="flex items-center justify-between p-3 rounded-xl bg-purple-50/90 border border-purple-100 text-sm hover:shadow-md transition"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 rounded-lg bg-purple-200 text-purple-800 flex items-center justify-center font-bold text-xs">
                        {{ item.client_name.substring(0, 2).toUpperCase() }}
                      </div>
                      <div>
                        <p class="font-bold text-gray-900">{{ item.client_name }}</p>
                        <p class="text-xs text-gray-500">{{ item.title }}</p>
                      </div>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-purple-200 text-purple-900">
                      {{ item.price }} €
                    </span>
                  </div>
                </div>
                <div v-else class="py-6 text-center text-sm text-gray-400 italic">
                  No hay visitas programadas para esta fecha.
                </div>
              </div>

              <!-- Lista general de las visitas agendadas cuando no hay día seleccionado -->
              <div v-else class="mt-4">
                <div v-if="calendarEvents.length > 0" class="space-y-2.5">
                  <div
                    v-for="item in calendarEvents.slice(0, 5)"
                    :key="item.id"
                    class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100 text-sm hover:bg-purple-50/60 transition"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">
                        {{ item.client_name.substring(0, 2).toUpperCase() }}
                      </div>
                      <div>
                        <p class="font-semibold text-gray-800">{{ item.client_name }}</p>
                        <p class="text-xs text-gray-500">{{ item.title }}</p>
                      </div>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-purple-100 text-purple-800">
                      {{ formatDate(item.date) }}
                    </span>
                  </div>
                </div>
                <div v-else class="py-6 text-center text-sm text-gray-400 italic">
                  No hay próximas visitas agendadas. Puedes programarlas al crear o editar una venta.
                </div>
              </div>
            </div>
          </div>

          <!-- Columna Derecha: Calendario -->
          <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-md p-2 border border-white/60 h-full">
              <MiniCalendar :events="calendarEvents" @select-day="onCalendarSelectDay" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
