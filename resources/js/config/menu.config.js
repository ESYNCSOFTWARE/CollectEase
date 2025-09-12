import {
    Boxes,
    House,
    List,
    UserCog,
    Users,
    Grid,
    EyeOff,
    NotebookTabs,
    HandCoins,
    PhoneCall,
    MapPinned
} from "lucide-vue-next";

const menuItems = [
    {
        name: "Dashboard",
        key: "dashboard",
        icon: House,
        route: "dashboard",
        permission: "VIEW_DASHBOARD",
    },
        {
        name: "Setup",
        key: "setup",
        icon: UserCog,
        route: "setup",
        permission: "VIEW_SETUPS",
        subMenu: [
            {
                name: "Users",
                key: "users",
                route: "users.index",
                permission: "VIEW_USERS",
                icon: Users,
            },
            {
                name: "Regions",
                key: "regions",
                route: "regions.index",
                permission: "VIEW_REGIONS",
                icon: MapPinned,
            },
        ],
    },
    {
        name: "Assignment Management",
        key: "assignment-management",
        icon: NotebookTabs,
        route: "assignment-management",
        permission: "VIEW_ASSIGNMENT_MANAGEMENT",
        subMenu: [
            {
                name: "All Cases",
                key: "cases",
                route: "users.index",
                permission: "VIEW_CASES",
                icon: HandCoins,
            },
        ],
    },
    {
        name: "Permissions",
        key: "permissions",
        icon: EyeOff,
        permission: "VIEW_PERMISSIONS",
        route: "permissions",
        subMenu: [
            {
                name: "Roles",
                key: "roles",
                route: "roles.index",
                permission: "VIEW_ROLES",
                icon: Users,
            },
            {
                name: "User groups",
                key: "users-groups",
                route: "permission_groups.index",
                permission: "VIEW_PERMISSION_GROUPS",
                icon: Boxes,
            },
            {
                name: "Permission List",
                key: "permission-list",
                route: "permissions.index",
                permission: "VIEW_PERMISSIONS",
                icon: List,
            },
        ],
    },
];

export default menuItems;
