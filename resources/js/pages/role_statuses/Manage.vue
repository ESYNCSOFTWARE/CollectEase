<template>
  <Head :title="roleStatus ? 'Edit Role Status' : 'Add a new Role Status'" />

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
            <span v-if="roleStatus">Edit Role Status</span>
            <span v-else>Add Role Status</span>
          </h2>
        </div>
      </div>

      <!-- Form body -->
      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <div>
            <FormSelectBox
              v-model:selected-record="roleStatusForm.role_id"
              :records="roles"
              input-label="Select role"
              label="name"
              value-field="id"
              placeholder="Select role"
              validation-field-name="role_id"
              :required="true"
            />
          </div>
          <div>
            <FormSelectBox
              v-model:selected-record="roleStatusForm.status_id"
              :records="statuses"
              input-label="Select status"
              label="name"
              value-field="id"
              placeholder="Select status"
              validation-field-name="status_id"
              :required="true"
            />
          </div>
        </div>
        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
          <FormSwitch
            v-model:input-value="roleStatusForm.can_view"
            input-value="can_view"
            label="Can view"
          />
          <FormSwitch
            v-model:input-value="roleStatusForm.can_update"
            input-value="can_update"
            label="Can update"
          />
        </div>

        <!-- Buttons -->
        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData">Clear</SecondaryButton>
          <Link :href="route('role_statuses.index')">
            <CancelButton />
          </Link>
          <SubmitButton type="submit" :text="roleStatus ? 'Update' : 'Submit'" class="w-24" />
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
import { CircleCheck } from "lucide-vue-next";
const props = defineProps({
  roleStatus: {
    type: Object,
    default: null,
  },
  roles: {
    type: Object,
    default: () => [],
  },
  statuses: {
    type: Object,
    default: () => [],
  },
});

const roleStatusForm = useForm({
  role_id: null,
  status_id: null,
  can_view: false,
  can_update: false,
});

const saveStatus = () => {
  if (props.roleStatus) {
    roleStatusForm.put(route("role_statuses.update", props.roleStatus.id));
    return;
  }

  router.post(route("role_statuses.store"), roleStatusForm);
};

onMounted(() => {
  if (props.roleStatus) {
    Object.assign(roleStatusForm, props.roleStatus);
  }
});

const clearFormData = () => {
  roleStatusForm.reset();
};
</script>
