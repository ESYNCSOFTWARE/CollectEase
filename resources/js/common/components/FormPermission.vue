<template>
    <Tippy
        tag="label"
        :for="validationFieldName ?? inputName"
        :class="labelClass"
        :content="title"
    >
        {{ inputLabel }}
        <span v-if="required" class="text-danger">*</span>
        <Info v-if="title" class="ml-2 inline-block text-cyan-400" :size="15" />
    </Tippy>

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
            @mousedown.stop
            :label="label"
            :track-by="trackBy"
            @select="selectPermission"
            @remove="deselectPermission"
            @permission="createPermission"
            :disabled="disabled"
        />

        <ValidationError :validationFieldName="validationFieldName" />
    </div>
</template>

<script setup>
import VueMultiselect from 'vue-multiselect';
import ValidationError from '@commonComponents/ValidationError.vue';
import { Info } from 'lucide-vue-next';

const props = defineProps({
    records: Array,
    selectedRecords: Array,
    inputLabel: String,
    placeholder: String,
    validationFieldName: String,
    multiple: Boolean,
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
    title: {
        type: String,
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    labelClass: {
        type: String,
        default: 'form-label',
    },
    selectPermission: Function,
    deselectPermission: Function,
    createPermission: Function,
});

const emits = defineEmits(['update:selected-records']);

const optionUpdated = (selectedOptions) => {
    emits('update:selected-records', selectedOptions);
};

const selectPermission = (option) => {
    props.selectPermission(option);
};

const deselectPermission = (option) => {
    props.deselectPermission(option);
};

const createPermission = (option) => {
    props.createPermission(option);
};
</script>
