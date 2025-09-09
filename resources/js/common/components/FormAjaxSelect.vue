<template>
    <label
        class="text-primary-p3 mb-2 block text-md font-medium"
        v-if="inputLabel"
    >
        {{ inputLabel }}<span class="text-accent-a4" v-if="required">*</span>
    </label>

    <div class="relative">
        <VueMultiselect
            :model-value="selectedRecord"
            @update:model-value="selectedRecordUpdated"
            :options="state.records"
            :label="label"
            :multiple="multiple"
            @search-change="searchRecordsFor"
            :placeholder="placeholder"
            :searchable="true"
            :internal-search="false"
            :track-by="trackBy"
            autocomplete="off"
            :loading="state.isLoading"
        />

        <ValidationError
            v-if="validationFieldName"
            :validationFieldName="validationFieldName"
        />
    </div>
</template>

<script setup>
import VueMultiselect from 'vue-multiselect';
import ValidationError from '@commonComponents/ValidationError.vue';
import { reactive } from 'vue';
import { nextTick } from 'vue';

const props = defineProps({
    selectedRecord: Object,
    inputLabel: String,
    placeholder: String,
    validationFieldName: String,
    searchRecords: Function,
    tableName: String,
    multiple:Boolean,
    trackBy: {
        type: String,
        default: 'id',
    },
    required: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: 'name',
    },
});

const state = reactive({
    records: [],
    isLoading: false,
});

const emits = defineEmits(['update:selected-record']);

const selectedRecordUpdated = (selectedRecord) => {
    emits('update:selected-record', selectedRecord);
    nextTick(() => {
        state.isLoading = false;
    });
};

const searchRecordsFor = async (searchText) => {
    state.isLoading = true;
    try {
        const results = await props.searchRecords(searchText);
        state.records = results || [];
    } catch (error) {
        console.error('Search error:', error);
        state.records = [];
    } finally {
        state.isLoading = false;
    }
};
</script>
