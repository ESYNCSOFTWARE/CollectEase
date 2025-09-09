<template>
    <div class="mt-3">
        <Tippy
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="labelClass"
            :content="title"
        >
            <span class="flex">
                <component
                    :is="components[icon]"
                    v-if="icon"
                    size="20"
                    class="icon-class mt-1 text-gray-500"
                ></component>
                <p v-if="icon" class="ml-1 mt-1">{{ inputLabel }}</p>
                <p v-else class="ml-0 mt-1">{{ inputLabel }}</p>
                <span v-if="required" class="text-danger mt-1 text-lg"
                    >*</span
                ></span
            >
            <Info
                v-if="title"
                class="ml-2 inline-block text-cyan-400"
                :size="15"
            />
        </Tippy>

        <div class="input-group">
            <div
                v-if="inputGroupPrefix"
                :id="validationFieldName ?? inputName"
                class="input-group-text"
            >
                {{ inputGroupPrefix }}
            </div>

            <input
                :id="validationFieldName ?? inputName"
                class="dark:disabled:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent focus:ring-primary focus:border-primary group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent w-full rounded-md border-slate-200 text-sm shadow-sm transition duration-200 ease-in-out placeholder:text-slate-400/90 focus:border-opacity-40 focus:ring-4 focus:ring-opacity-20 disabled:cursor-not-allowed disabled:bg-slate-100 group-[.input-group]:z-10 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r"
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
                class="input-group-text"
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
import { Twitter, Facebook, Linkedin, Instagram } from 'lucide-vue-next';

const components = {
    Twitter,
    Facebook,
    Instagram,
    Linkedin,
};

defineProps({
    type: {
        type: String,
        default: 'text',
    },
    icon: {
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
