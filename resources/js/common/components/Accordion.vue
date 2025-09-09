<template>
    <div class="rounded-lg border border-gray-200">
        <button
            type="button"
            @click="isOpen = !isOpen"
            class="flex w-full items-center justify-between rounded-lg bg-white p-4 transition-colors duration-150 hover:bg-gray-50 focus:outline-none focus:ring-gray-200"
            :aria-expanded="isOpen"
        >
            <!-- Left side with optional icon and title -->
            <div class="flex flex-1 items-center gap-3">
                <!-- Custom icon slot -->
                <slot name="icon">
                    <component
                        :is="icon"
                        v-if="icon"
                        class="h-5 w-5"
                        :class="iconClass"
                    />
                </slot>

                <!-- Title slot -->
                <div>
                    <slot name="title">{{ title }}</slot>
                </div>
            </div>

            <!-- Chevron icon -->
            <slot name="chevron">
                <ChevronDown
                    class="h-5 w-5 transform transition-transform duration-200"
                    :class="{ 'rotate-180': isOpen }"
                />
            </slot>
        </button>

        <!-- Content with transition -->
        <Transition
            enter-active-class="transition-all duration-200 ease-out"
            leave-active-class="transition-all duration-200 ease-in"
            @enter="onEnter"
            @leave="onLeave"
        >
            <div v-show="isOpen" class="">
                <div class="bg-white p-4">
                    <slot />
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { ChevronDown } from 'lucide-vue-next';

// Props
defineProps({
    title: {
        type: String,
        default: '',
    },
    icon: {
        type: [Object, Function],
        default: null,
    },
    iconClass: {
        type: String,
        default: 'text-gray-500',
    },
});

const isOpen = ref(false);

// Smooth height animation functions
const onEnter = (el) => {
    el.style.height = 'auto';
    const height = el.offsetHeight;
    el.style.height = '0';
    el.offsetHeight; // Force reflow
    el.style.height = `${height}px`;
};

const onLeave = (el) => {
    el.style.height = `${el.offsetHeight}px`;
    el.offsetHeight; // Force reflow
    el.style.height = '0';
};
</script>
