<template>
  <div class="space-y-1">
    <Tippy
      tag="label"
      :for="validationFieldName ?? inputName"
      :class="[
        'block text-sm font-medium text-gray-800 mb-1.5 cursor-pointer transition-colors duration-300 hover:text-gray-900',
        labelClass,
      ]"
      :content="title"
    >
      <span class="flex items-center">
        {{ inputLabel }}
        <span v-if="required" class="text-red-500 ml-1">*</span>
        <Info
          v-if="title"
          class="ml-2 text-blue-500 hover:text-blue-600 transition-colors duration-300"
          :size="16"
        />
      </span>
    </Tippy>

    <div
      class="input-group flex rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 ease-in-out"
    >
      <div
        v-if="inputGroupPrefix"
        :id="validationFieldName ?? inputName"
        class="input-group-text px-4 py-3 bg-gradient-to-r from-gray-50 to-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-700 font-medium transition-colors duration-300"
      >
        {{ inputGroupPrefix }}
      </div>

      <input
        :id="validationFieldName ?? inputName"
        class="flex-1 w-full px-4 py-3 border border-gray-300 outline-none transition-all duration-300 placeholder-gray-400"
        :class="{
          'rounded-l-xl': !inputGroupPrefix,
          'rounded-r-xl': !inputGroupSuffix,
          'bg-gray-100 text-gray-600 cursor-not-allowed select-text': readonly,
          'bg-white text-gray-800 hover:border-gray-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500': !readonly,
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
        class="input-group-text px-4 py-3 bg-gradient-to-r from-gray-100 to-gray-50 border border-l-0 border-gray-300 rounded-r-xl text-gray-700 font-medium transition-colors duration-300"
      >
        {{ inputGroupSuffix }}
      </div>
    </div>

    <ValidationError :validation-field-name="validationFieldName ?? inputName" />
  </div>
</template>

<script setup>
import ValidationError from "@commonComponents/ValidationError.vue";
import { Info } from "lucide-vue-next";
defineProps({
  type: {
    type: String,
    default: "text",
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
    default: "form-label",
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
const emits = defineEmits(["update:input-value"]);
const updateInput = (element) => {
  emits("update:input-value", element.target.value);
};
</script>
