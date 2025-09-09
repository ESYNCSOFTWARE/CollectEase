<script setup>
import { Head } from '@inertiajs/vue3';
import JTable from '@commonComponents/JTable.vue';
import PrimaryButton from '@commonComponents/PrimaryButton.vue';
import DeleteIcon from '@svg/DeleteIcon.vue';
import EditIcon from '@svg/EditIcon.vue';
import { router } from '@inertiajs/vue3';
import { confirmDialogBox } from '@commonServices/notifier';

const props = defineProps({
    auth: {
        type: Object,
        default: null,
    },
});

import { reactive } from 'vue';

const state = reactive({
    columns: [
        {
            key: 'action',
            headerClass: "w-32 bg-primary text-white",
            bodyClass: "w-32 bg-gray-200 z-50",
        },
        {
            key: 'name',
            sortable: true,
            isDisplay: true,
        },
        {
            key: 'display_name',
            sortable: true,
            isDisplay: true,
        },
        {
            key: 'description',
            sortable: true,
            isDisplay: true,
        },
    ],
    refreshTableData: Math.random(),
});

const hasPermission = (permission) => {
    return props.auth?.permissions.includes(permission);
};

const deletePermission = (permissionId, name) => {
    const message =
        'Are you sure you want to delete the selected permission `' +
        name +
        '` ?';

    confirmDialogBox(message, () => {
        router.post(
            route('permissions.delete', permissionId),
            {},
            {
                onSuccess: () => router.get(route('permissions.index')),
            },
        );
    });
};
</script>
<template>
    <Head title="Permissions" />
    <main class="pt-5 sm:pt-8 px-4 sm:px-6 pb-24 sm:pb-8">
    <JTable v-model:columns="state.columns" :fetch-url="route('permissions.fetch')"
        :refresh-table-data="state.refreshTableData">
        <template #header>
            <Link v-if="hasPermission('CREATE_PERMISSIONS')" :href="route('permissions.create')">
            <PrimaryButton> Add New Permission </PrimaryButton>
            </Link>
        </template>

        <template #action="data">
            <div class="flex items-center">
                <Link class="mr-3 flex items-center" v-if="hasPermission('UPDATE_PERMISSIONS')"
                    :href="route('permissions.edit', data.item.id)">
                <EditIcon />
                </Link>
                <span class="mr-3 flex cursor-pointer items-center" v-if="hasPermission('DELETE_PERMISSIONS')"
                    @click="deletePermission(data.item.id, data.item.name)">
                    <DeleteIcon />
                </span>
            </div>
        </template>
    </JTable>
    </main>
</template>
