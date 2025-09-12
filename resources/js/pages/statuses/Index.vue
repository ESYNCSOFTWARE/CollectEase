<template>
  <Head title="Statuses" />

  <JTable
    v-model:columns="state.columns"
    :fetch-url="route('statuses.fetch')"
    :refresh-table-data="state.refreshTableData"
  >
    <template #header="data">
      <Link
        v-if="hasPermission('CREATE_STATUSES')"
        :href="route('statuses.create')"
      >
        <PrimaryButton> Add New Status </PrimaryButton>
      </Link>
    </template>
    <!-- Color Badge -->
    <template #color="data">
      <div
        class="w-12 h-8 rounded-full border border-gray-300"
        :style="{ backgroundColor: data.item.color }"
        :title="data.item.color"
      ></div>
    </template>
    <template #profile_image="data">
      <div
        class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold"
      >
        {{ getInitials(data.item.name) }}
      </div>
    </template>

    <template #is_default="data">
      <TableStatus
        :status="data.item.is_default"
        @update:status="updateStatus(data.item.id)"
      />
    </template>

    <template #action="data">
      <div class="flex items-center">
        <Link
          class="mr-3 flex items-center"
          v-if="hasPermission('UPDATE_STATUSES')"
          :href="route('statuses.edit', data.item.id)"
        >
          <EditIcon />
        </Link>

        <span
          class="mr-3 flex cursor-pointer items-center"
          v-if="hasPermission('DELETE_STATUSES')"
          @click="deleteStatus(data.item.id, data.item.name)"
        >
          <DeleteIcon />
        </span>
        <Link
          class="mr-3 flex items-center"
          v-if="hasPermission('UPDATE_STATUSES')"
          :href="route('statuses.create.status.to.role', data.item.id)"
        >
          <Settings2 />
        </Link>
        <!--Link class="mr-3 flex items-center" v-if="hasPermission('UPDATE_ROLES')" :href="route('roles.create.permission.to.role', data.item.id)
                    ">
                <Settings2 />
                </Link-->
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
import {
  confirmDialogBox,
  showErrorNotification,
} from "@commonServices/notifier";
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
  statusId: null,
  columns: [
    {
      key: "action",
      headerClass: "w-32 bg-primary text-white",
      bodyClass: "w-32 bg-gray-200 z-50",
    },
    {
      key: "name",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "color",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "type",
      sortable: true,
      isDisplay: true,
    },

    {
      key: "code",
      sortable: true,
      isDisplay: true,
    },

    {
      key: "description",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "sort_order",
      sortable: true,
      isDisplay: true,
    },
    {
      key: "is_default",
      sortable: true,
      isDisplay: true,
    },
  ],
  refreshTableData: Math.random(),
});

const hasPermission = (permission) => {
  return props.auth.permissions.includes(permission);
};

const deleteStatus = (statusId, name) => {
  const message =
    "Are you sure you want to delete the selected status `" + name + "` ?";

  confirmDialogBox(message, () => {
    router.post(
      route("statuses.delete", statusId),
      {},
      {
        onSuccess: () => router.get(route("statuses.index")),
      }
    );
  });
};

// ✅ Get initials from all words
const getInitials = (name) => {
  if (!name) return "";
  return name
    .split(" ") // split by space
    .filter(Boolean) // remove empty values
    .map((word) => word[0]) // take first letter of each word
    .join("")
    .toUpperCase(); // uppercase initials
};

const updateStatus = (statusId) => {
  if (!hasPermission("UPDATE_STATUSES")) {
    showErrorNotification("you are not authorized for this action");
    return;
  }
  router.post(
    route("statuses.status", statusId),
    {},
    {
      onSuccess: () => router.get(route("statuses.index")),
    }
  );
};
</script>
