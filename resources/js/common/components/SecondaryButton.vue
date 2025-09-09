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
        class="focus:ring-primary bg-white [&amp;:hover:not(:disabled)]:bg-opacity-90 [&amp;:hover:not(:disabled)]:border-opacity-90 [&amp;:not(button)]:text-center border-secondary [&amp;:hover:not(:disabled)]:bg-secondary/20 [&amp;:hover:not(:disabled)]:dark:bg-darkmode-100/10 mr-2 inline-flex w-24 cursor-pointer items-center justify-center rounded-md  px-3 py-2 font-medium text-black shadow-sm transition duration-200 focus:ring-4 focus:ring-opacity-20 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-70"
    >
        <slot />
    </button>
</template>
