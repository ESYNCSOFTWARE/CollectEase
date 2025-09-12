<template>
  <Head :title="client ? 'Edit Client' : 'Add a new Client'" />

  <form @submit.prevent="saveClient()">
    <div class="p-5 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4">
            <Landmark class="text-orange-400" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="client">Edit Client</span>
            <span v-else>Add Client</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- Name -->
          <div>
            <FormInput
              v-model:input-value="clientForm.name"
              input-name="name"
              input-label="Name"
              validation-field-name="name"
              :required="true"
            />
          </div>

          <div>
            <FormInput
              v-model:input-value="clientForm.code"
              input-name="code"
              input-label="Code"
              validation-field-name="code"
              :required="true"
            />
          </div>

          <!-- Email -->
          <div>
            <FormInput
              v-model:input-value="clientForm.email"
              input-name="email"
              input-label="Email"
              type="email"
              validation-field-name="email"
            />
          </div>

          <div>
            <FormInput
              v-model:input-value="clientForm.phone"
              input-name="phone"
              input-label="Phone"
              validation-field-name="phone"
            />
          </div>
          <div>
            <FormInput
              v-model:input-value="clientForm.contact_person"
              input-name="contact_person"
              input-label="Contact person"
              validation-field-name="contact_person"
            />
          </div>
          <div>
            <FormSelectBox
              v-model:selected-record="clientForm.type"
              :records="client_types"
              input-label="Select type"
              label="name"
              value-field="id"
              placeholder="Select type"
              validation-field-name="type"
              :required="true"
            />
          </div>
          <div>
            <FormTextarea
              v-model:input-value="clientForm.address"
              input-name="address"
              :rows="3"
              input-label="Address person"
              validation-field-name="address"
            />
          </div>
        </div>
           <div class="mt-6 p-4 bg-gray-50 rounded-xl">
          <FormSwitch
            v-model:input-value="clientForm.status"
            input-value="status"
            label="Status"
          />
        </div>

        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('clients.index')">
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
import FormTextarea from "@commonComponents/FormTextarea.vue";
import FormSwitch from "@commonComponents/FormSwitch.vue";
import { Landmark } from "lucide-vue-next";

const props = defineProps({
  client: {
    type: Object,
    default: null,
  },

  client_types: {
    type: Object,
    default: null,
  },
});

const clientForm = useForm({
  name: null,
  email: null,
  type: null,
  code: null,
  contact_person: null,
  phone: null,
  address: null,
  status:false
});

const saveClient = () => {
  if (props.client) {
    clientForm.put(route("clients.update", props.client.id));
    return;
  }

  router.post(route("clients.store"), clientForm);
};

onMounted(() => {
  if (props.client) {
    Object.assign(clientForm, props.client);
  }
});

const clearFormData = () => {
  clientForm.reset();
};
</script>
