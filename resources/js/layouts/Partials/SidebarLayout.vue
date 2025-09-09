<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import menuItems from '@config/menu.config.js';
import { LogOut } from 'lucide-vue-next';

const page = usePage();
const { permissions } = page.props.auth;
const url = ref(page.url);

const activeMenu = ref(null);
const isHovered = ref(false);

// device width reactive
const isMobile = ref(false);

const checkDevice = () => {
  isMobile.value = window.innerWidth <= 1024; // tablet & below
};

onMounted(() => {
  checkDevice();
  window.addEventListener('resize', checkDevice);
});

const toggleSubMenu = (item, event) => {
  if (item.subMenu) {
    event.preventDefault();
    event.stopPropagation();
    activeMenu.value = activeMenu.value === item.key ? null : item.key;
  }
};

const isActive = (key) => activeMenu.value === key;

const hasPermission = (permission) =>
  permission ? permissions?.includes(permission) : true;

const filteredMenuItems = computed(() =>
  menuItems.filter((item) => hasPermission(item.permission))
);

const getFilteredSubMenuItems = (subMenu) =>
  Array.isArray(subMenu)
    ? subMenu.filter((sub) => hasPermission(sub.permission))
    : [];

const isRouteActive = (routeName) => {
  const currentRoute = route().current();
  return (
    currentRoute === routeName || currentRoute?.startsWith(routeName + '.')
  );
};

const handleLogout = () => {
  router.post(route('logout'));
};

const setActiveMenu = () => {
  filteredMenuItems.value.forEach((item) => {
    if (item.subMenu) {
      const hasActiveChild = item.subMenu.some(
        (subItem) =>
          isRouteActive(subItem.route) || url.value === route(subItem.route)
      );
      if (hasActiveChild) {
        activeMenu.value = item.key;
      }
    } else if (isRouteActive(item.route) || url.value === route(item.route)) {
      activeMenu.value = item.key;
    }
  });
};

onMounted(setActiveMenu);

watch(
  () => usePage().url,
  (newUrl) => {
    url.value = newUrl;
    setActiveMenu();
  }
);
</script>

