<template>
  <Head title="Manage User Role Permission" />

  <form @submit.prevent="saveRole()">
    <div class="p-5 bg-white rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-lg bg-blue-100 mr-4">
            <ShieldUser class="text-primary" />
          </div>
          <h2 class="text-xl font-semibold text-gray-800">
            <span>Manage User Role Permission</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- Name (readonly) -->
          <div>
            <FormInput
              v-model:input-value="userForm.name"
              input-name="name"
              input-label="Name"
              :readonly="true"
            />
          </div>

          <!-- Roles MultiSelect -->
          <div>
            <FormMultiSelect
              v-model:selected-records="userForm.role_ids"
              :records="roles"
              :multiple="true"
              :label="'name'"
              track-by="name"
              placeholder="Please select roles"
              input-label="Select roles"
              validation-field-name="role_ids"
              :required="true"
            />
          </div>
        </div>

        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('users.index')">
            <CancelButton />
          </Link>

          <SubmitButton type="submit" :text="'Submit'" class="w-24" />
        </div>
      </div>
    </div>
  </form>
</template>
<script setup>
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormInput from "@commonComponents/FormInput.vue";
import FormMultiSelect from "@commonComponents/FormMultiSelect.vue";
import CancelButton from "@commonComponents/CancelButton.vue";
import SubmitButton from "@commonComponents/SubmitButton.vue";
import SecondaryButton from "@commonComponents/SecondaryButton.vue";
import { ShieldUser } from "lucide-vue-next";

const props = defineProps({
  user: {
    type: Object,
    default: () => null,
  },
  permission_groups: {
    type: Array,
    default: () => null,
  },
  roles: {
    type: Array,
    default: () => null,
  },
  role_ids: {
    type: Array,
    default: () => null,
  },
});

const userForm = useForm({
  role_ids: null,
  permission_group_ids: null,
});

const saveRole = () => {
  userForm.put(route("users.assign.role.permissions", props.user.id));
};

onMounted(() => {
  if (props.user) {
    userForm.role_ids = props.role_ids || [];
    userForm.name = props.user.name || "";
  }
});

const clearFormData = () => {
  userForm.reset();
};
</script>
