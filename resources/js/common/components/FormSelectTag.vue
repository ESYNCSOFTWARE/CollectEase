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
            @select="selectTag"
            @remove="deselectTag"
            @tag="createTag"
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
    selectTag: Function,
    deselectTag: Function,
    createTag: Function,
});

const emits = defineEmits(['update:selected-records']);

const optionUpdated = (selectedOptions) => {
    emits('update:selected-records', selectedOptions);
};

const selectTag = (option) => {
    props.selectTag(option);
};

const deselectTag = (option) => {
    props.deselectTag(option);
};

const createTag = (option) => {
    props.createTag(option);
};
</script>