<template>
  <!-- Desktop Sidebar -->
  <aside
    v-if="!isMobile"
    id="sidebar"
    :class="{ collapsed: !isHovered }"
    @mouseenter="isHovered = true"
    @mouseleave="isHovered = false"
  >
    <div class="logo-container">
      <img src="/images/logo2.png"/>
    </div>

    <ul class="nav-list">
      <li
        v-for="item in filteredMenuItems"
        :key="item.key"
        class="nav-item"
        :class="{
          'has-children': item.subMenu,
          active: isActive(item.key)
        }"
      >
        <template v-if="item.subMenu">
          <a
            href="javascript:;"
            class="nav-link cursor-pointer"
            @click="(event) => toggleSubMenu(item, event)"
          >
            <component v-if="item.icon" :is="item.icon" class="nav-icon" />
            <span class="nav-text">{{ item.name }}</span>
            <i
              class="nav-chevron fas fa-chevron-right"
              :class="{ open: isActive(item.key) }"
            ></i>
          </a>
        </template>

        <template v-else>
          <Link
            :href="route(item.route)"
            class="nav-link"
            :class="{
              active: isRouteActive(item.route) || url === route(item.route)
            }"
          >
            <component v-if="item.icon" :is="item.icon" class="nav-icon" />
            <span class="nav-text">{{ item.name }}</span>
          </Link>
        </template>

        <!-- Child menu -->
        <ul
          v-if="item.subMenu"
          class="child-menu"
          :class="{ open: isActive(item.key) }"
        >
          <li
            v-for="sub in getFilteredSubMenuItems(item.subMenu)"
            :key="sub.key"
            class="child-item"
          >
            <Link
              :href="route(sub.route)"
              class="child-link"
              :class="{
                active:
                  isRouteActive(sub.route) || url === route(sub.route)
              }"
            >
              <component v-if="sub.icon" :is="sub.icon" class="child-icon" />
              <span class="child-text">{{ sub.name }}</span>
            </Link>
          </li>
        </ul>
      </li>
    </ul>

    <div class="sidebar-footer">
      <button class="profile-btn" @click="handleLogout">
        <LogOut />
        <span class="profile-name">Logout</span>
      </button>
    </div>
  </aside>

  <!-- Mobile/Tablet Navigation -->
  <div v-else>
    <!-- Overlay for closing menu when clicking outside -->
    <div 
      v-if="activeMenu" 
      class="mobile-menu-overlay"
      @click="activeMenu = null"
    ></div>
    
    <!-- Submenu panel that slides up from bottom -->
    <div 
      class="mobile-submenu-panel"
      :class="{ open: activeMenu }"
    >
      <div class="mobile-submenu-header">
        <button class="mobile-submenu-close" @click="activeMenu = null">
          <i class="fas fa-times"></i>
        </button>
        <h3>{{ filteredMenuItems.find(item => item.key === activeMenu)?.name }}</h3>
      </div>
      <div class="mobile-submenu-content">
        <template v-for="item in filteredMenuItems" :key="item.key">
          <div v-if="item.key === activeMenu && item.subMenu">
            <Link
              v-for="sub in getFilteredSubMenuItems(item.subMenu)"
              :key="sub.key"
              :href="route(sub.route)"
              class="mobile-submenu-item"
              :class="{
                active: isRouteActive(sub.route) || url === route(sub.route)
              }"
              @click="activeMenu = null"
            >
              <component v-if="sub.icon" :is="sub.icon" class="mobile-submenu-icon" />
              <span>{{ sub.name }}</span>
            </Link>
          </div>
        </template>
      </div>
    </div>

    <!-- Enhanced Bottom navigation bar with horizontal scrolling -->
    <nav class="bottom-nav">
      <ul class="bottom-list">
        <li
          v-for="item in filteredMenuItems"
          :key="item.key"
          class="bottom-item"
          :class="{ active: isActive(item.key) }"
        >
          <template v-if="item.subMenu">
            <button
              class="bottom-link"
              @click="(event) => toggleSubMenu(item, event)"
            >
              <div class="nav-icon-container">
                <component v-if="item.icon" :is="item.icon" class="bottom-icon" />
              </div>
              <span class="bottom-text">{{ item.name }}</span>
            </button>
          </template>

          <template v-else>
            <Link
              :href="route(item.route)"
              class="bottom-link"
              :class="{
                active: isRouteActive(item.route) || url === route(item.route)
              }"
            >
              <div class="nav-icon-container">
                <component v-if="item.icon" :is="item.icon" class="bottom-icon" />
              </div>
              <span class="bottom-text">{{ item.name }}</span>
            </Link>
          </template>
        </li>
      </ul>
    </nav>
  </div>
</template>

<style scoped>
/* Desktop sidebar same as before */
#sidebar {
  width: 220px;
  transition: width 0.3s ease;
  background: #111827;
  color: #fff;
}
#sidebar.collapsed {
  width: 90px;
}
#sidebar.collapsed .nav-text,
#sidebar.collapsed .child-text,
#sidebar.collapsed .profile-name {
  display: none;
}
.nav-link,
.child-link {
  display: flex;
  align-items: center;
  padding: 0.6rem 1rem;
  color: #fff;
  text-decoration: none;
}
.nav-link:hover,
.child-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
}
.nav-icon,
.child-icon {
  min-width: 20px;
  text-align: center;
  font-size: 1.2rem;
  margin-right: 0.75rem;
}
.child-menu {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease;
}
.child-menu.open {
  max-height: 500px;
}
.nav-chevron {
  transition: all 0.3s ease;
  margin-left: auto;
  opacity: 0;
  font-size: 0.8rem;
}
.nav-chevron.open {
  transform: rotate(90deg);
}
#sidebar:not(.collapsed) .nav-chevron,
.nav-item.active .nav-chevron {
  opacity: 1;
}
.child-link.active,
.nav-link.active {
  background-color: rgba(59, 130, 246, 0.1);
  color: rgb(246, 159, 59);
  font-weight: 500;
}
#sidebar:not(.collapsed) .child-link {
  padding-left: 2.5rem;
  justify-content: flex-start;
}
#sidebar.collapsed .child-link {
  justify-content: center;
}
.logo-container {
  padding: 1.5rem 1rem;
  border-bottom: 1px solid #374151;
  /* margin-bottom: 1rem; */
}
.sidebar-footer {
  margin-top: auto;
  padding: 1rem;
  border-top: 1px solid #374151;
}
.profile-btn {
  display: flex;
  align-items: center;
  color: #fff;
  background: none;
  border: none;
  width: 100%;
  padding: 0.5rem;
  cursor: pointer;
}
.profile-btn i {
  margin-right: 0.75rem;
}

