<script setup>
import GuestLayout from '@layouts/GuestLayout.vue';
import InputError from '@commonComponents/InputError.vue';
import InputLabel from '@commonComponents/InputLabel.vue';
import PrimaryButton from '@commonComponents/PrimaryButton.vue';
import TextInput from '@commonComponents/TextInput.vue';
import { Link, useForm } from '@inertiajs/vue3';

const appName = import.meta.env.VITE_APP_NAME;

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <GuestLayout>
        <div
            class="bg-primary dark:bg-darkmode-800 xl:dark:bg-darkmode-600 before:bg-primary/20 before:dark:bg-darkmode-400 after:bg-primary after:dark:bg-darkmode-700 relative h-screen p-3 before:absolute before:inset-y-0 before:left-0 before:-mb-[16%] before:-ml-[13%] before:-mt-[28%] before:hidden before:w-[57%] before:rotate-[-4.5deg] before:transform before:rounded-[100%] before:content-[''] after:absolute after:inset-y-0 after:left-0 after:-mb-[13%] after:-ml-[13%] after:-mt-[20%] after:hidden after:w-[57%] after:rotate-[-4.5deg] after:transform after:rounded-[100%] after:content-[''] sm:px-8 lg:overflow-hidden xl:bg-white before:xl:block after:xl:block"
        >
            <div class="container relative z-10 sm:px-10">
                <div class="block grid-cols-2 gap-4 xl:grid">
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
                                alt="Login Page Logo"
                            />
                            <div
                                class="-intro-x mt-10 text-4xl font-medium leading-tight text-white"
                            >
                                A few more clicks to <br />
                                sign in to your account.
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="submit">
                        <div
                            class="my-10 mt-20 flex h-screen py-5 xl:my-0 xl:h-auto xl:py-0"
                            style="margin-top: 108px"
                        >
                            <div
                                class="dark:bg-darkmode-600 mx-auto my-auto w-full rounded-md bg-white px-5 py-8 shadow-md sm:px-8 xl:bg-transparent xl:p-0 xl:shadow-none"
                            >
                                <h2
                                    class="intro-x mt-28 text-center text-2xl font-bold xl:text-left xl:text-3xl"
                                >
                                    Signup
                                </h2>

                                <div class="intro-x mt-10">
                                    <InputLabel for="email" value="Name" />

                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        required
                                        autocomplete="username"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />

                                    <InputLabel for="email" value="Email" />

                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 block w-full"
                                        v-model="form.email"
                                        required
                                        autocomplete="username"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.email"
                                    />

                                    <InputLabel
                                        for="password"
                                        value="Password"
                                    />

                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="form.password"
                                        required
                                        autocomplete="new-password"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />

                                    <InputLabel
                                        for="password_confirmation"
                                        value="Confirm Password"
                                    />

                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="form.password_confirmation"
                                        required
                                        autocomplete="new-password"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="
                                            form.errors.password_confirmation
                                        "
                                    />
                                </div>

                                <div
                                    class="intro-x mt-5 text-center xl:mt-8 xl:text-left"
                                >
                                    <PrimaryButton
                                        class="mr-5"
                                        :class="{
                                            'opacity-25': form.processing,
                                        }"
                                        :disabled="form.processing"
                                    >
                                        Register
                                    </PrimaryButton>

                                    <Link
                                        :href="route('login')"
                                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        Already registered?
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
