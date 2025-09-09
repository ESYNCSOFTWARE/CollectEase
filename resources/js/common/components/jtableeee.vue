<template>
  <div
    class="w-full max-w-full px-2 sm:px-4 rounded-2xl min-h-[500px] sm:min-h-[742px] flex flex-col"
  >
    <!-- Header: Filters + Search + Slot -->
    <div
      class="intro-y backdrop-blur-sm mt-2 p-2 sm:p-4 rounded-xl bg-white/30 shadow-sm"
    >
      <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <!-- Per Page Select -->
        <div class="w-full md:w-auto">
          <select
            class="w-full md:w-24 rounded-xl border border-gray-200 bg-white/80 py-2.5 pl-3.5 pr-10 text-sm font-medium text-gray-700 shadow-xs backdrop-blur-sm transition-all duration-200 hover:bg-white focus:outline-none focus:ring-0 focus:border-gray-200"
            :value="state.perPage"
            @input="updatePerPage"
          >
            <option
              v-for="perPageRecordLimit in state.perPageRecordLimits"
              :key="'per-page-record-limit-' + perPageRecordLimit"
              :value="perPageRecordLimit"
              class="text-gray-700"
            >
              {{ perPageRecordLimit }}
            </option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex-1 md:max-w-md w-full">
          <div class="relative">
            <div
              class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5"
            >
              <Search class="h-4.5 w-4.5 text-gray-400" />
            </div>
            <input
              type="text"
              placeholder="Search records..."
              class="w-full rounded-xl border border-gray-200 bg-white/80 py-2.5 pl-12 pr-5 text-sm text-gray-700 shadow-xs backdrop-blur-sm hover:bg-white placeholder:text-gray-400 focus:outline-none focus:ring-0 focus:border-gray-200"
              @input="updateSearchText"
            />
          </div>
        </div>

        <!-- Slot Content -->
        <div class="w-full md:w-auto md:ml-auto">
          <div class="flex flex-wrap items-center justify-end gap-2 sm:gap-4">
            <slot name="header" />
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto mt-5 rounded-xl shadow-md">
      <table class="w-full min-w-[600px] table-auto border border-gray-200 rounded-lg">
        <thead class="bg-gray-50/90 backdrop-blur-sm sticky top-0 z-10">
          <tr>
            <th
              v-for="(column, index) in filterColumns"
              :key="'header-' + column.key"
              :class="
                getHeaderClass(column, index) +
                ' px-6 py-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider group'
              "
            >
              <div
                :class="
                  column.sortable
                    ? 'flex items-center justify-between cursor-pointer hover:text-primary-600 transition-colors duration-200'
                    : ''
                "
                @click="column.sortable ? sortRecords(column) : ''"
              >
                <span class="transition-all duration-200 group-hover:translate-x-0.5">
                  {{ prepareColumnLabel(column) }}
                </span>
                <div
                  v-if="column.sortable"
                  class="ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                >
                  <ChevronUp
                    v-if="state.sortDirection === 'asc' && column.key === state.sortBy"
                    class="h-4 w-4 text-primary-600"
                  />
                  <ChevronDown
                    v-else
                    class="h-4 w-4"
                    :class="{
                      'text-gray-400': column.key !== state.sortBy,
                      'text-primary-600': column.key === state.sortBy,
                    }"
                  />
                </div>
              </div>
            </th>
          </tr>
        </thead>

        <tbody class="divide-y divide-gray-200/60 bg-white">
          <template v-if="state.isDataFetching">
            <tr v-for="n in state.perPage" :key="'loading-table-content-' + n">
              <td :colspan="columns.length" class="px-6 py-5">
                <div class="flex items-center space-x-4 animate-pulse">
                  <div class="h-10 w-10 bg-gray-200/60 rounded-full"></div>
                  <div class="flex-1 space-y-3">
                    <div class="h-4 bg-gray-200/60 rounded-full w-3/4"></div>
                    <div class="h-3 bg-gray-200/60 rounded-full w-1/2"></div>
                  </div>
                </div>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr
              v-for="(record, index) in state.records"
              :key="'record-' + record.id"
              :class="
                getTrClass(record) +
                ' transition-all duration-300 hover:bg-gray-50/80 hover:shadow-sm'
              "
            >
              <td
                v-for="(column, columnIndex) in filterColumns"
                :key="'body-' + column.key"
                :class="
                  getBodyClass(column, columnIndex) +
                  ' px-6 py-5 text-sm text-gray-700 break-words'
                "
              >
                <slot
                  v-if="!column.hidden"
                  :name="`${column.key}`"
                  :item="record"
                  :index="
                    state.currentPage === 1
                      ? index
                      : index + state.perPage * (state.currentPage - 1)
                  "
                >
                  {{ record[column.key] }}
                </slot>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <!-- Empty State -->
      <div
        v-if="!state.isDataFetching && state.records.length === 0"
        class="text-center py-16 px-6"
      >
        <div class="mx-auto h-16 w-16 text-gray-400 mb-4">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-16 w-16 opacity-50"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
            />
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No records found</h3>
        <p class="text-gray-400">
          Try adjusting your search or filter to find what you're looking for.
        </p>
      </div>
    </div>

    <!-- Pagination -->
    <div
      class="intro-y col-span-12 mt-3 flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-0"
    >
      <JPagination
        :current-page="state.currentPage"
        :per-page="state.perPage"
        :total-records="state.totalRecords"
        @update:current-page="changeCurrentPage"
        class="rounded-xl bg-gray-100 px-5 py-3 shadow-md ring-1 ring-gray-100/50 backdrop-blur-sm w-full sm:w-auto"
      />
      <div class="text-sm text-gray-600 font-medium">
        Showing
        <span class="text-primary-600 font-semibold">{{ getFromRecordNumber() }}</span> to
        <span class="text-primary-600 font-semibold">{{ getToRecordNumber() }}</span> of
        <span class="text-primary-600 font-semibold">{{ state.totalRecords }}</span>
        results
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Optional: smooth shadow & hover effect for awesome feel */
table tbody tr:hover {
  transform: translateY(-2px);
  transition: all 0.2s ease-in-out;
}
</style>

