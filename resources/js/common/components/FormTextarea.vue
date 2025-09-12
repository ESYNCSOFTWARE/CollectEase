<template>
    <div>
        <Tippy
            :content="title"
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="labelClass"
        >
            {{ inputLabel }}
            <span v-if="required" class="text-red-400">*</span>
            <Info
                v-if="title"
                class="ml-2 inline-block text-cyan-400"
                :size="15"
            />
        </Tippy>

        <textarea
            :id="validationFieldName ?? inputName"
            :value="inputValue"
            :rows="rows"
            class="flex-1 w-full px-4 py-3 border rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200"
            :placeholder="placeholder ? placeholder : 'Enter ' + inputLabel"
            :name="inputName"
            :required="required"
            @input="updateInput"
        />
        <ValidationError
            :validation-field-name="validationFieldName ?? inputName"
        />
    </div>
</template>

<script setup>
import ValidationError from '@commonComponents/ValidationError.vue';
import { Info } from 'lucide-vue-next';

defineProps({
    placeholder: {
        type: String,
        default: null,
    },
    inputName: {
        type: String,
        default: null,
    },
    inputValue: {
        type: [String, Number, Array, Boolean, Object],
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
        type: Boolean,
        default: false,
    },
    rows: {
        type: Number,
        default: 1,
    },
    title: {
        type: String,
        default: null,
    },
    labelClass: {
        type: String,
        default: 'form-label',
    },
});

const emits = defineEmits(['update:input-value']);
const updateInput = (element) => {
    emits('update:input-value', element.target.value);
};
</script>
