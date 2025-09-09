<template>
  <Head :title="role ? 'Edit Role' : 'Add a new Role'" />

  <form @submit.prevent="saveRole()">
    <div class="p-5 bg-white rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-lg bg-blue-100 mr-4">
            <Users class="text-primary" />
          </div>
          <h2 class="text-xl font-semibold text-gray-800">
            <span v-if="role">Edit Role</span>
            <span v-else>Add Role</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-5">
        <div>
          <FormInput
            v-model:input-value="roleForm.name"
            input-name="name"
            input-label="Role Name"
            validation-field-name="name"
            :required="true"
          />
        </div>
        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('roles.index')">
            <CancelButton />
          </Link>

          <SubmitButton type="submit" :text="role ? 'Update' : 'Submit'" class="w-24" />
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
import { Users } from "lucide-vue-next";

const props = defineProps({
  role: {
    type: Object,
    default: null,
  },
});

const roleForm = useForm({
  name: null,
});

const saveRole = () => {
  if (props.role) {
    roleForm.put(route("roles.update", props.role.id));
    return;
  }

  router.post(route("roles.store"), roleForm);
};

onMounted(() => {
  if (props.role) {
    Object.assign(roleForm, props.role);
  }
});

const clearFormData = () => {
  roleForm.reset();
};
</script>
