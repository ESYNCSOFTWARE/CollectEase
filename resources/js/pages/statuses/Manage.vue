<template>
  <Head :title="status ? 'Edit Status' : 'Add a new Status'" />

  <form @submit.prevent="saveStatus()">
    <div class="p-5 bg-white rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-lg bg-blue-100 mr-4">
            <Blocks class="text-primary" />
          </div>
          <h2 class="text-xl font-semibold text-gray-800">
            <span v-if="status">Edit Status</span>
            <span v-else>Add Status</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-5">
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

          <div>
            <label
              for="type"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Type <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <select
                id="type"
                v-model="statusForm.type"
                name="type"
                required
                class="block w-full appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
              >
                <!-- Placeholder -->
                <option value="" disabled>Select Status Type</option>
                <option value="case">Case</option>
                <option value="assignment">Assignment</option>
              </select>

              <!-- Custom down arrow icon -->
              <div
                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"
              >
                <svg
                  class="h-4 w-4 text-gray-400"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 12a.75.75 0 01-.53-.22l-3-3a.75.75 0 011.06-1.06L10 10.44l2.47-2.47a.75.75 0 111.06 1.06l-3 3A.75.75 0 0110 12z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
            </div>
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
            <!-- Color Picker -->
            <input
              type="color"
              v-model="statusForm.color"
              class="h-12 w-12 mt-6 cursor-pointer rounded border border-gray-300"
            />
            <!-- Text Input (hex code) -->
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
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Default <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-4">
              <!-- True -->
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  class="form-radio h-4 w-4 text-indigo-600"
                  v-model="statusForm.is_default"
                  :value="true"
                />
                <span class="ml-2 text-sm text-gray-700">True</span>
              </label>

              <!-- False -->
              <label class="inline-flex items-center">
                <input
                  type="radio"
                  class="form-radio h-4 w-4 text-indigo-600"
                  v-model="statusForm.is_default"
                  :value="false"
                />
                <span class="ml-2 text-sm text-gray-700">False</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData">
            Clear
          </SecondaryButton>

          <Link :href="route('statuses.index')">
            <CancelButton />
          </Link>

          <SubmitButton
            type="submit"
            :text="status ? 'Update' : 'Submit'"
            class="w-24"
          />
        </div>
      </div>
    </div>
  </form>
</template>
<script setup>
import { onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import FormInput from "@commonComponents/FormInput.vue";
import CancelButton from "@commonComponents/CancelButton.vue";
import SubmitButton from "@commonComponents/SubmitButton.vue";
import SecondaryButton from "@commonComponents/SecondaryButton.vue";
import { Blocks } from "lucide-vue-next";
import { ref, watch } from "vue";

const props = defineProps({
  status: { type: Object, default: null }, // ✅ Add this
});

const statusForm = useForm({
  name: null,
  type: "",
  code: null,
  description: null,
  color: null,
  sort_order: null,
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
    console.log("Region prop:", props.region);
  }
});

const clearFormData = () => {
  statusForm.reset();
};
</script>
