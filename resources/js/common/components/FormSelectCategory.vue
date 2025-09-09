<template>
    <label
        class="text-primary-p3 mb-2 block text-base font-medium"
        v-if="inputLabel"
    >
        {{ inputLabel }}<span class="text-accent-a4" v-if="required">*</span>
    </label>

    <div class="relative">
        <VueMultiselect
            :model-value="selectedRecords"
            @update:model-value="optionUpdated"
            :options="records.length ? records : []"
            :multiple="true"
            :taggable="true"
            :close-on-select="true"
            :placeholder="placeholder"
            :hide-selected="true"
            @mousedown.stop=""
            :label="label"
            :track-by="trackBy"
            @select="selectCategory"
            @remove="deselectCategory"
            @category="deselectCategory"
        />

        <ValidationError :validationFieldName="validationFieldName" />
    </div>
</template>

<script setup>
import VueMultiselect from 'vue-multiselect';
import ValidationError from '@commonComponents/ValidationError.vue';

const props = defineProps({
    records: Array,
    selectedRecords: Array,
    inputLabel: String,
    placeholder: String,
    validationFieldName: String,
    required: {
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
    selectCategory: Function,
    deselectCategory: Function,
});

const emits = defineEmits(['update:selected-records']);

const optionUpdated = (selectedOptions) => {
    emits('update:selected-records', selectedOptions);
};

const selectCategory = (option) => {
    props.selectCategory(option);
};

const deselectCategory = (option) => {
    props.deselectCategory(option);
};
</script>
