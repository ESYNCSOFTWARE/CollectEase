<template>
    <div class="mt-3">
        <Tippy
            :content="title"
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="labelClass"
        >
            {{ inputLabel }}
            <span v-if="required" class="text-danger">*</span>
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
            class="dark:disabled:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent focus:ring-primary focus:border-primary group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent w-full rounded-md border-slate-200 text-sm shadow-sm transition duration-200 ease-in-out placeholder:text-slate-400/90 focus:border-opacity-40 focus:ring-4 focus:ring-opacity-20 disabled:cursor-not-allowed disabled:bg-slate-100 group-[.input-group]:z-10 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r"
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
