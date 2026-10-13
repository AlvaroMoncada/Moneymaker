<template>
  <div class="relative text-gray-500 focus-within:text-gray-600">
    <input class="rounded block w-full mt-1 text-sm text-black focus:border-gray-400 focus:outline-none focus:shadow-outline-gray form-input"
    :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input" :placeholder="text"
    :type="type" :required="required" :accept="accept">
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue','text','type','required','accept']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});
</script>