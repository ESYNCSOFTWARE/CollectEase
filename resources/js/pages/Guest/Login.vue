<script setup>
import InputError from "@commonComponents/InputError.vue";
import InputLabel from "@commonComponents/InputLabel.vue";
import PrimaryButton from "@commonComponents/PrimaryButton.vue";
import TextInput from "@commonComponents/TextInput.vue";
import { Mail, KeyRound } from "lucide-vue-next";
import { useForm } from "@inertiajs/vue3";

const appName = import.meta.env.VITE_APP_NAME;

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};
</script>

<template>

  <Head title="Login" />

  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="container max-w-6xl mx-auto">
      <div
        class="login-card flex flex-col lg:flex-row bg-white rounded-2xl overflow-hidden shadow-xl min-h-[600px] w-full">
        <!-- Illustration Section -->
        <div class="illustration-section flex-1 bg-gray-50 flex justify-center items-center p-10 lg:p-12">
          <img src="images/debt-collection-login.png" alt="Login Illustration" class="max-w-full h-auto" />
        </div>

        <!-- Form Section -->
        <div class="form-section flex-1 p-8 md:p-10 lg:p-12 flex flex-col justify-center">
          <form @submit.prevent="submit">
            <div class="logo text-3xl font-bold text-gray-700 text-center mb-2">
              <img src="images/Collexa.png" width="200" height="50" class="mx-auto" />
            </div>

            <div class="h-px  my-4 w-60 mx-auto"></div>
            <!-- <p class="subtitle text-gray-600 text-center mb-8 text-base">
              Login to start your session
            </p> -->

            <div class="form-group mb-5">
              <label for="email" class="block mb-2 font-medium text-gray-700">Email Address</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <Mail class="text-gray-200" />
                </div>
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                  placeholder="Enter email" required autofocus autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
              </div>
            </div>

            <div class="form-group mb-5">
              <label for="password" class="block mb-2 font-medium text-gray-700">Password</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <KeyRound class="text-gray-200" />
                </div>
                <TextInput id="password" type="password" class="mt-1 block w-full" placeholder="Enter password"
                  v-model="form.password" required autocomplete="current-password" />
              </div>
            </div>

            <div class="remember-forgot flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
              <div class="remember flex items-center mb-3 sm:mb-0">
                <input type="checkbox" id="remember"
                  class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
              </div>
              <a href="#" class="forgot-password text-orange-400 font-medium hover:underline text-sm">Forgot
                Password?</a>
            </div>

            <button
              class="w-full py-3 px-4 bg-primary cursor-pointer hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Login
            </button>

            <p class="terms text-gray-500 text-center text-sm mt-6">
              By clicking on Login, I accept the
              <a href="#" class="text-orange-400 hover:underline">Terms & Conditions</a>
            </p>

            <p class="support text-gray-500 text-center mt-4 text-sm">
              Need help?
              <a href="contacts/" class="text-orange-400 font-medium hover:underline">Contact Support</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