<script setup>
import JPagination from "@commonComponents/JPagination.vue";
import {
  areColumnsCustomized,
  capitalize,
  getDisplayableColumns,
} from "@commonServices/helper";
import { confirmDialogBox } from "@commonServices/notifier";
import axios from "axios";
import { debounce } from "lodash";
import { ChevronDown, ChevronUp, Search } from "lucide-vue-next";
import { onUnmounted } from "vue";
import { computed, onMounted, reactive, watch } from "vue";

const props = defineProps({
  isModalTable: {
    type: Boolean,
    default: false,
  },
  columns: {
    type: Array,
    required: true,
  },
  fetchUrl: {
    type: String,
    default: null,
  },
  refreshTableData: {
    type: Number,
    default: null,
  },
  additionalQueryParams: {
    type: Object,
    default: null,
  },
  searchTitle: {
    type: String,
    default: null,
  },
  allowCsvExport: {
    type: Boolean,
    default: false,
  },
  allowExcelExport: {
    type: Boolean,
    default: false,
  },
  allowPdfExport: {
    type: Boolean,
    default: false,
  },
  exportCsvRecordsCallback: {
    type: Function,
    default: null,
  },
  exportExcelRecordsCallback: {
    type: Function,
    default: null,
  },
  exportPdfRecordsCallback: {
    type: Function,
    default: null,
  },
  allowColumnCustomization: {
    type: Boolean,
    default: false,
  },
  localStorageKey: {
    type: String,
    default: null,
  },
  sortDirection: {
    type: String,
    default: "desc",
  },
  sortBy: {
    type: String,
    default: null,
  },
  firstDivClass: {
    type: String,
    default: "py-2 sm:py-5 mt-0 sm:mt-5 intro-y",
  },
  searchValue: {
    type: String,
    default: null,
  },
  confirmationExport: {
    type: Boolean,
    default: false,
  },
  rowKeyBackgroundColor: {
    type: String,
    default: "",
  },
  rowKeyForBackgroundColor: {
    type: String,
    default: "",
  },
  tokenController: {
    type: AbortController,
    default: null,
  },
  roleId: {
    type: String,
    default: "",
  },
});

const emits = defineEmits([
  "update:columns",
  "get-search-text",
  "get-total-records",
  "update:get-cancel-controller",
]);

const state = reactive({
  isDataFetching: false,
  perPageRecordLimits: [8, 25, 50, 100],
  perPage: 8,
  sortDirection: props.sortDirection,
  sortBy: props.sortBy,
  searchText: props.searchValue ?? null,
  totalRecords: null,
  records: [],
  customizedColumns: [],
  currentPage: 1,
  responseData: [],
  cancelToken: null,
  cancelController: props.tokenController ?? new AbortController(),
  isExportFileInProgress: false,
  confirmationExport: props.confirmationExport,
});

const sortRecords = (column) => {
  state.sortBy = column.key;
  state.sortDirection = state.sortDirection === "asc" ? "desc" : "asc";
  state.currentPage = 1;

  fetchRecords();
};

const prepareColumnLabel = (column) => {
  if (column.label) {
    return column.label;
  }

  return capitalize(column.key);
};

const filterColumns = computed(() => {
  if (props.allowColumnCustomization) {
    return state.customizedColumns.filter((column) => {
      return column.isDisplay === true;
    });
  }

  return props.columns;
});

