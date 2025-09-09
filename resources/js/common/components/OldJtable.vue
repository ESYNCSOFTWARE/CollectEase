<template>
    <div class="intro-y mt-0 py-2 sm:mt-5 sm:py-5">
        <div
            class="block flex-col md:flex md:flex-row md:items-center xl:items-center"
        >
            <select
                class="disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 focus:ring-primary focus:border-primary !box mt-3 w-20 rounded-md border-slate-200 px-3 py-2 pr-8 text-sm shadow-sm transition duration-200 ease-in-out focus:border-opacity-40 focus:ring-4 focus:ring-opacity-20 disabled:cursor-not-allowed disabled:bg-slate-100 group-[.form-inline]:flex-1 sm:mr-auto sm:mt-0"
                :value="state.perPage"
                @input="updatePerPage"
            >
                <option
                    v-for="perPageRecordLimit in state.perPageRecordLimits"
                    :key="'per-page-record-limit-' + perPageRecordLimit"
                    :value="perPageRecordLimit"
                >
                    {{ perPageRecordLimit }}
                </option>
            </select>

            <div class="w-full md:w-auto">
                <div class="relative w-56 text-slate-500">
                    <input
                        type="text"
                        placeholder="Search..."
                        autocomplete="off"
                        @input="updateSearchText"
                        class="dark:disabled:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent focus:ring-primary focus:border-primary group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent !box w-56 rounded-md border-slate-200 pr-10 text-sm shadow-sm transition duration-200 ease-in-out placeholder:text-slate-400/90 focus:border-opacity-40 focus:ring-4 focus:ring-opacity-20 disabled:cursor-not-allowed disabled:bg-slate-100 group-[.input-group]:z-10 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r"
                    />
                </div>
            </div>
        </div>
    </div>

    <div
        class="intro-y overflow-x col-span-12 mb-2 overflow-x-auto sm:mb-2 sm:overflow-x-auto md:mb-2 md:overflow-x-auto lg:mb-2 lg:overflow-x-auto"
    >
        <table class="-mt-2 w-full border-separate border-spacing-y-[10px]">
            <thead>
                <tr class="text-black-60 text-sm leading-normal">
                    <th
                        class="dark:border-darkmode-300 whitespace-nowrap border-b-0 px-5 py-3 font-medium"
                        v-for="(column, index) in filterColumns"
                        :key="'header-' + column.key"
                        :class="getHeaderClass(column, index)"
                    >
                        <div
                            v-if="!column.hidden"
                            :class="column.sortable ? 'cursor-pointer' : ''"
                            @click="column.sortable ? sortRecords(column) : ''"
                        >
                            <div
                                :class="
                                    column.sortable
                                        ? 'mr-auto inline-block text-left'
                                        : ''
                                "
                            >
                                {{ prepareColumnLabel(column) }}
                            </div>

                            <div
                                v-if="column.sortable"
                                class="ml-auto inline-block text-right"
                                :class="
                                    column.key === state.sortBy
                                        ? 'text-gray-900'
                                        : 'text-gray-400'
                                "
                            >
                                <ChevronUp
                                    v-if="
                                        state.sortDirection === 'asc' &&
                                        column.key === state.sortBy
                                    "
                                    class="h-4 w-4"
                                />

                                <ChevronDown v-else class="h-4 w-4" />
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody v-if="state.isDataFetching">
                <tr
                    v-for="n in state.perPage"
                    :key="'loading-table-content-' + n"
                >
                    <td :colspan="columns.length" class="cp">
                        <div class="animated-background" />
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr
                    v-for="(record, index) in state.records"
                    :key="'record-' + record.id"
                    :class="getTrClass(record)"
                    class="dark:border-darkmode-300 box w-40 rounded-l-none rounded-r-none border-x-0 border-b px-5 py-3 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r"
                >
                    <td
                        v-for="(column, columnIndex) in filterColumns"
                        :key="'body-' + column.key"
                        :class="getBodyClass(column, columnIndex)"
                        class="dark:border-darkmode-300 box rounded-l-none rounded-r-none border-x-0 border-b px-5 py-3 shadow-[5px_3px_5px_#00000005] first:rounded-l-[0.6rem] first:border-l last:rounded-r-[0.6rem] last:border-r"
                    >
                        <slot
                            v-if="!column.hidden"
                            :name="`${column.key}`"
                            :item="record"
                            :index="
                                state.currentPage === 1
                                    ? index
                                    : index +
                                      state.perPage * (state.currentPage - 1)
                            "
                        >
                            {{ record[column.key] }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div
        class="intro-y col-span-12 block flex-wrap items-center sm:flex sm:flex-row sm:flex-nowrap"
    >
        <JPagination
            :current-page="state.currentPage"
            :per-page="state.perPage"
            :total-records="state.totalRecords"
            @update:current-page="changeCurrentPage"
        />

        <div class="ml-auto mt-2 block text-slate-500 sm:mt-0">
            Showing {{ getFromRecordNumber() }} to {{ getToRecordNumber() }} of
            {{ state.totalRecords }} entries
        </div>
    </div>
</template>

<script setup>
import JPagination from '@commonComponents/JPagination.vue';
import {
    areColumnsCustomized,
    capitalize,
    getDisplayableColumns,
} from '@commonServices/helper';
import { confirmDialogBox } from '@commonServices/notifier';
import axios from 'axios';
import { debounce } from 'lodash';
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
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
    perPageRecordLimits: [10, 25, 50, 100],
    perPage: 10,
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
    state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
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

const updatePerPage = (event) => {
    state.perPage = parseInt(event.target.value);
    state.currentPage = 1;

    fetchRecords();
};

const updateSearchText = debounce((event) => {
    state.searchText = event.target.value;
    state.currentPage = 1;
    emits('get-search-text', event.target.value);

    fetchRecords();
}, 1000);

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

const getHeaderClass = (column, index) => {
    let cssClasses = '';

    if (index === 0) {
        cssClasses = 'sticky top-0 left-0 bg-slate-100';

        if (props.isModalTable) {
            cssClasses = 'sticky top-0 left-0 bg-white';
        }
    }

    if (column.headerClass) {
        cssClasses =
            cssClasses + ' ' + column.headerClass + ' whitespace-nowrap';
    } else {
        cssClasses = cssClasses + ' text-left whitespace-nowrap';
    }

    return cssClasses;
};

const getBodyClass = (column, index) => {
    let cssClasses = '';

    if (index === 0) {
        cssClasses = 'sticky top-0 left-0 box-shadow-none';
    }

    if (column.bodyClass) {
        cssClasses = cssClasses + ' ' + column.bodyClass;
    }

    return cssClasses;
};

const getTrClass = (record) => {
    if (
        props.rowKeyForBackgroundColor === 'match_count' &&
        record.match_count > 0
    ) {
        return 'intro-x' + ' ' + props.rowKeyBackgroundColor;
    }

    return 'intro-x bg-white';
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
