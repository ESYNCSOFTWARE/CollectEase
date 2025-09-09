<template>
    <div class="space-y-2">
        <Tippy
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="['block text-sm font-medium text-gray-700 mb-1 cursor-pointer transition-colors duration-200', labelClass]"
            :content="title"
        >
            <span class="flex items-center">
                {{ inputLabel }}
                <span v-if="required" class="text-red-500 ml-1">*</span>
                <Info
                    v-if="title"
                    class="ml-2 text-blue-400 hover:text-blue-500 transition-colors"
                    :size="16"
                />
            </span>
        </Tippy>

        <div class="input-group flex rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
            <div
                v-if="inputGroupPrefix"
                :id="validationFieldName ?? inputName"
                class="input-group-text px-4 py-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-600 font-medium"
            >
                {{ inputGroupPrefix }}
            </div>

            <input
                :id="validationFieldName ?? inputName"
                class="flex-1 w-full px-4 py-3 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
                :class="{
                    'rounded-l-lg': !inputGroupPrefix,
                    'rounded-r-lg': !inputGroupSuffix,
                    'bg-gray-100 cursor-not-allowed': readonly
                }"
                :type="type"
                :step="type === 'number' ? 'any' : ''"
                :min="min ? min : ''"
                :max="max ? max : ''"
                :placeholder="placeholder ? placeholder : 'Enter ' + inputLabel"
                :name="inputName"
                :value="inputValue"
                :required="required"
                :readonly="readonly"
                @input="updateInput"
            />

            <div
                v-if="inputGroupSuffix"
                :id="validationFieldName ?? inputName"
                class="input-group-text px-4 py-3 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg text-gray-600 font-medium"
            >
                {{ inputGroupSuffix }}
            </div>
        </div>

        <ValidationError
            :validation-field-name="validationFieldName ?? inputName"
        />
    </div>
</template>

<script setup>
import ValidationError from '@commonComponents/ValidationError.vue';
import { Info } from 'lucide-vue-next';
defineProps({
    type: {
        type: String,
        default: 'text',
    },
    placeholder: {
        type: String,
        default: null,
    },
    inputName: {
        type: String,
        default: null,
    },
    inputValue: {
        type: [String, Number],
        default: null,
    },
    inputLabel: {
        type: String,
        default: null,
    },
    validationFieldName: {
        type: String,
        default: null,
    },
    required: {
        type: [Boolean, Number],
        default: false,
    },
    readonly: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: null,
    },
    inputGroupPrefix: {
        type: String,
        default: null,
    },
    inputGroupSuffix: {
        type: String,
        default: null,
    },
    labelClass: {
        type: String,
        default: 'form-label',
    },
    min: {
        type: String,
        default: null,
    },
    max: {
        type: String,
        default: null,
    },
});
const emits = defineEmits(['update:input-value']);
const updateInput = (element) => {
    emits('update:input-value', element.target.value);
};
</script>