<template>
  <aside class="z-20 hidden w-64 bg-gradient-to-b from-purple-800 to-purple-900 md:block flex-shrink-0 shadow-lg">
    <div class="py-6 text-white">
      <Link class="block text-center text-2xl font-bold text-gray-100" :href="route('dashboard')">
        misVentas
      </Link>

      <ul class="mt-8 space-y-2 px-4">
        <li>
          <NavLink 
            :href="route('dashboard')" 
            :active="route().current('dashboard')" 
            class="block px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-700/70"
            :class="route().current('dashboard') ? 'bg-purple-700 text-white font-bold shadow' : 'text-purple-100'"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              <span>Inicio</span>
            </div>
          </NavLink>
        </li>

        <li>
          <NavLink 
            :href="route('sells.index')" 
            :active="route().current('sells.*')" 
            class="block px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-700/70"
            :class="route().current('sells.*') ? 'bg-purple-700 text-white font-bold shadow' : 'text-purple-100'"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
              <span>Ventas</span>
            </div>
          </NavLink>
        </li>

        <li>
          <NavLink 
            :href="route('clients.index')" 
            :active="route().current('clients.*')" 
            class="block px-4 py-3 rounded-xl transition-all duration-200 hover:bg-purple-700/70"
            :class="route().current('clients.*') ? 'bg-purple-700 text-white font-bold shadow' : 'text-purple-100'"
          >
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span>Clientes</span>
            </div>
          </NavLink>
        </li>
      </ul>
    </div>
  </aside>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

export default {
  components: {
    NavLink,
    Link,
  },
  setup() {
    let showingTwoLevelMenu = ref(false);
    return {
      showingTwoLevelMenu,
    };
  },
};
</script>