<template>
  <Head :title="permissions ? 'Edit Permission' : 'Add a new Permission'" />

  <form @submit.prevent="savePermission()">
    <div class="p-6 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4 ">
            <Fingerprint class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="permissions">Edit Permission</span>
            <span v-else>Add Permission</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
          <div>
            <FormInput
              v-model:input-value="permissionForm.display_name"
              input-name="display_name"
              input-label="Permission Display Name"
              validation-field-name="display_name"
              :required="true"
              @update:input-value="updatePermissionName"
            />
          </div>
          <div>
            <FormInput
              v-model:input-value="permissionForm.description"
              input-name="description"
              input-label="Permission Description"
              validation-field-name="description"
            />
          </div>
          <div>
            <FormInput
              v-model:input-value="permissionForm.name"
              input-name="name"
              input-label="Permission name"
              validation-field-name="name"
              :readonly="true"
              :required="true"
            />
          </div>
        </div>

        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('permissions.index')">
            <CancelButton />
          </Link>

          <SubmitButton
            type="submit"
            :text="permissions ? 'Update' : 'Submit'"
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
import { Fingerprint } from 'lucide-vue-next';


const props = defineProps({
  permissions: {
    type: Object,
    default: null,
  },
});

const permissionForm = useForm({
  name: null,
  description: null,
  display_name: null,
});

const savePermission = () => {
  if (props.permissions) {
    permissionForm.put(route("permissions.update", props.permissions.id));
    return;
  }

  router.post(route("permissions.store"), permissionForm);
};

const updatePermissionName = (value) => {
  if (!props.permissions) {
    const trimmedValue = value.trim();
    permissionForm.name = trimmedValue.toUpperCase().replace(/\s+/g, "_");
  }
};

onMounted(() => {
  if (props.permissions) {
    Object.assign(permissionForm, props.permissions);
  }
});

const clearFormData = () => {
  permissionForm.reset();
};
</script>
