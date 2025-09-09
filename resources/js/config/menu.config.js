import {
    Boxes,
    House,
    List,
    Settings,
    UserCog,
    Users,
    EyeOff,
    ClipboardPlus,
    BadgeQuestionMark,
    BookText
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
        permission: "VIEW_SETUP",
        subMenu: [
            {
                name: "Users",
                key: "users",
                route: "users.index",
                permission: "VIEW_USERS",
                icon: Users,
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
