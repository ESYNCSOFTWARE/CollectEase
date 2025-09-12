<script setup>
import { router, usePage } from '@inertiajs/vue3';
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { Lock, LogOut, User, Building2 } from 'lucide-vue-next';

defineProps({
    currentRoute: {
        type: String,
        default: 'Dashboard',
    },
});

const appName = import.meta.env.VITE_APP_NAME;

// ✅ properly grab user from Inertia
const page = usePage();
const user = page.props.auth.user;

const profile = (close) => {
    router.get(route('profile.edit'));
    close();
};

const changePassword = (close) => {
    router.get(route('profile.change_password'));
    close();
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

const logout = (close) => {
    router.post(route('logout'));
    close();
};
</script>

<template>
  <header class="w-[100%] mx-auto py-4 px-6 flex items-center justify-between top-0 z-50">
    <div class="flex-1 mx-6 max-w-md">
      <div class="flex flex-col lg:ml-20">
        <span
          class="text-sm sm:text-xl text-gray-500 font-bold inline-block"
          style="font-family: 'Roboto', sans-serif"
        >
          {{ currentRoute }}
        </span>
        <ol class="hidden md:flex items-center mt-2">
          <li class="flex items-center text-sm font-medium opacity-60">
            <svg class="mr-2.5 h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
              />
            </svg>
            <span>Home</span>
          </li>
          <li class="flex items-center text-sm font-medium opacity-60">
            <svg class="h-3 w-3 text-gray-400 mx-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
            </svg>
            <span class="text-primary">{{ currentRoute }}</span>
          </li>
        </ol>
      </div>
    </div>
    <div class="flex items-center space-x-4">
      <div class="flex items-center space-x-2 cursor-pointer">
        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold">
          {{ getInitials(user?.name) }}
        </div>
        <span class="text-gray-800 font-medium">{{ user?.name }}</span>
      </div>
    </div>
  </header>
</template>
