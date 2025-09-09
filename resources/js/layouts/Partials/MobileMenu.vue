<template>
    <div
        class="mobile-menu group top-0 inset-x-0 fixed bg-theme-1/90 z-[60] border-b border-white/[0.08] md:hidden before:content-[''] before:w-full before:h-screen before:z-10 before:fixed before:inset-x-0 before:bg-black/90 before:transition-opacity before:duration-200 before:ease-in-out before:invisible before:opacity-0 [&.mobile-menu--active]:before:visible [&.mobile-menu--active]:before:opacity-100"
        :class="state.add_class"
    >
        <div class="flex h-[70px] items-center px-3 sm:px-8">
            <a class="mr-auto flex text-white">
                {{ appName }}
            </a>
            <a
                @click="openMenu()"
                :class="state.toggle_icon_hide"
                class="mobile-menu-toggler"
            >
                <span class="text-white"> <AlignJustify /></span>
            </a>
        </div>
        <div
            class="scrollable bg-primary [&amp;[data-simplebar]]:fixed [&amp;_.simplebar-scrollbar]:before:bg-black/50 left-0 top-0 z-20 -ml-[100%] h-screen w-[270px] transition-all duration-300 ease-in-out group-[.mobile-menu--active]:ml-0"
            data-simplebar="init"
            :class="state.menu_show"
        >
            <div class="simplebar-wrapper" style="margin: 0px">
                <div class="simplebar-height-auto-observer-wrapper">
                    <div class="simplebar-height-auto-observer"></div>
                </div>
                <div class="simplebar-mask">
                    <div
                        class="simplebar-offset"
                        style="right: 0px; bottom: 0px"
                    >
                        <div
                            class="simplebar-content-wrapper"
                            tabindex="0"
                            role="region"
                            aria-label="scrollable content"
                            style="height: 100%; overflow: hidden scroll"
                        >
                            <div class="simplebar-content" style="padding: 0px">
                                <span
                                    class="invisible fixed right-0 top-0 mr-4 mt-4 text-white opacity-0 transition-opacity duration-200 ease-in-out group-[.mobile-menu--active]:visible group-[.mobile-menu--active]:opacity-100"
                                    @click="closeMenu()"
                                >
                                    <CircleX />
                                </span>
                                <ul class="py-2">
                                    <li
                                        v-for="item in filteredMenuItems"
                                        :key="item.key"
                                    >
                                        <a
                                            class="menu"
                                            :href="
                                                item.subMenu
                                                    ? 'javascript:;'
                                                    : route(item.route)
                                            "
                                            :class="
                                                item.subMenu &&
                                                isActive(item.key)
                                                    ? 'side-menu--active'
                                                    : ''
                                            "
                                            @click="toggleSubMenu(item, $event)"
                                        >
                                            <div class="menu__icon">
                                                <component :is="item.icon" />
                                            </div>
                                            <div class="menu__title">
                                                {{ item.name }}
                                                <div
                                                    class="menu__sub-icon"
                                                    v-if="item.subMenu"
                                                    :class="{
                                                        'rotate-180': isActive(
                                                            item.key,
                                                        ),
                                                    }"
                                                >
                                                    <ArrowUpIcon />
                                                </div>
                                            </div>
                                        </a>
                                        <ul
                                            class="menu__sub-open"
                                            v-show="isActive(item.key)"
                                        >
                                            <li
                                                v-for="subItem in getFilteredSubMenuItems(
                                                    item.subMenu,
                                                ).value"
                                                :key="subItem.route"
                                            >
                                                <Link
                                                    class="menu"
                                                    :href="route(subItem.route)"
                                                    :class="{
                                                        'bg-gray-300 text-white':
                                                            activeSubMenu ===
                                                            subItem.route,
                                                    }"
                                                    @click.prevent="
                                                        setActiveSubMenu(
                                                            subItem.route,
                                                        )
                                                    "
                                                >
                                                    <div class="menu__icon">
                                                        <component
                                                            v-if="subItem.icon"
                                                            :is="subItem.icon"
                                                        />
                                                    </div>
                                                    <div class="menu__title">
                                                        {{ subItem.name }}
                                                    </div>
                                                </Link>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="simplebar-placeholder"
                    style="width: auto; height: 1172px"
                ></div>
            </div>
            <div
                class="simplebar-track simplebar-horizontal"
                style="visibility: hidden"
            >
                <div
                    class="simplebar-scrollbar"
                    style="width: 0px; display: none"
                ></div>
            </div>
            <div
                class="simplebar-track simplebar-vertical"
                style="visibility: visible"
            >
                <div
                    class="simplebar-scrollbar"
                    style="
                        height: 607px;
                        transform: translate3d(0px, 0px, 0px);
                        display: block;
                    "
                ></div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { AlignJustify, CircleX } from 'lucide-vue-next';
import { ref, onMounted, computed, reactive } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ArrowUpIcon from '@svg/ArrowUpIcon.vue';
import menuItems from '@config/menu.config.js';

const state = reactive({
    menu_show: 'hidden',
    add_class: '',
    toggle_icon_hide: '',
});

const appName = import.meta.env.VITE_APP_NAME;

const { permissions } = usePage().props.auth;

const activeMenu = ref(null);
const activeSubMenu = ref(null);
const { url } = usePage();

const toggleSubMenu = (item, event) => {
    if (item.subMenu) {
        event.preventDefault();
        activeMenu.value = activeMenu.value === item.key ? null : item.key;
    }

    return;
};

const isActive = (menu) => {
    return activeMenu.value === menu || activeSubMenu.value === menu;
};

const setActiveSubMenu = (subMenu) => {
    activeSubMenu.value = subMenu;
};

const hasPermission = (permission) => {
    return permission ? permissions?.includes(permission) : true;
};

const filteredMenuItems = computed(() => {
    return menuItems.filter((item) => hasPermission(item.permission));
});

const getFilteredSubMenuItems = (subMenu) => {
    return computed(() => {
        return Array.isArray(subMenu)
            ? subMenu.filter((subItem) => hasPermission(subItem.permission))
            : [];
    });
};

const openMenu = () => {
    state.menu_show = '';
    state.toggle_icon_hide = 'hidden';
    state.add_class =
        '[&amp;.mobile-menu--active]:before:visible [&amp;.mobile-menu--active]:before:opacity-100';
};

const closeMenu = () => {
    state.menu_show = 'hidden';
    state.toggle_icon_hide = '';
    state.add_class = '';
};

onMounted(() => {
    filteredMenuItems.value.forEach((item) => {
        if (url.includes(item.route)) {
            activeMenu.value = item.key;
        }

        item.subMenu?.forEach((subItem) => {
            if (url.includes(subItem.route)) {
                activeSubMenu.value = item.key;
            }
        });
    });
});
</script>
