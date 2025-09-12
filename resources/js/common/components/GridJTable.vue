<template>
      <!-- Header: Filters + Search + Slot -->
      <div class="intro-y backdrop-blur-sm  p-2 sm:p-4 rounded-xl bg-white/30 shadow-sm">
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
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
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
    <div class="intro-y overflow-x">
        <div class="intro-y mt-5 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-5 2xl:grid-cols-6 gap-10">
  <!-- BEGIN: Item List -->
  <div
    v-for="(record, index) in state.records"
    :key="'record-' + record.id"
    class="intro-y"
  >
    <div class="box zoom-in relative p-4">
      <div
        v-for="(column, columnIndex) in filterColumns"
        :key="'body-' + column.key"
        :class="getBodyClass(column, columnIndex)"
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
        />
      </div>
    </div>
  </div>
</div>

    </div>

       <!-- Pagination -->
       <div class="intro-y col-span-12 mt-3 flex flex-col sm:flex-row items-center justify-between gap-4 sm:gap-0">
      <JPagination
        :current-page="state.currentPage"
        :per-page="state.perPage"
        :total-records="state.totalRecords"
        @update:current-page="changeCurrentPage"
        class="rounded-xl bg-gray-100 px-5 py-3 shadow-md ring-1 ring-gray-100/50 backdrop-blur-sm w-full sm:w-auto"
      />
      <div class="text-sm text-gray-600 font-medium">
        Showing <span class="text-primary-600 font-semibold">{{ getFromRecordNumber() }}</span> to
        <span class="text-primary-600 font-semibold">{{ getToRecordNumber() }}</span> of
        <span class="text-primary-600 font-semibold">{{ state.totalRecords }}</span> results
      </div>
    </div>
</template>

<script setup>
import JPagination from '@commonComponents/JPagination.vue';
import {
    areColumnsCustomized,
    getDisplayableColumns,
} from '@commonServices/helper';
import { confirmDialogBox } from '@commonServices/notifier';
import axios from 'axios';
import { onUnmounted } from 'vue';
import { computed, onMounted, reactive, watch } from 'vue';

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
        default: 'desc',
    },
    sortBy: {
        type: String,
        default: null,
    },
    firstDivClass: {
        type: String,
        default: 'py-2 sm:py-5 mt-0 sm:mt-5 intro-y',
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
        default: '',
    },
    rowKeyForBackgroundColor: {
        type: String,
        default: '',
    },
    tokenController: {
        type: AbortController,
        default: null,
    },
    roleId: {
        type: String,
        default: '',
    },
});

const emits = defineEmits([
    'update:columns',
    'get-search-text',
    'get-total-records',
    'update:get-cancel-controller',
]);

const state = reactive({
    isDataFetching: false,
    perPageRecordLimits: [8, 16, 25, 50],
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

    emits('update:get-cancel-controller', state.cancelController);

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
            emits('get-total-records', response.data.total_records);
        })
        .catch((error) => {
            if (error.message === 'canceled') {
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

fetchRecords();

const exportCsvRecord = () => {
    if (state.confirmationExport) {
        confirmExport('csv');
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
        confirmExport('excel');
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
    const message =
        'Are you sure you want to export ' + state.totalRecords + ' records?';
    confirmDialogBox(message, () => {
        state.confirmationExport = false;
        if (type === 'csv') {
            exportCsvRecord();
        }

        if (type === 'excel') {
            exportExcelRecord();
        }
    });
};

const compareAndReassignColumns = () => {
    const columns = JSON.parse(localStorage.getItem(props.localStorageKey));

    if (columns && !areColumnsCustomized(state.customizedColumns, columns)) {
        state.customizedColumns = columns;
        emits('update:columns', getDisplayableColumns(columns));

        return;
    }

    emits('update:columns', props.columns);
};

const getBodyClass = (column, index) => {
    let cssClasses = '';

    if (index === 0) {
        cssClasses = 'sticky top-0 left-0 box-shadow-none w[650px] gap-5';
    }

    if (column.bodyClass) {
        cssClasses = cssClasses + ' ' + column.bodyClass;
    }

    return cssClasses;
};

watch(
    () => props.refreshTableData,
    () => {
        state.currentPage = 1;
        fetchRecords();
    },
);

onMounted(() => {
    state.customizedColumns = props.columns;
    compareAndReassignColumns();
});

onUnmounted(() => {
    state.cancelController.abort();
});
</script>
