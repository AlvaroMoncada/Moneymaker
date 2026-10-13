<template>
  <button 
    :type="type" 
    class="rounded-lg border border-transparent bg-gradient-to-r from-purple-500 to-purple-700 px-5 py-2 text-center text-sm font-semibold text-white transition-all duration-200 hover:from-purple-600 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-400 active:scale-95 shadow-md">
    <slot/>
  </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>