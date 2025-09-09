import {
    showErrorNotification,
    showSuccessNotification,
} from '@commonServices/notifier';
import { reactive, watch } from 'vue';

export const validation = reactive({
    errors: null,
});

export const evaluateValidationErrors = (props) => {
    watch(
        () => props.value?.errors,
        () => {
            validation.errors = props.value?.errors;
        },
        { immediate: true },
    );
};

export const evaluateFlashMessagesToast = (props) => {
    watch(
        () => props.value?.flash,
        () => {
            if (props.value?.flash && props.value?.flash?.success) {
                showSuccessNotification(props.value.flash.success);
            }

            if (props.value?.flash && props.value?.flash?.error) {
                showErrorNotification(props.value.flash.error);
            }
        },
        { immediate: true },
    );
};
