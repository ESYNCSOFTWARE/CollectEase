<template>
  <Head title="Assign permission to group" />
  <form @submit.prevent="savePermission()">
    <div class="p-5 bg-white rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-lg bg-blue-100 mr-4">
            <Fingerprint class="text-primary" />
          </div>
          <h2 class="text-xl font-semibold text-gray-800">
            <span >Assign permission to group</span>
          </h2>
        </div>
      </div>
      <div class="intro-y box p-4 sm:p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <div>
            <FormInput
              v-model:input-value="permissionForm.name"
              input-name="name"
              input-label="Name"
              :readonly="true"
            />
          </div>
        </div>

        <div class="mt-4 sm:mt-5">
          <FormMultiSelect
            v-model:selected-records="permissionForm.permission_ids"
            :records="permissions"
            :multiple="true"
            :label="'display_name'"
            track-by="name"
            placeholder="Please select permissions"
            input-label="Select permissions"
            validation-field-name="permission_ids"
            :required="true"
          />
        </div>

        <div
          class="mt-5"
        >
          <Link :href="route('permission_groups.index')">
            <CancelButton class="w-full sm:w-auto" />
          </Link>
          <SubmitButton type="submit" :text="'Submit'" class="w-full sm:w-24" />
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormInput from "@commonComponents/FormInput.vue";
import CancelButton from "@commonComponents/CancelButton.vue";
import FormMultiSelect from "@commonComponents/FormMultiSelect.vue";
import SubmitButton from "@commonComponents/SubmitButton.vue";
import { Fingerprint } from 'lucide-vue-next';


const props = defineProps({
  permission_groups: {
    type: Object,
    default: null,
  },
  permissions: {
    type: Array,
    default: null,
  },
  permission_ids: {
    type: Array,
    default: null,
  },
});

const permissionForm = useForm({
  permission_ids: null,
});

const savePermission = () => {
  permissionForm.put(
    route("permission_groups.assign.group.permissions", props.permission_groups.id)
  );
};

onMounted(() => {
  if (props.permission_groups) {
    permissionForm.permission_ids = props.permission_ids || [];
    permissionForm.name = props.permission_groups.name || "";
  }
});
</script>
