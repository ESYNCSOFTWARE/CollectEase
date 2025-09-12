<template>
  <Head :title="user ? 'Edit User' : 'Add a new User'" />

  <form @submit.prevent="saveUser()">
    <div class="p-5 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4">
            <UserRoundPlus class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="user">Edit User</span>
            <span v-else>Add user</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- Name -->
          <div>
            <FormInput
              v-model:input-value="userForm.name"
              input-name="name"
              input-label="Name"
              validation-field-name="name"
              :required="true"
            />
          </div>

          <!-- Email -->
          <div>
            <FormInput
              v-model:input-value="userForm.email"
              input-name="email"
              input-label="Email"
              type="email"
              validation-field-name="email"
              :required="true"
            />
          </div>

          <!-- Password (only if creating user) -->
          <div v-if="!user">
            <FormInput
              v-model:input-value="userForm.password"
              input-name="password"
              type="password"
              input-label="Password"
              validation-field-name="password"
              :required="true"
            />
          </div>

          <!-- Confirm Password (only if creating user) -->
          <div v-if="!user">
            <FormInput
              v-model:input-value="userForm.password_confirmation"
              input-name="password_confirmation"
              input-label="Confirm Password"
              type="password"
              validation-field-name="password_confirmation"
              :required="true"
            />
          </div>
          <div>
            <FormSelectBox
              v-model:selected-record="userForm.dashboard_component"
              :records="templates"
              input-label="Select template"
              label="name"
              value-field="id"
              placeholder="Select Template"
              validation-field-name="dashboard_component"
              :required="true"
            />
          </div>
        </div>

        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('users.index')">
            <CancelButton />
          </Link>

          <SubmitButton type="submit" :text="user ? 'Update' : 'Submit'" class="w-24" />
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
import FormSelectBox from "@commonComponents/FormSelectBox.vue";
import { UserRoundPlus } from "lucide-vue-next";

const props = defineProps({
  user: {
    type: Object,
    default: null,
  },
  permission_groups: {
    type: Object,
    default: null,
  },
  companies: {
    type: Array,
    default: null,
  },
  templates: {
    type: Array,
    default: null,
  },
});

const userForm = useForm({
  name: null,
  email: null,
  dashboard_component:null,
});

const saveUser = () => {
  if (props.user) {
    userForm.put(route("users.update", props.user.id));
    return;
  }

  router.post(route("users.store"), userForm);
};

onMounted(() => {
  if (props.user) {
    Object.assign(userForm, props.user);
  }
});

const clearFormData = () => {
  userForm.reset();
};
</script>
