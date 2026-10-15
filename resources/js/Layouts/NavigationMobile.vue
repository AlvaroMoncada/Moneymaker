<template>
  <transition
    enter-active-class="transition ease-in-out duration-200"
    enter-from-class="opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition ease-in-out duration-200"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0">
    <div 
      v-show="$page.props.showingMobileMenu" 
      class="fixed inset-0 z-10 flex items-center justify-center bg-black bg-opacity-50">
    </div>
  </transition>

  <transition
    enter-active-class="transition ease-in-out duration-200 transform"
    enter-from-class="opacity-0 -translate-x-20"
    enter-to-class="opacity-100 translate-x-0"
    leave-active-class="transition ease-in-out duration-200 transform"
    leave-from-class="opacity-100 translate-x-0"
    leave-to-class="opacity-0 -translate-x-20">
    <aside 
      v-show="$page.props.showingMobileMenu" 
      class="fixed inset-y-0 left-0 z-20 w-64 mt-16 overflow-y-auto bg-gradient-to-b from-purple-800 to-purple-900 shadow-xl md:hidden rounded-r-lg">
      <div class="py-6 text-white">
        <Link class="block text-center text-2xl font-bold text-gray-100" :href="route('sells.index')">
          misVentas
        </Link>
        <ul class="mt-8 space-y-2">
          <li>
            <ResponsiveNavLink 
              :href="route('sells.index')" 
              :active="route().current('sells.index')" 
              class="block px-6 py-3 rounded-lg transition-all duration-300 hover:bg-purple-700"
            >
              Ventas
            </ResponsiveNavLink>
          </li>

          <li>
            <ResponsiveNavLink 
              :href="route('clients.index')" 
              :active="route().current('clients.index')" 
              class="block px-6 py-3 rounded-lg transition-all duration-300 hover:bg-purple-700"
            >
              Clientes
            </ResponsiveNavLink>
          </li>

          <li>
            <ResponsiveNavLink 
              :href="route('comissions.index')" 
              :active="route().current('comissions.index')" 
              class="block px-6 py-3 rounded-lg transition-all duration-300 hover:bg-purple-700"
            >
              Comisiones
            </ResponsiveNavLink>
          </li>
        </ul>
      </div>
    </aside>
  </transition>
</template>

<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

let showingTwoLevelMenu = ref(false);
</script>
