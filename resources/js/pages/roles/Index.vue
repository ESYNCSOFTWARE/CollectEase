<script setup>
import { Head } from '@inertiajs/vue3';
import JTable from '@commonComponents/JTable.vue';
import PrimaryButton from '@commonComponents/PrimaryButton.vue';
import DeleteIcon from '@svg/DeleteIcon.vue';
import EditIcon from '@svg/EditIcon.vue';
import { Settings2 } from 'lucide-vue-next';
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
    ],
    refreshTableData: Math.random(),
});

const hasPermission = (permission) => {
    return props.auth?.permissions.includes(permission);
};

const deleteRole = (roleId, name) => {
    const message =
        'Are you sure you want to delete the selected role `' + name + '` ?';

    confirmDialogBox(message, () => {
        router.post(
            route('roles.delete', roleId),
            {},
            {
                onSuccess: () => router.get(route('roles.index')),
            },
        );
    });
};
</script>
<template>

    <Head title="Roles" />


    <JTable v-model:columns="state.columns" :fetch-url="route('roles.fetch')"
        :refresh-table-data="state.refreshTableData">

        <template #header>
            <Link v-if="hasPermission('CREATE_ROLES')" :href="route('roles.create')">
            <PrimaryButton> Add New Role </PrimaryButton>
            </Link>
        </template>

        <template #action="data">
            <div class="flex items-center">
                <Link class="mr-3 flex items-center" v-if="hasPermission('UPDATE_ROLES')"
                    :href="route('roles.edit', data.item.id)">
                <EditIcon />
                </Link>
                <span class="mr-3 flex cursor-pointer items-center" v-if="hasPermission('DELETE_ROLES')"
                    @click="deleteRole(data.item.id, data.item.name)">
                    <DeleteIcon />
                </span>
                <Link class="mr-3 flex items-center" v-if="hasPermission('UPDATE_ROLES')" :href="route('roles.create.permission.to.role', data.item.id)
                    ">
                <Settings2 />
                </Link>
            </div>
        </template>
    </JTable>

   
</template>
