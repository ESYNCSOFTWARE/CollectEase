<template>
  <div>
    <Tippy
      tag="label"
      :for="validationFieldName ?? inputName"
      :class="labelClass"
      :content="title"
    >
      {{ inputLabel }}
      <span v-if="required" class="text-red-500">*</span>
      <Info
        v-if="title"
        class="ml-2 inline-block text-cyan-400"
        :size="15"
      />
    </Tippy>

    <div class="input-group">
      <select
        :class="selectBoxClass"
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
  selectBoxClass: {
    type: String,
    default:
      'flex-1 w-full px-4 py-3 border border-gray-300 focus:ring-2 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-200',
  },
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