/* Enhanced Bottom Nav (mobile/tablet) */
.bottom-nav {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(26, 26, 46, 0.95);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  z-index: 100;
  padding: 0.8rem 0;
  box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.3);
  overflow-x: auto;
  overflow-y: hidden;
  white-space: nowrap;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none; /* Firefox */
}

.bottom-nav::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Edge */
}

.bottom-list {
  display: inline-flex;
  list-style: none;
  padding: 0 0.5rem;
  align-items: center;
  margin: 0;
}

.bottom-item {
  display: inline-flex;
  flex-direction: column;
  align-items: center;
  padding: 0 0.8rem;
  transition: all 0.3s ease;
  min-width: 70px;
  position: relative;
}

.bottom-item.active .nav-icon-container {
  background: rgba(52, 152, 219, 0.2);
  transform: translateY(-5px);
}

.bottom-link {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-decoration: none;
  color: #b6b6b6;
  position: relative;
  background: none;
  border: none;
  cursor: pointer;
}

.bottom-item.active .bottom-link {
  color: #fff;
}

.nav-icon-container {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.4rem;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.05);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

.bottom-item.active .nav-icon-container {
  background: linear-gradient(135deg, #3498db, #2c3e50);
  box-shadow: 0 6px 15px rgba(52, 152, 219, 0.4);
}

.bottom-icon {
  font-size: 1.3rem;
  color: #b6b6b6;
  transition: all 0.3s ease;
}

.bottom-item.active .bottom-icon {
  color: #fff;
  transform: scale(1.1);
}

.bottom-text {
  font-size: 0.7rem;
  opacity: 1;
  margin: 0;
  font-weight: 500;
  transition: all 0.3s ease;
}

.bottom-item.active .bottom-text {
  color: #3498db;
  font-weight: 600;
}

/* Enhanced Mobile submenu panel (iOS style) */
.mobile-menu-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 101;
}

.mobile-submenu-panel {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: #fff;
  border-top-left-radius: 16px;
  border-top-right-radius: 16px;
  z-index: 102;
  transform: translateY(100%);
  transition: transform 0.3s ease-out;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
}

.mobile-submenu-panel.open {
  transform: translateY(0);
}

.mobile-submenu-header {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  position: relative;
}

.mobile-submenu-header h3 {
  margin: 0 auto;
  font-size: 1.1rem;
  font-weight: 600;
}

.mobile-submenu-close {
  position: absolute;
  left: 1rem;
  background: none;
  border: none;
  font-size: 1.2rem;
  color: #6b7280;
  cursor: pointer;
}

.mobile-submenu-content {
  padding: 0.5rem 0;
  overflow-y: auto;
}

.mobile-submenu-item {
  display: flex;
  align-items: center;
  padding: 1rem 1.5rem;
  color: #374151;
  text-decoration: none;
  font-size: 1rem;
  transition: all 0.2s ease;
}

.mobile-submenu-item.active {
  color: rgb(59, 130, 246);
  background-color: rgba(59, 130, 246, 0.1);
}

.mobile-submenu-item:active {
  background-color: #f3f4f6;
}

.mobile-submenu-icon {
  margin-right: 0.75rem;
  font-size: 1.2rem;
  width: 24px;
  text-align: center;
}
</style>