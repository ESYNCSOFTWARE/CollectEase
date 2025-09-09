<script setup>
const props = defineProps({
    inputValue: [Number],
    label: String,
    radioValue: [String, Number],
});

const emits = defineEmits(['update:input-value']);

const updateInput = () => {
    const radio = props.radioValue == 0 ? 1 : 0;
    emits('update:input-value', radio);
};
</script>

<template>
    <div class="form-group mt-3">
        <div class="mb-4 flex items-center">
            <input
                type="radio"
                :value="radioValue"
                :checked="inputValue == 1"
                @input="updateInput"
                class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-blue-500"
            />
            <label class="ms-2 text-sm font-medium text-gray-900">{{
                label
            }}</label>
        </div>
    </div>
</template>
