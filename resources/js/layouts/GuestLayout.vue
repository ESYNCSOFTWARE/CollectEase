<template>
    <div>
        <slot></slot>
    </div>
 
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import {
    evaluateFlashMessagesToast,
    evaluateValidationErrors,
} from '@commonServices/displayErrors';

const pageProps = computed(() => usePage().props);

evaluateFlashMessagesToast(pageProps);
evaluateValidationErrors(pageProps);
</script>
