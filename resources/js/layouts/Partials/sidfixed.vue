<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import menuItems from '@config/menu.config.js';

const page = usePage();
const { permissions } = page.props.auth;
const url = ref(page.url);

const activeMenu = ref(null);
const isHovered = ref(false);

// Toggle submenu
const toggleSubMenu = (item, event) => {
    if (item.subMenu) {
        event.preventDefault();
        event.stopPropagation();
        activeMenu.value = activeMenu.value === item.key ? null : item.key;
    }
};

// Check if menu is active (parent)
const isActive = (key) => activeMenu.value === key;

// Permission check
const hasPermission = (permission) => permission ? permissions?.includes(permission) : true;

// Filter main menu by permission
const filteredMenuItems = computed(() =>
    menuItems.filter(item => hasPermission(item.permission))
);

// Filter submenus by permission
const getFilteredSubMenuItems = (subMenu) =>
    Array.isArray(subMenu)
        ? subMenu.filter(sub => hasPermission(sub.permission))
        : [];

// Check if a route is active
const isRouteActive = (routeName) => {
  const currentRoute = route().current();
  return currentRoute === routeName || currentRoute?.startsWith(routeName + '.');
};

// Handle logout
const handleLogout = () => {
  router.post(route('logout'));
};

// Function to update active menu
const setActiveMenu = () => {
  filteredMenuItems.value.forEach((item) => {
    if (item.subMenu) {
      const hasActiveChild = item.subMenu.some(subItem => 
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

// Run on page load
onMounted(setActiveMenu);

// Run whenever Inertia URL changes
watch(
  () => usePage().url,
  (newUrl) => {
    url.value = newUrl;
    setActiveMenu();
  }
);
</script>

<template>
<aside 
  id="sidebar" 
  :class="{ collapsed: !isHovered }"
  @mouseenter="isHovered = true" 
  @mouseleave="isHovered = false"
>
  <div class="logo-container">
    <p class="text-white">TestingApp</p>
  </div>

  <ul class="nav-list">
    <li v-for="item in filteredMenuItems" :key="item.key" class="nav-item" :class="{ 
        'has-children': item.subMenu, 
        'active': isActive(item.key) 
    }">
      
      <!-- Parent menu -->
      <template v-if="item.subMenu">
        <a 
          href="javascript:;" 
          class="nav-link cursor-pointer"
          @click="(event) => toggleSubMenu(item, event)"
        >
          <component v-if="item.icon" :is="item.icon" class="nav-icon" />
          <span class="nav-text">{{ item.name }}</span>
          <i class="nav-chevron fas fa-chevron-right" :class="{ 'open': isActive(item.key) }"></i>
        </a>
      </template>

      <template v-else>
        <Link :href="route(item.route)" class="nav-link" :class="{ 
          'active': isRouteActive(item.route) || url === route(item.route) 
        }">
          <component v-if="item.icon" :is="item.icon" class="nav-icon" />
          <span class="nav-text">{{ item.name }}</span>
        </Link>
      </template>

      <!-- Child menu -->
      <ul v-if="item.subMenu" class="child-menu" :class="{ 'open': isActive(item.key) }">
        <li v-for="sub in getFilteredSubMenuItems(item.subMenu)" :key="sub.key" class="child-item">
          <Link 
            :href="route(sub.route)" 
            class="child-link" 
            :class="{ 'active': isRouteActive(sub.route) || url === route(sub.route) }"
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
      <i class="fa-solid fa-right-from-bracket"></i>
      <span class="profile-name">Logout</span>
    </button>
  </div>
</aside>
</template>

<style scoped>
#sidebar {
  width: 220px;
  transition: width 0.3s ease;
  background: #111827;
  color: #fff;
}

#sidebar.collapsed {
  width: 80px;
}

/* Hide text when collapsed */
#sidebar.collapsed .nav-text,
#sidebar.collapsed .child-text,
#sidebar.collapsed .profile-name {
  display: none;
}

/* Nav & child links */
.nav-link, .child-link {
  display: flex;
  align-items: center;
  padding: 0.6rem 1rem;
}

/* Icons */
.nav-icon, .child-icon {
  min-width: 20px;
  text-align: center;
  font-size: 1.2rem;
}

/* Child menu animation */
.child-menu {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.4s ease;
}

.child-menu.open {
  max-height: 500px;
}

/* Chevron */
.nav-chevron {
  transition: all 0.3s ease;
  margin-left: auto;
  opacity: 0;
}

.nav-chevron.open {
  transform: rotate(90deg);
}

#sidebar:not(.collapsed) .nav-chevron,
.nav-item.active .nav-chevron {
  opacity: 1;
}

/* Active link styles */
.child-link.active,
.nav-link.active {
  background-color: rgba(59, 130, 246, 0.1);
  color: rgb(59, 130, 246);
  font-weight: 500;
}

/* Expanded child padding */
#sidebar:not(.collapsed) .child-link {
  padding-left: 2.5rem;
  justify-content: flex-start;
}

/* Collapsed child alignment */
#sidebar.collapsed .child-link {
  justify-content: center;
}
</style>
