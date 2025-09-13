<template>
  <Head :title="status ? 'Edit Status' : 'Add a new Status'" />

  <form @submit.prevent="saveStatus()">
    <div class="p-5 rounded-2xl">
      <!-- Header -->
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-lg bg-white mr-4">
            <CircleCheck class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="status">Edit Status</span>
            <span v-else>Add Status</span>
          </h2>
        </div>
      </div>

      <!-- Form body -->
      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- Name -->
          <div>
            <FormInput
              v-model:input-value="statusForm.name"
              input-name="name"
              input-label="Name"
              validation-field-name="name"
              :required="true"
            />
          </div>

          <!-- Type -->
          <div>
            <FormSelectBox
              v-model:selected-record="statusForm.type"
              :records="status_types"
              input-label="Select type"
              label="name"
              value-field="id"
              placeholder="Select type"
              validation-field-name="type"
              :required="true"
            />
          </div>

          <!-- Code -->
          <div>
            <FormInput
              v-model:input-value="statusForm.code"
              input-name="code"
              input-label="Code"
              type="text"
              validation-field-name="code"
              :required="true"
            />
          </div>

          <!-- Description -->
          <div>
            <FormInput
              v-model:input-value="statusForm.description"
              input-name="description"
              input-label="Description"
              type="text"
              validation-field-name="description"
              :required="true"
            />
          </div>

          <!-- Color -->
          <div class="flex items-center gap-2">
            <input
              type="color"
              v-model="statusForm.color"
              class="h-12 w-12 mt-6 cursor-pointer rounded border border-gray-300"
            />
            <FormInput
              v-model:input-value="statusForm.color"
              input-name="color"
              input-label="Color"
              type="text"
              validation-field-name="color"
              :required="true"
              class="flex-1"
            />
          </div>

          <!-- Sort Order -->
          <div>
            <FormInput
              v-model:input-value="statusForm.sort_order"
              input-name="sort_order"
              input-label="Sort Order"
              type="text"
              validation-field-name="sort_order"
              :required="true"
            />
          </div>
        </div>
        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
          <FormSwitch
            v-model:input-value="statusForm.is_default"
            input-value="status"
            label="Is default"
          />
        </div>

        <!-- Buttons -->
        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData">Clear</SecondaryButton>
          <Link :href="route('statuses.index')">
            <CancelButton />
          </Link>
          <SubmitButton type="submit" :text="status ? 'Update' : 'Submit'" class="w-24" />
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import FormInput from "@commonComponents/FormInput.vue";
import CancelButton from "@commonComponents/CancelButton.vue";
import SubmitButton from "@commonComponents/SubmitButton.vue";
import SecondaryButton from "@commonComponents/SecondaryButton.vue";
import FormMultiSelect from "@commonComponents/FormMultiSelect.vue";
import FormSelectBox from "@commonComponents/FormSelectBox.vue";
import FormSwitch from "@commonComponents/FormSwitch.vue";
import { CircleCheck } from 'lucide-vue-next';
const props = defineProps({
  status: { type: Object, default: null },
  roles: { type: Array, default: () => [] },
  status_types: {
    type: Object,
    default: () => [],
  },
});

const statusForm = useForm({
  name: null,
  type: null,
  code: null,
  description: null,
  color: null,
  sort_order: 0,
  is_default: false,
});

const saveStatus = () => {
  if (props.status) {
    statusForm.put(route("statuses.update", props.status.id));
    return;
  }

  router.post(route("statuses.store"), statusForm);
};

onMounted(() => {
  if (props.status) {
    Object.assign(statusForm, props.status);
  }
});

const clearFormData = () => {
  statusForm.reset();
};
</script>
