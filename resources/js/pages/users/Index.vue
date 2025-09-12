<template>

    <Head title="Users" />


    <JTable v-model:columns="state.columns" :fetch-url="route('users.fetch')"
        :refresh-table-data="state.refreshTableData">
        <template #header>
            <Link v-if="hasPermission('CREATE_USERS')" :href="route('users.create')">
            <PrimaryButton> Add New User </PrimaryButton>
            </Link>
        </template>

        <template #profile_image="data">
            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
          {{ getInitials(data.item.name) }}
        </div>
        </template>

        <template #status="data">
            <TableStatus :status="data.item.status" @update:status="updateStatus(data.item.id)" />
        </template>

        <template #action="data">
            <div class="flex items-center">
                <Link class="mr-3 flex items-center" v-if="hasPermission('UPDATE_USERS')"
                    :href="route('users.edit', data.item.id)">
                <EditIcon />
                </Link>

                <span class="mr-3 flex cursor-pointer items-center" v-if="hasPermission('DELETE_USERS')"
                    @click="deleteUser(data.item.id, data.item.name)">
                    <DeleteIcon />
                </span>

                <Link class="mr-3 flex cursor-pointer items-center" v-if="hasPermission('CREATE_USERS')"
                    :href="route('users.create.role.permissions', data.item.id)">
                <Settings2 />
                </Link>
            </div>
        </template>
    </JTable>
</template>
<script setup>
import { Head } from '@inertiajs/vue3';
import JTable from '@commonComponents/JTable.vue';
import PrimaryButton from '@commonComponents/PrimaryButton.vue';
import DeleteIcon from '@svg/DeleteIcon.vue';
import EditIcon from '@svg/EditIcon.vue';
import { Settings2 } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import {
    confirmDialogBox,
    showErrorNotification,
} from '@commonServices/notifier';
import TableStatus from '@commonComponents/TableStatus.vue';
import { reactive } from 'vue';

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
            key: 'action',
          headerClass: "w-32 bg-primary text-white",
          bodyClass: "w-32 bg-gray-200 z-50",
        },
        {
            key: 'status',
            sortable: true,
            isDisplay: true,
        },
        {
            key: 'profile_image',
            sortable: false,
            isDisplay: true,
        },
        {
            key: 'name',
            sortable: true,
            isDisplay: true,
        },
        {
            key: 'type',
            isDisplay: true,
        },
        {
            key: 'email',
            sortable: true,
            isDisplay: true,
        },
    ],
    refreshTableData: Math.random(),
});

const hasPermission = (permission) => {
    return props.auth.permissions.includes(permission);
};

const deleteUser = (userId, name) => {
    const message =
        'Are you sure you want to delete the selected user `' + name + '` ?';

    confirmDialogBox(message, () => {
        router.post(
            route('users.delete', userId),
            {},
            {
                onSuccess: () => router.get(route('users.index')),
            },
        );
    });
};

// ✅ Get initials from all words
const getInitials = (name) => {
  if (!name) return '';
  return name
    .split(' ')           // split by space
    .filter(Boolean)      // remove empty values
    .map(word => word[0]) // take first letter of each word
    .join('')
    .toUpperCase();       // uppercase initials
};


const updateStatus = (userId) => {
    if (!hasPermission('UPDATE_USERS')) {
        showErrorNotification('you are not authorized for this action');
        return;
    }
    router.post(
        route('users.status', userId),
        {},
        {
            onSuccess: () => router.get(route('users.index')),
        },
    );
};
</script>
