<template>
    <button
      class="bg-primary text-white font-medium cursor-pointer
             px-3 py-2 rounded-md shadow-md mr-2
             hover:bg-primary/end hover:border-primary/end
             focus:ring-4 focus:ring-primary/20 focus:outline-none
             disabled:cursor-not-allowed disabled:opacity-70"
    >
      <slot />
    </button>
  </template>
  
  <script setup>
  defineProps({
    text: {
      type: String,
    },
  });
  </script>
  