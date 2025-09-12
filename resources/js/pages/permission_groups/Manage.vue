<template>
  <Head
    :title="permission_groups ? 'Edit Permission Group' : 'Add a new Permission Group'"
  />

  <form @submit.prevent="savePermissionGroups()">
    <div class="p-6 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4">
            <Fingerprint class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="permission_groups">Edit Permission Group</span>
            <span v-else>Add Permission Group</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <!-- Form Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <div>
            <FormInput
              v-model:input-value="permissionGroupsForm.name"
              input-name="name"
              input-label="Permission Group Name"
              validation-field-name="name"
              :required="true"
            />
          </div>
          <div>
            <FormInput
              v-model:input-value="permissionGroupsForm.description"
              input-name="description"
              input-label="Permission Group Description"
              validation-field-name="description"
            />
          </div>
        </div>

        <!-- Action Buttons -->
        <div
          class="mt-5"
        >
          <SecondaryButton type="button" @click="clearFormData" >
            Clear
          </SecondaryButton>

          <Link :href="route('permission_groups.index')" >
            <CancelButton />
          </Link>

          <SubmitButton
            type="submit"
            :text="permission_groups ? 'Update' : 'Submit'"
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
  permission_groups: {
    type: Object,
    default: null,
  },
});

const permissionGroupsForm = useForm({
  name: null,
  description: null,
});

const savePermissionGroups = () => {
  if (props.permission_groups) {
    permissionGroupsForm.put(
      route("permission_groups.update", props.permission_groups.id)
    );
    return;
  }

  router.post(route("permission_groups.store"), permissionGroupsForm);
};

onMounted(() => {
  if (props.permission_groups) {
    Object.assign(permissionGroupsForm, props.permission_groups);
  }
});

const clearFormData = () => {
  permissionGroupsForm.reset();
};
</script>
