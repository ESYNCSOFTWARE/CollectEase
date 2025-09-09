<template>
  <nav class="w-full flex justify-center sm:justify-start mt-4">
    <ul class="flex flex-wrap items-center gap-1 sm:gap-2">
      <!-- Previous Button -->
      <li>
        <button
          type="button"
          class="flex items-center justify-center rounded-md cursor-pointer  border border-gray-300 bg-white px-3 py-2 text-gray-700 shadow-sm transition duration-200 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="currentPage <= 1"
          @click="gotoPage(currentPage - 1)"
        >
          <ChevronLeft class="h-5 w-5" />
        </button>
      </li>

      <!-- Page Numbers -->
      <li v-for="(pageNumber, key) in getPageNumbers()" :key="'pagination-link-' + key">
        <button
          type="button"
          @click="gotoPage(pageNumber)"
          :disabled="currentPage === pageNumber"
          :class="[
            'min-w-[36px] px-3 py-2 rounded-md text-sm font-medium cursor-pointer  transition-all duration-200 focus:ring-2 focus:ring-primary focus:ring-opacity-20 focus:outline-none',
            currentPage === pageNumber
              ? 'bg-primary text-white border border-primary shadow'
              : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-100',
          ]"
        >
          {{ pageNumber }}
        </button>
      </li>

      <!-- Next Button -->
      <li>
        <button
          type="button"
          class="flex items-center justify-center rounded-md cursor-pointer border border-gray-300 bg-white px-3 py-2 text-gray-700 shadow-sm transition duration-200 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
          :disabled="Math.ceil(totalRecords / perPage) <= currentPage"
          @click="gotoPage(currentPage + 1)"
        >
          <ChevronRight class="h-5 w-5" />
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { ChevronLeft, ChevronRight } from "lucide-vue-next";

const props = defineProps({
  currentPage: { type: Number, default: 1 },
  perPage: { type: Number, default: 15 },
  totalRecords: { type: Number, default: 0 },
});

const emits = defineEmits(["update:current-page"]);

const getPageNumbers = () => {
  const pages = [];
  const totalPages = Math.ceil(props.totalRecords / props.perPage);

  let start = props.currentPage - 3 > 0 ? props.currentPage - 3 : 1;
  const end = props.currentPage + 3 <= totalPages ? props.currentPage + 3 : totalPages;

  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  return pages;
};

const gotoPage = (pageNumber) => {
  emits("update:current-page", pageNumber);
};
</script>

<style scoped>
/* Optional smooth hover transform for active effect */
button:hover:not(:disabled) {
  transform: translateY(-1px);
  transition: transform 0.2s ease-in-out;
}
</style>
