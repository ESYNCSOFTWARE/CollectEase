<template>
    <Tippy
        tag="button"
        :type="type"
        :disabled="state.isDisabled"
        :content="title"
        class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white transition-all duration-300 hover:shadow-lg hover:from-blue-700 hover:to-indigo-800 focus:ring-primary bg-primary border-primary inline-flex w-24 cursor-pointer items-center justify-center font-medium shadow-sm focus:ring-4 focus:ring-opacity-20 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-70 [&:hover:not(:disabled)]:border-opacity-90 [&:hover:not(:disabled)]:bg-opacity-90 [&:not(button)]:text-center"
    >
        {{ text }}
    </Tippy>
</template>
<script setup>
import { onMounted, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

const state = reactive({
    isDisabled: {
        type: Boolean,
        default: false,
    },
});

const props = defineProps({
    text: {
        type: String,
        default: null,
    },
    type: {
        type: String,
        default: 'submit',
    },
    title: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    disableOnSubmit: {
        type: Boolean,
        default: true,
    },
});

onMounted(() => {
    state.isDisabled = props.disabled;

    router.on('start', () => {
        state.isDisabled = props.disableOnSubmit || props.disabled;
    });

    router.on('finish', () => {
        state.isDisabled = props.disabled;
    });
});
</script>
