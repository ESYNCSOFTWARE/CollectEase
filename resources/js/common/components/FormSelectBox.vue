<template>
  <div class="space-y-2">
    <Tippy
      tag="label"
      :for="validationFieldName ?? inputName"
      :class="['block text-sm font-medium text-gray-800 mb-1.5 cursor-pointer transition-colors duration-300 hover:text-gray-900', labelClass]"
      :content="title"
    >
      <span class="flex items-center">
        {{ inputLabel }}
        <span v-if="required" class="text-red-500 ml-1">*</span>
        <Info
          v-if="title"
          class="ml-2 text-blue-500 hover:text-blue-600 transition-colors duration-300"
          :size="15"
        />
      </span>
    </Tippy>

    <div class="input-group relative">
      <select
        :class="['w-full px-4 py-3.5 border border-gray-300 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 ease-in-out focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white appearance-none', 
                 disabled ? 'bg-gray-100 cursor-not-allowed opacity-80' : 'hover:border-gray-400']"
        :value="selectedRecord"
        @input="updateSelectedRecord"
        :required="required"
        :disabled="disabled"
      >
        <option value="" disabled selected v-if="placeholder">
          {{ placeholder }}
        </option>

        <option
          v-for="record in records"
          :key="record"
          :value="record[valueField]"
        >
          {{ record[label] }}
        </option>
      </select>
      
      <!-- Custom dropdown arrow -->
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
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
  records: Array,
  selectedRecord: [String, Number],
  inputLabel: String,
  validationFieldName: String,
  required: { type: Boolean, default: false },
  label: { type: String, default: 'name' },
  labelClass: { type: String },
  title: { type: String, default: '' },
  valueField: { type: String, default: 'id' },
  placeholder: String,
  disabled: { type: Boolean, default: false },
});

const emits = defineEmits(['update:selected-record']);

const updateSelectedRecord = (event) => {
  emits('update:selected-record', event.target.value);
};
</script>