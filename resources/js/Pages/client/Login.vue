<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <div
                v-if="$page.props.flash.message"
                class="bg-red mb-4 text-center text-white p-4 rounded-md"
            >
                {{ $page.props.flash.message }}
            </div>
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.email" class="text-red">
                        {{ form.errors.email }}
                    </div>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700"
                        >Password</label
                    >
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.password" class="text-red">
                        {{ form.errors.password }}
                    </div>
                </div>
                <button
                    type="submit"
                    class="w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-secondary transition duration-300"
                >
                    Login
                </button>
                <p class="mt-4 text-center">
                    Belum punya akun?
                    <UILink
                        to="/auth/register"
                        class="text-secondary hover:underline hover:bg-white"
                        >Daftar sekarang</UILink
                    >
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import UILink from "@/Components/UI/UILink.vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { toastSuccess, toastError } from "@/Composables/useToast";

const page = usePage();

const form = useForm({
    email: null,
    password: null,
});

const { errors } = defineProps({ errors: Object });

const handleLogin = () => {
    form.post("/auth/login", {
        email: email.value,
        password: password.value,
        onSuccess: () => {
            form.reset('password')
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
};
</script>

<style scoped>
/* Tambahkan gaya khusus di sini jika diperlukan */
</style>
