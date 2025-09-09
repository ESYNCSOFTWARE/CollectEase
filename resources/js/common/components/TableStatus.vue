<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    status: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(['update:status']);

const currentStatus = ref(props.status);

watch(
    () => props.status,
    (newStatus) => {
        currentStatus.value = newStatus;
    },
);

const toggleStatus = () => {
    currentStatus.value = currentStatus.value === true ? false : true;
    emits('update:status', currentStatus.value);
};
</script>

<template>
    <div
        class="cm-toggle-wrapper m-2 flex cursor-pointer items-center"
        @click="toggleStatus"
    >
        <div
            class="h-4 w-8 rounded-full bg-gray-300 p-0.5"
            :class="{
                'bg-red-500': currentStatus === false,
                'bg-green-500': currentStatus === true,
            }"
        >
            <div
                class="mx-auto h-3 w-3 transform rounded-full bg-white duration-300 ease-in-out"
                :class="{
                    '-translate-x-2': currentStatus === false,
                    'translate-x-2': currentStatus === true,
                }"
            ></div>
        </div>
    </div>
</template>
