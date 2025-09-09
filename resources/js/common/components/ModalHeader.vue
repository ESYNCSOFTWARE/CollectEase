<template>
    <div>
        <div
            class="flex shrink-0 items-center pb-4 text-xl font-medium text-slate-800"
        >
            <span class="m-3 cursor-pointer text-red-600" @click="clicked"
                ><CircleX
            /></span>
            <slot></slot>
        </div>
    </div>
</template>
<script setup>
import { CircleX } from 'lucide-vue-next';

defineProps({
    hasExtraInformation: Boolean,
});

const emits = defineEmits(['clicked']);

const clicked = () => {
    emits('clicked', true);
};
</script>
