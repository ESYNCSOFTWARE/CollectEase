<template>
  <div class="form-group mt-3">
    <label class="relative inline-flex cursor-pointer items-center">
      <input
        type="checkbox"
        :value="state.modelValue"
        class="peer sr-only"
        :checked="inputValue"
        @input="updateInput"
        :disabled="disabled"
      />
      <!-- Small Toggle -->
      <div
        @click="toggle"
        class="h-4 w-8 rounded-full bg-gray-200 relative after:absolute after:top-0.5 after:left-0.5 after:h-3 after:w-3 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-green-600 peer-checked:after:translate-x-4 peer-checked:after:border-white"
      ></div>
      <span class="ml-2 text-sm">{{ label }}</span>
    </label>
  </div>
</template>

<script setup>
import { reactive } from "vue";

const props = defineProps({
  inputValue: [Boolean, Number],
  label: String,
  valueType: String,
  isReadOnly: { type: Boolean, default: false },
  activeClass: String,
  switchClass: String,
  required: Boolean,
  disabled: { type: Boolean, default: false },
});

const state = reactive({
  modelValue: props.inputValue,
});

const toggle = () => {
  state.modelValue = !state.modelValue;
};

const emits = defineEmits(["update:input-value"]);

const updateInput = () => {
  emits("update:input-value", state.modelValue);
};
</script>
