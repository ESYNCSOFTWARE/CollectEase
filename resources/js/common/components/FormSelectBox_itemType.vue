<template>
    <div class="mt-3">
        <Tippy
            tag="label"
            :for="validationFieldName ?? inputName"
            :class="labelClass"
            :content="title"
        >
            {{ inputLabel }}
            <span v-if="required" class="text-danger">*</span>
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
            'disabled:bg-slate-100  w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 group-[.form-inline]:flex-1',
    },
    required: {
        type: Boolean,
        default: false,
    },
    label: {
        type: String,
        default: 'item_type_name',
    },
    labelClass: {
        type: String,
    },
    title: {
        type: String,
        default: '',
    },
    valueField: {
        type: String,
        default: 'id',
    },
    placeholder: String,
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(['update:selected-record']);

const updateSelectedRecord = (event) => {
    emits('update:selected-record', event.target.value);
};
</script>
