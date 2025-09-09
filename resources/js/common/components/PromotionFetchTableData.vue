<template>
    <div class="mt-3">
        <Tippy
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="labelClass"
            :content="title"
        >
            {{ inputLabel }}
            <span v-if="required" class="text-danger">*</span>
            <Info
                v-if="title"
                class="ml-2 inline-block text-cyan-400"
                :size="15"
            />
        </Tippy>

        <div class="relative">
            <VueMultiselect
                :model-value="selectedRecords"
                @update:model-value="optionUpdated"
                :options="state.tableData"
                :multiple="multiple"
                :taggable="false"
                :close-on-select="true"
                :placeholder="placeholder"
                :hide-selected="true"
                @mousedown.stop=""
                :label="label"
                :track-by="trackBy"
                :disabled="disabled"
            />

            <ValidationError :validationFieldName="validationFieldName" />
        </div>
    </div>
</template>

<script setup>
import VueMultiselect from 'vue-multiselect';
import ValidationError from '@commonComponents/ValidationError.vue';
import { Info } from 'lucide-vue-next';
import { reactive, onMounted } from 'vue';

const props = defineProps({
    records: Array,
    multiple: Boolean,
    selectedRecords: {
        type: [Array, Object], // This allows selectedRecords to be either an Array or an Object
    },
    inputLabel: String,
    placeholder: String,
    validationFieldName: String,
    tableName: String,
    required: {
        type: Boolean,
        default: false,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: 'name',
    },
    trackBy: {
        type: String,
        default: 'name',
    },
    displayLabel: {
        type: Boolean,
        default: true,
    },
    labelClass: {
        type: String,
        default: 'form-label',
    },
    title: {
        type: String,
        default: null,
    },
});

const state = reactive({
    tableData: [],
});

const emits = defineEmits(['update:selected-records']);

const optionUpdated = (selectedOptions) => {
    emits('update:selected-records', selectedOptions);
};

onMounted(() => {
    axios
        .post(route('promotions.fetchData', { table_name: props.tableName }))
        .then((response) => {
            state.tableData = response.data;
        });
});
</script>
