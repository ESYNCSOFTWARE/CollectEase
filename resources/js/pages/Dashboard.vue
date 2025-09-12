<script setup>
import { computed } from "vue";
import SuperAdminDashboard from "@pages/ui_dashboard_partials/SuperAdminDashboard.vue";
import AgentDashboard from "@pages/ui_dashboard_partials/AgentDashboard.vue";
import DefaultDashboard from "@pages/ui_dashboard_partials/DefaultDashboard.vue";

const props = defineProps({
    auth: {
        type: Object,
        default: () => ({
            user: {
                dashboard_component: "DefaultDashboard",
            },
        }),
    },
});

const dashboardComponents = {
    SuperAdminDashboard,
    AgentDashboard,
    DefaultDashboard,
};

const resolvedComponent = computed(() => {
    const name = props.auth?.user?.dashboard_component ?? "DefaultDashboard";
    return dashboardComponents[name] || DefaultDashboard;
});
</script>

<template>
    <Head title="Dashboard" />
    <div class="relative">
        <component :is="resolvedComponent" />
    </div>
</template>
