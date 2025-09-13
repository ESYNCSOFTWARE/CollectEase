<template>
  <Head title="Role statuses" />

  <JTable
    v-model:columns="state.columns"
    :fetch-url="route('role_statuses.fetch')"
    :refresh-table-data="state.refreshTableData"
  >
    <template #header>
      <Link
        v-if="hasPermission('CREATE_ROLE_STATUSES')"
        :href="route('role_statuses.create')"
      >
        <PrimaryButton> Add Role To Status </PrimaryButton>
      </Link>
          <Link v-if="hasPermission('VIEW_STATUSES')" :href="route('statuses.index')">
        <span
          class="middle none center mr-3 cursor-pointer rounded-lg border border-orange-500 py-3 px-6 font-sans text-xs font-bold uppercase text-orange-500 transition-all hover:opacity-75 focus:ring focus:ring-orange-200 active:opacity-[0.85] disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
          data-ripple-dark="true"
        >
          View Status List
        </span>
      </Link>
    </template>

    

    <template #status="data">
      <div
        :style="{ background: data.item?.status.color }"
        class="center relative inline-block select-none whitespace-nowrap rounded-lg py-2 px-3.5 align-baseline font-sans text-xs font-bold uppercase leading-none text-white"
      >
        <div class="mt-px">{{ data.item?.status.name }}</div>
      </div>
    </template>

    <template #role="data">
      {{ data.item?.role.name }}
    </template>

    <template #can_view="data">
      <TableStatus
        :status="data.item.can_view"
        @update:status="updateCanView(data.item.id)"
      />
    </template>

    <template #can_update="data">
      <TableStatus
        :status="data.item.can_update"
        @update:status="updateCanUpdate(data.item.id)"
      />
    </template>

    <template #action="data">
      <div class="flex items-center">
        <span
          class="mr-3 flex cursor-pointer items-center"
          v-if="hasPermission('DELETE_CLIENTS')"
          @click="deleteRoleStatus(data.item.id, data.item?.status.name, data.item?.role.name,)"
        >
          <DeleteIcon />
        </span>
      </div>
    </template>
  </JTable>
</template>
<script setup>
import { Head } from "@inertiajs/vue3";
import JTable from "@commonComponents/JTable.vue";
import PrimaryButton from "@commonComponents/PrimaryButton.vue";
import DeleteIcon from "@svg/DeleteIcon.vue";
import EditIcon from "@svg/EditIcon.vue";
import { Settings2 } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import { confirmDialogBox, showErrorNotification } from "@commonServices/notifier";
import TableStatus from "@commonComponents/TableStatus.vue";
import { reactive } from "vue";

const props = defineProps({
  auth: {
    type: Object,
    default: null,
  },
});

const state = reactive({
  displayUserPermissionModal: false,
  userId: null,
  columns: [
    {
      key: "action",
      headerClass: "w-32 bg-primary text-white",
      bodyClass: "w-32 bg-gray-200 z-50",
    },
    {
      key: "role",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "status",
      sortable: false,
      isDisplay: true,
    },
    {
      key: "can_view",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "can_update",
      sortable: true,
      isDisplay: true,
    },
  ],
  refreshTableData: Math.random(),
});

const hasPermission = (permission) => {
  return props.auth.permissions.includes(permission);
};

const deleteRoleStatus = (roleStatusId, status, role) => {
  const message = "Are you sure you want to delete the mapped status `" + status + " From " + role+ "` ?";

  confirmDialogBox(message, () => {
    router.post(
      route("role_statuses.delete", roleStatusId),
      {},
      {
        onSuccess: () => router.get(route("role_statuses.index")),
      }
    );
  });
};

const updateCanView = (roleStatusId) => {
  if (!hasPermission("UPDATE_ROLE_STATUSES")) {
    showErrorNotification("you are not authorized for this action");
    return;
  }
  router.post(
    route("role_statuses.canView", roleStatusId),
    {},
    {
      onSuccess: () => router.get(route("role_statuses.index")),
    }
  );
};

const updateCanUpdate = (roleStatusId) => {
  if (!hasPermission("UPDATE_ROLE_STATUSES")) {
    showErrorNotification("you are not authorized for this action");
    return;
  }
  router.post(
    route("role_statuses.canUpdate", roleStatusId),
    {},
    {
      onSuccess: () => router.get(route("role_statuses.index")),
    }
  );
};
</script>
