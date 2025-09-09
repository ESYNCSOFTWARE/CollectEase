<template>
    <Tippy
        tag="button"
        :type="type"
        :disabled="state.isDisabled"
        :content="title"
        class="focus:ring-primary bg-primary border-primary inline-flex w-24 cursor-pointer items-center justify-center rounded-md  px-3 py-2 font-medium text-white shadow-sm transition duration-200 focus:ring-4 focus:ring-opacity-20 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-70 [&:hover:not(:disabled)]:border-opacity-90 [&:hover:not(:disabled)]:bg-opacity-90 [&:not(button)]:text-center"
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
