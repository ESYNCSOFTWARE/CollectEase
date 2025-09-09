<script setup>
import GuestLayout from '@layouts/GuestLayout.vue';
import { usePage } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME;

defineProps({
    status: {
        type: String,
    },
});

const { user } = usePage().props.auth;
</script>

<template>
    <Head title="Email Verification" />

    <GuestLayout>
        <div
            class="bg-primary dark:bg-darkmode-800 xl:dark:bg-darkmode-600 before:bg-primary/20 before:dark:bg-darkmode-400 after:bg-primary after:dark:bg-darkmode-700 relative h-screen p-3 before:absolute before:inset-y-0 before:left-0 before:-mb-[16%] before:-ml-[13%] before:-mt-[28%] before:hidden before:w-[57%] before:rotate-[-4.5deg] before:transform before:rounded-[100%] before:content-[''] after:absolute after:inset-y-0 after:left-0 after:-mb-[13%] after:-ml-[13%] after:-mt-[20%] after:hidden after:w-[57%] after:rotate-[-4.5deg] after:transform after:rounded-[100%] after:content-[''] sm:px-8 lg:overflow-hidden xl:bg-white before:xl:block after:xl:block"
        >
            <div class="container relative z-10 sm:px-10">
                <div class="block grid-cols-2 gap-4 xl:grid">
                    <!-- BEGIN: Login Info -->
                    <div class="hidden min-h-screen flex-col xl:flex">
                        <a class="-intro-x flex items-center pt-5" href="">
                            <span class="ml-3 text-lg text-white">
                                {{ appName }}
                            </span>
                        </a>
                        <div class="my-auto">
                            <img
                                class="-intro-x -mt-16 w-1/2"
                                src="/images/illustration.svg"
                                alt="Logo"
                            />
                            <div
                                class="-intro-x mt-10 text-4xl font-medium leading-tight text-white"
                            >
                                A few more clicks to <br />
                                sign in to your account.
                            </div>
                            <div
                                class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-slate-400"
                            >
                                Manage all your e-commerce accounts in one place
                            </div>
                        </div>
                    </div>

                    <div
                        class="my-10 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0"
                    >
                        <div
                            class="dark:bg-darkmode-600 mx-auto w-full rounded-md bg-white px-5 py-8 shadow-md sm:w-3/4 sm:px-8 xl:shadow-none"
                            style="margin-top: 300px"
                        >
                            <div class="max-w-xl px-5 text-center">
                                <h2
                                    class="mb-2 text-[42px] font-bold text-zinc-800"
                                >
                                    Check your inbox
                                </h2>
                                <p class="mb-2 text-lg text-zinc-500">
                                    We are glad, that you’re with us ? We’ve
                                    sent you a verification link to the email
                                    address
                                    <span class="font-medium text-indigo-500">{{
                                        user?.email
                                    }}</span
                                    >.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
