<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-100 to-gray-300 border border-gray-400 rounded-full font-bold text-sm text-gray-800 uppercase tracking-wide shadow-lg transition-all duration-300 hover:from-purple-200 hover:to-purple-400 hover:border-purple-500 focus:outline-none focus:ring-4 focus:ring-purple-500 focus:ring-offset-2 disabled:opacity-50 active:scale-90"
    >
        <slot />
    </button>
</template>