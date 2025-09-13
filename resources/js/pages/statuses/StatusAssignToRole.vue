<script setup>
import { onMounted, reactive } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormInput from "@commonComponents/FormInput.vue";
import FormSwitch from "@commonComponents/FormSwitch.vue";
import FormPermission from "@commonComponents/FormPermission.vue";
import JTable from "@commonComponents/JTable.vue";
import { router } from "@inertiajs/vue3";
import { Fingerprint } from "lucide-vue-next";
import {
  confirmDialogBox,
  showErrorNotification,
  showSuccessNotification,
} from "@commonServices/notifier";
import axios from "axios";

const props = defineProps({
  role: { type: Object, default: null },
  permissions: { type: Array, default: null },
  permission_groups: { type: Array, default: null },
  permission_group_ids: { type: Array, default: null },
});

const permissionForm = useForm({
  permission_group_ids: [],
  name: "",
});

const state = reactive({
  role_permissions: [],
  columns: [
    { key: "display_name", sortable: true, isDisplay: true },
    { key: "action", headerClass: "text-center", bodyClass: "text-center" },
  ],
  refreshTableData: Math.random(),
});

const updateRolePemission = (permissionId, event) => {
  console.log(event.target.checked);
  const params = {
    permission_id: permissionId,
    is_permission: event.target.checked,
    role_id: props.role.id,
  };
  const permissionMsg = event.target.checked ? "give" : "remove";
  const permissionSuccessMsg = event.target.checked
    ? "given to"
    : "removed from";

  const message =
    "Are you sure you want to " +
    permissionMsg +
    " permission to the selected role " +
    props.role.name +
    " ?";

  confirmDialogBox(message, () => {
    router.post(
      route("roles.assign.permission.to.role", params),
      {},
      {
        onSuccess: () => {
          showSuccessNotification(
            "Permission " + permissionSuccessMsg + " the role"
          );
          state.refreshTableData = Math.random();
        },
      }
    );
  });
};

const attachGroupPermisisonToRole = (value) => {
  const message =
    "Adding group permission `" +
    value?.name +
    "` to " +
    props.role.name +
    " role?";

  confirmDialogBox(message, () => {
    axios
      .post(route("roles.attach.group.permissions"), {
        permission_group_id: value.id,
        role_id: props.role.id,
      })
      .then((response) => {
        showSuccessNotification(response.data.message);
        state.refreshTableData = Math.random();
      })
      .catch((error) => {
        if (error.response && error.response.data) {
          showErrorNotification(error.response.data.message);
        }
      });
  });
};

const detachGroupPermisisonToRole = (value) => {
  const message =
    "Are you sure you want to remove group permission `" +
    value?.name +
    "` from " +
    props.role?.name +
    " role?";

  confirmDialogBox(message, () => {
    axios
      .post(route("roles.detach.group.permissions"), {
        permission_group_id: value.id,
        role_id: props.role.id,
      })
      .then((response) => {
        showSuccessNotification(response.data.message);
        state.refreshTableData = Math.random();
      })
      .catch((error) => {
        if (error.response && error.response.data) {
          showErrorNotification(error.response.data.message);
        }
      });
  });
};

onMounted(() => {
  if (props.role) {
    permissionForm.name = props.role.name || "";
    permissionForm.permission_group_ids = props.permission_group_ids || [];
  }
});
</script>

<template>
  <Head title="Assign Status to Role" />

  <form @submit.prevent="savePermissionToRole()">
    <main class="pt-5 sm:pt-8 px-4 sm:px-6 pb-24 sm:pb-8">
      <div class="p-5 bg-white rounded-2xl">
        <div
          class="flex flex-col items-center border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 sm:flex-row sm:p-6 rounded-t-2xl"
        >
          <div class="flex items-center mr-auto">
            <div class="p-3 rounded-lg bg-blue-100 mr-4">
              <Fingerprint class="text-primary" />
            </div>
            <h2 class="text-xl font-semibold text-gray-800">
              <span> Assign status to role</span>
            </h2>
          </div>
        </div>

        <!-- Content -->
        <div class="intro-y box p-5">
          <div class="flex flex-col sm:flex-row sm:gap-6">
            <!-- JTable -->
            <div class="w-full sm:w-1/2 overflow-x-auto mb-4 sm:mb-0">
              <JTable
                v-model:columns="state.columns"
                :fetch-url="route('roles.rolePermission')"
                :refresh-table-data="state.refreshTableData"
                :role-id="role.id"
              >
                <template #action="data">
                  <FormSwitch
                    :input-value="data.item?.has_permission"
                    @input="updateRolePemission(data.item.id, $event)"
                  />
                </template>
              </JTable>
            </div>

            <!-- Form Inputs -->
            <div class="w-full sm:w-1/2 flex flex-col gap-4">
              <FormInput
                v-model:input-value="permissionForm.name"
                input-name="name"
                input-label="Name"
                :readonly="true"
              />

              <FormPermission
                v-model:selected-records="permissionForm.permission_group_ids"
                :records="permission_groups"
                :multiple="true"
                :label="'name'"
                track-by="name"
                :select-permission="attachGroupPermisisonToRole"
                :deselect-permission="detachGroupPermisisonToRole"
                placeholder="Please select group permissions"
                input-label="Select group permissions"
                validation-field-name="permission_group_ids"
              />
            </div>
          </div>
        </div>
      </div>
    </main>
  </form>
</template>
