<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  events: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['select-day']);

const today = new Date();
const currentMonth = ref(today.getMonth());
const currentYear = ref(today.getFullYear());
const selectedDate = ref(null);

const monthNames = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const dayNames = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];

const monthLabel = computed(() => {
  return `${monthNames[currentMonth.value]} ${currentYear.value}`;
});

const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value -= 1;
  } else {
    currentMonth.value -= 1;
  }
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value += 1;
  } else {
    currentMonth.value += 1;
  }
};

const goToToday = () => {
  currentMonth.value = today.getMonth();
  currentYear.value = today.getFullYear();
  selectedDate.value = formatDateString(today.getFullYear(), today.getMonth(), today.getDate());
  emit('select-day', selectedDate.value);
};

const formatDateString = (year, monthIndex, dayNumber) => {
  const m = String(monthIndex + 1).padStart(2, '0');
  const d = String(dayNumber).padStart(2, '0');
  return `${year}-${m}-${d}`;
};

// Generar días del mes con relleno inicial según el día de la semana (Lunes = 0)
const calendarDays = computed(() => {
  const year = currentYear.value;
  const month = currentMonth.value;

  const firstDayOfMonth = new Date(year, month, 1);
  const totalDays = new Date(year, month + 1, 0).getDate();

  // En JS: Domingo es 0, Lunes es 1... Lo adaptamos para que Lunes sea 0
  let startOffset = firstDayOfMonth.getDay() - 1;
  if (startOffset === -1) startOffset = 6;

  const days = [];

  // Días vacíos previos
  for (let i = 0; i < startOffset; i++) {
    days.push({ empty: true, id: `empty-pre-${i}` });
  }

  const todayStr = formatDateString(today.getFullYear(), today.getMonth(), today.getDate());

  // Días del mes
  for (let day = 1; day <= totalDays; day++) {
    const dateStr = formatDateString(year, month, day);
    const isToday = dateStr === todayStr;
    const isSelected = dateStr === selectedDate.value;
    const hasEvents = props.events.some((ev) => ev.date === dateStr);

    days.push({
      empty: false,
      dayNumber: day,
      dateString: dateStr,
      isToday,
      isSelected,
      hasEvents,
      id: `day-${dateStr}`,
    });
  }

  return days;
});

const handleDayClick = (day) => {
  if (day.empty) return;
  selectedDate.value = day.dateString;
  emit('select-day', day.dateString);
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-md p-4 border border-purple-100 flex flex-col justify-between h-full">
    <!-- Header del Calendario -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-100">
      <div class="flex items-center space-x-2">
        <span class="text-base font-bold text-gray-800 capitalize">{{ monthLabel }}</span>
        <button
          type="button"
          @click="goToToday"
          class="text-xs bg-purple-50 text-purple-700 hover:bg-purple-100 font-semibold px-2 py-0.5 rounded transition"
          title="Ir a hoy"
        >
          Hoy
        </button>
      </div>
      <div class="flex items-center space-x-1">
        <button
          type="button"
          @click="prevMonth"
          class="p-1 rounded-md text-gray-500 hover:text-purple-700 hover:bg-purple-50 transition"
          aria-label="Mes anterior"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button
          type="button"
          @click="nextMonth"
          class="p-1 rounded-md text-gray-500 hover:text-purple-700 hover:bg-purple-50 transition"
          aria-label="Mes siguiente"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Días de la semana -->
    <div class="grid grid-cols-7 gap-1 text-center mt-3 text-xs font-semibold text-gray-400 notranslate" translate="no">
      <div v-for="(d, index) in dayNames" :key="index" class="py-1 select-none">
        {{ d }}
      </div>
    </div>

    <!-- Celdas de los días -->
    <div class="grid grid-cols-7 gap-1 text-center mt-1">
      <div
        v-for="day in calendarDays"
        :key="day.id"
        class="h-8 flex flex-col items-center justify-center relative"
      >
        <button
          v-if="!day.empty"
          type="button"
          @click="handleDayClick(day)"
          :class="[
            'w-7 h-7 flex items-center justify-center rounded-full text-xs transition duration-150 relative',
            day.isToday && !day.isSelected ? 'bg-purple-600 text-white font-bold shadow-sm' : '',
            day.isSelected && !day.isToday ? 'bg-purple-100 text-purple-900 font-bold ring-2 ring-purple-500' : '',
            day.isSelected && day.isToday ? 'bg-purple-700 text-white font-bold ring-2 ring-purple-300 shadow' : '',
            !day.isToday && !day.isSelected ? 'text-gray-700 hover:bg-purple-50 hover:text-purple-700' : '',
          ]"
        >
          <span>{{ day.dayNumber }}</span>
          <!-- Indicador de Eventos futuro -->
          <span
            v-if="day.hasEvents"
            :class="[
              'absolute bottom-0.5 w-1 h-1 rounded-full',
              day.isToday || day.isSelected ? 'bg-white' : 'bg-purple-600'
            ]"
          ></span>
        </button>
      </div>
    </div>

    <!-- Pie / Indicadores -->
    <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
      <div class="flex items-center space-x-1.5">
        <span class="w-2.5 h-2.5 rounded-full bg-purple-600 inline-block"></span>
        <span>Hoy</span>
      </div>
      <div v-if="events.length > 0" class="flex items-center space-x-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-purple-600 inline-block"></span>
        <span>Próxima visita</span>
      </div>
      <span v-else class="text-gray-400 italic">Sin visitas programadas</span>
    </div>
  </div>
</template>
