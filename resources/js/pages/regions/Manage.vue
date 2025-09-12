<template>
  <Head :title="region ? 'Edit Region' : 'Add a new Region'" />

  <form @submit.prevent="saveRegion()">
    <div class="p-5 rounded-2xl">
      <div
        class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r bg-primary px-8 py-6 sm:flex-row sm:p-6 rounded-t-2xl"
      >
        <div class="flex items-center mr-auto">
          <div class="p-3 rounded-xl bg-white backdrop-blur-sm mr-4">
            <MapPinned class="text-primary" />
          </div>
          <h2 class="text-2xl font-bold text-white">
            <span v-if="region">Edit Region</span>
            <span v-else>Add region</span>
          </h2>
        </div>
      </div>

      <div class="intro-y box p-6 bg-white rounded-b-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
          <!-- Name -->
          <div>
            <FormInput
              v-model:input-value="regionForm.name"
              input-name="name"
              input-label="Name"
              validation-field-name="name"
              :required="true"
            />
          </div>

          <!-- Code -->
          <div>
            <FormInput
              v-model:input-value="regionForm.code"
              input-name="code"
              input-label="Code"
              type="text"
              validation-field-name="code"
              :required="true"
            />
          </div>
        </div>
        <div class="mt-6 p-4 bg-gray-50 rounded-xl">
          <FormSwitch
            v-model:input-value="regionForm.status"
            input-value="status"
            label="Status"
          />
        </div>

        <div class="mt-5">
          <SecondaryButton type="button" @click="clearFormData"> Clear </SecondaryButton>

          <Link :href="route('regions.index')">
            <CancelButton />
          </Link>

          <SubmitButton type="submit" :text="region ? 'Update' : 'Submit'" class="w-24" />
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
import FormSwitch from "@commonComponents/FormSwitch.vue";
import { MapPinned } from "lucide-vue-next";

const props = defineProps({
  region: {
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
});

const regionForm = useForm({
  name: null,
  code: null,
  status: false,
});

const saveRegion = () => {
  if (props.region) {
    regionForm.put(route("regions.update", props.region.id));
    return;
  }

  router.post(route("regions.store"), regionForm);
};

onMounted(() => {
  if (props.region) {
    Object.assign(regionForm, props.region);
    console.log("Region prop:", props.region);
  }
});

const clearFormData = () => {
  regionForm.reset();
};
</script>
