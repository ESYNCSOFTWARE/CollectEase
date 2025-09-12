<template>
  <Head :title="role ? 'Edit Role' : 'Add a new Role'" />

  <form @submit.prevent="saveRole()">
    <div class="p-6 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4">
            <Users class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="role">Edit Role</span>
            <span v-else>Add Role</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
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
          <SecondaryButton
            type="button"
            @click="clearFormData"
            class="px-5 py-2.5 rounded-xl border-1 border-gray-200 transition-all duration-300 hover:shadow-md"
          >
            Clear
          </SecondaryButton>

          <Link :href="route('roles.index')">
            <CancelButton
              class="rounded-xl py-3 transition-all duration-300 hover:shadow-md"
            />
          </Link>

          <SubmitButton
            type="submit"
            :text="role ? 'Update' : 'Submit'"
            class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white transition-all duration-300 hover:shadow-lg hover:from-blue-700 hover:to-indigo-800"
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
