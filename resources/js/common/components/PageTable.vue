<template>
    <div class="intro-y overflow-x">
        <div
            class="pb-10md:mt-1 relative min-h-screen min-w-0 max-w-full flex-1 rounded-[30px] bg-slate-100 px-4 before:block before:h-px before:w-full before:content-[''] md:max-w-none md:rounded-none md:px-[22px] md:pt-20"
        >
            <div class="intro-y grid grid-cols-12 gap-5">
                <!-- BEGIN: Item List -->
                <div class="intro-y col-span-12 lg:col-span-10">
                    <div class="grid grid-cols-12 gap-5 pt-5">
                        <div
                            class="intro-y col-span-12 block sm:col-span-4 2xl:col-span-3"
                            href="#"
                            v-for="(record, index) in state.records"
                            :key="'record-' + record.id"
                        >
                            <div class="box zoom-in relative rounded-md p-3">
                                <div
                                    v-for="(
                                        column, columnIndex
                                    ) in filterColumns"
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
                                                : index +
                                                  state.perPage *
                                                      (state.currentPage - 1)
                                        "
                                    >
                                    </slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Ticket -->
                <div class="col-span-12 lg:col-span-2">
                    <div class="tab-content">
                        <div class="intro-y box mt-6 p-5">
                            <div class="mt-1">
                                <Link
                                    class="mt-2 flex items-center rounded-md px-3 py-2 hover:bg-gray-100"
                                    :href="route('page_sections.create')"
                                >
                                    <LayoutTemplate />
                                    <span class="ml-3">Create section</span>
                                </Link>
                            </div>
                            <div
                                class="dark:border-darkmode-400 mt-4 border-t border-slate-200 pt-4"
                            >
                                <a
                                    class="flex items-center rounded-md px-3 py-2"
                                    href=""
                                >
                                    <div
                                        class="bg-pending mr-3 h-2 w-2 rounded-full"
                                    ></div>
                                    Custom Work
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="intro-y col-span-12 mt-5 block flex-wrap items-center sm:flex sm:flex-row sm:flex-nowrap"
            >
                <JPagination
                    :current-page="state.currentPage"
                    :per-page="state.perPage"
                    :total-records="state.totalRecords"
                    @update:current-page="changeCurrentPage"
                />

                <div class="ml-auto mt-2 block text-slate-500 sm:mt-0">
                    Showing {{ getFromRecordNumber() }} to
                    {{ getToRecordNumber() }} of
                    {{ state.totalRecords }} entries
                </div>
            </div>
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
import { LayoutTemplate } from 'lucide-vue-next';
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
        cssClasses = 'sticky top-0 left-0 box-shadow-none';
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
