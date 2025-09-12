<template>
  <div class="form-group mt-3">
    <label
      class="relative inline-flex items-center"
      :class="{ 'cursor-not-allowed opacity-70': isReadOnly || disabled }"
    >
      <!-- Hidden checkbox bound to modelValue -->
      <input
        type="checkbox"
        class="peer sr-only"
        v-model="modelValue"
        :disabled="disabled || isReadOnly"
      />

      <!-- Visible toggle -->
      <div
        class="h-8 w-14 rounded-full bg-gray-200 relative 
               after:absolute after:top-1 after:left-1 
               after:h-6 after:w-6 after:rounded-full 
               after:border after:border-gray-300 cursor-pointer
               after:bg-white after:transition-all after:content-[''] 
               peer-checked:bg-green-600 peer-checked:after:translate-x-6 peer-checked:after:border-white"
      ></div>

      <span class="ml-3 text-base">{{ label }}</span>
    </label>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
  inputValue: { type: Boolean, default: false },
  label: String,
  isReadOnly: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
});

const emits = defineEmits(["update:input-value"]);

const modelValue = ref(props.inputValue);

// Sync prop changes
watch(
  () => props.inputValue,
  (val) => {
    modelValue.value = val;
  }
);

// Emit value on change
watch(modelValue, (val) => {
  emits("update:input-value", val);
});
</script>