const getFromRecordNumber = () => {
  return state.perPage * state.currentPage - state.perPage + 1;
};

const getToRecordNumber = () => {
  const toRecordNumber = state.perPage * state.currentPage;

  if (toRecordNumber > state.totalRecords) {
    return state.totalRecords;
  }

  return toRecordNumber;
};

const fetchRecords = () => {
  state.isDataFetching = true;
  state.records = [];

  if (state.cancelToken !== null) {
    state.cancelController.abort();
    state.cancelController = new AbortController();
  }

  state.cancelToken = state.cancelController.signal;

  emits("update:get-cancel-controller", state.cancelController);

  axios
    .get(props.fetchUrl, {
      params: getParameters(),
      signal: state.cancelToken,
    })
    .then((response) => {
      state.isDataFetching = false;
      state.records = response.data.data;
      state.totalRecords = response.data.total_records;
      state.responseData = response.data;
      emits("get-total-records", response.data.total_records);
    })
    .catch((error) => {
      if (error.message === "canceled") {
        state.isDataFetching = true;
        return;
      }
      state.isDataFetching = false;
    });
};

const getParameters = () => {
  const defaultQueryParams = {
    per_page: state.perPage,
    page: state.currentPage,
    sort_direction: state.sortDirection,
    sort_by: state.sortBy,
    role_id: props.roleId,
    search_text: state.searchText,
  };

  if (props.additionalQueryParams) {
    return Object.assign(props.additionalQueryParams, defaultQueryParams);
  }

  return defaultQueryParams;
};

const changeCurrentPage = (pageNumber) => {
  state.currentPage = pageNumber;

  fetchRecords();
};

const updatePerPage = (event) => {
  state.perPage = parseInt(event.target.value);
  state.currentPage = 1;

  fetchRecords();
};

const updateSearchText = debounce((event) => {
  state.searchText = event.target.value;
  state.currentPage = 1;
  emits("get-search-text", event.target.value);

  fetchRecords();
}, 1000);

fetchRecords();

const exportCsvRecord = () => {
  if (state.confirmationExport) {
    confirmExport("csv");
    return;
  }

  state.isExportFileInProgress = true;
  props.exportCsvRecordsCallback(getParameters()).then(() => {
    state.isExportFileInProgress = false;
  });

  if (props.confirmationExport) {
    state.confirmationExport = true;
  }
};

const exportExcelRecord = async () => {
  if (state.confirmationExport) {
    confirmExport("excel");
    return;
  }

  state.isExportFileInProgress = true;
  props.exportExcelRecordsCallback(getParameters()).then(() => {
    state.isExportFileInProgress = false;
  });

  if (props.confirmationExport) {
    state.confirmationExport = true;
  }
};

const confirmExport = (type) => {
  const message = "Are you sure you want to export " + state.totalRecords + " records?";
  confirmDialogBox(message, () => {
    state.confirmationExport = false;
    if (type === "csv") {
      exportCsvRecord();
    }

    if (type === "excel") {
      exportExcelRecord();
    }
  });
};

const compareAndReassignColumns = () => {
  const columns = JSON.parse(localStorage.getItem(props.localStorageKey));

  if (columns && !areColumnsCustomized(state.customizedColumns, columns)) {
    state.customizedColumns = columns;
    emits("update:columns", getDisplayableColumns(columns));

    return;
  }

  emits("update:columns", props.columns);
};

const getHeaderClass = (column, index) => {
  let cssClasses = "";

  if (index === 0) {
    cssClasses = "sticky top-0 left-0 bg-slate-100";

    if (props.isModalTable) {
      cssClasses = "sticky top-0 left-0 bg-white";
    }
  }

  if (column.headerClass) {
    cssClasses = cssClasses + " " + column.headerClass + " whitespace-nowrap";
  } else {
    cssClasses = cssClasses + " text-left whitespace-nowrap";
  }

  return cssClasses;
};

const getBodyClass = (column, index) => {
  let cssClasses = "";

  if (index === 0) {
    cssClasses = "sticky top-0 left-0 box-shadow-none";
  }

  if (column.bodyClass) {
    cssClasses = cssClasses + " " + column.bodyClass;
  }

  return cssClasses;
};

const getTrClass = (record) => {
  if (props.rowKeyForBackgroundColor === "match_count" && record.match_count > 0) {
    return "intro-x" + " " + props.rowKeyBackgroundColor;
  }

  return "intro-x bg-white";
};

watch(
  () => props.refreshTableData,
  () => {
    state.currentPage = 1;
    fetchRecords();
  }
);

onMounted(() => {
  state.customizedColumns = props.columns;
  compareAndReassignColumns();
});

onUnmounted(() => {
  state.cancelController.abort();
});
</script>
