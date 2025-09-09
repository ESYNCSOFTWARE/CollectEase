<template>
    <div class="flex flex-wrap">
        <div class="w-1/2 gap-4 flex justify-start items-center pr-8">
            <div class="flex flex-row justify-start">
                <button class="bg-primary-p5 outline-0 flex-none w-11 h-11  border hover:bg-blue-800 hover:text-white  grid place-content-center cursor-pointer rounded-full" @click="clicked">
                    <ArrowLeft />
                </button>
            </div>

            <div class="text-2xl font-inter font-medium text-primary w-full">
                <slot name="info"></slot>
            </div>

            <!-- <hr class="w-10 mt-2 p-px bg-accent-a2 border border-accent-a2 rounded-full"> -->
        </div>

        <div class="w-1/2" v-if="hasExtraInformation">
            <div class="w-full rounded-20 bg-white shadow-5 px-6 py-4">
                <h2 class="text-lg font-bold text-primary-p5">Important Instructions</h2>

                <slot name="modal-information"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowLeft } from 'lucide-vue-next';

defineProps({
    hasExtraInformation: Boolean,
});

const emits = defineEmits(['clicked']);

const clicked = () =>  {
    emits('clicked', true);
};
</script>
