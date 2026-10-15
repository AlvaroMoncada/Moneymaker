<template>
  <aside class="z-20 hidden w-64 bg-gradient-to-b from-purple-800 to-purple-900 md:block flex-shrink-0 shadow-lg">
    <div class="py-6 text-white">
      <Link class="block text-center text-2xl font-bold text-gray-100" :href="route('sells.index')">
        misVentas
      </Link>

      <ul class="mt-8">
        <li>
          <NavLink 
            :href="route('sells.index')" 
            :active="route().current('sells.index')" 
            class="block px-6 py-6 rounded-lg transition-all duration-300 hover:bg-purple-700"
          >
            Ventas
          </NavLink>
        </li>

        <li>
          <NavLink 
            :href="route('clients.index')" 
            :active="route().current('clients.index')" 
            class="block px-6 py-6 rounded-lg transition-all duration-300 hover:bg-purple-700"
          >
            Clientes
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