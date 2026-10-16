<template>
    <header class="z-10 py-4 bg-white shadow-md border-2 border-b-gray-800">
      <div class="container flex justify-between items-center px-6 mx-auto h-full text-gray-600">
        <div class="flex items-center space-x-3">
          <!-- Mobile hamburger -->
          <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu" class="p-1 mr-2 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-gray" aria-label="Menu">
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
          </button>

          <!-- Botón Volver al Inicio visible en todas las páginas -->
          <Link
            :href="route('dashboard')"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold transition-all duration-200"
            :class="route().current('dashboard') 
              ? 'bg-purple-100 text-purple-800 border border-purple-200 shadow-sm' 
              : 'text-gray-600 hover:text-purple-700 hover:bg-purple-50'"
            title="Volver a la Pantalla Principal"
          >
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="hidden sm:inline">Pantalla Principal</span>
            <span class="sm:hidden">Inicio</span>
          </Link>
        </div>

        <Dropdown>
          <template #trigger>
            <button class="align-middle rounded-full focus:shadow-outline-gray focus:outline-none" aria-label="Account" aria-haspopup="true">
              {{ $page.props.auth.user.name }}
            </button>
          </template>

          <template #content>
            <DropdownLink :href="route('profile.edit')">
              <template #icon>
                <svg class="mr-3 w-4 h-4" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Perfil
              </template>
            </DropdownLink>

            <DropdownLink :href="route('logout')" method="post" as="button">
              <template #icon>
                <svg class="mr-3 w-4 h-4" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Cerar sesión
              </template>
            </DropdownLink>
          </template>
        </Dropdown>

      </div>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
</script>