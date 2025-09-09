<script setup>
import HeaderLayout from "@layouts/Partials/HeaderLayout.vue";
import SidebarLayout from "@layouts/Partials/SidebarLayout.vue";
import MobileMenu from "@layouts/Partials/MobileMenu.vue";

import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import {
  evaluateFlashMessagesToast,
  evaluateValidationErrors,
} from "@commonServices/displayErrors";

const pageProps = computed(() => usePage().props);

evaluateFlashMessagesToast(pageProps);
evaluateValidationErrors(pageProps);
</script>
<template>
  <SidebarLayout />

  <HeaderLayout :current-route="pageProps.routeName" :permissions="pageProps?.auth.permissions"  />
  <!-- Header -->

  <main class="lg:ml-25">
    <div class="w-full lg:w-auto mx-auto lg:mx-0">
      <slot></slot>
    </div>
  </main>
</template>
