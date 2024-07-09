<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <form @submit.prevent="handleRegister">
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
                <div class="mb-4">
                    <label for="fullName" class="block text-gray-700"
                        >Nama Lengkap</label
                    >
                    <input
                        type="text"
                        id="fullName"
                        v-model="form.fullName"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.fullName" class="text-red">
                        {{ form.errors.fullName }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700"
                        >Nomor Telepon</label
                    >
                    <input
                        type="text"
                        id="phone"
                        v-model="form.phone"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.phone" class="text-red">
                        {{ form.errors.phone }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700"
                        >Alamat</label
                    >
                    <textarea
                        id="address"
                        v-model="form.address"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        rows="3"
                    ></textarea>
                    <div v-if="form.errors?.address" class="text-red">
                        {{ form.errors.address }}
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
                    Register
                </button>
                <p class="mt-4 text-center">
                    Sudah punya akun?
                    <UILink
                        to="/auth/login"
                        class="text-secondary hover:underline hover:bg-white"
                        >Masuk sekarang</UILink
                    >
                </p>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import UILink from "@/Components/UI/UILink.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { toastSuccess, toastError } from "@/Composables/useToast";

const page = usePage();

const form = useForm({
    email: null,
    password: null,
    fullName: null,
    phone: null,
    address: null,
});

const { errors } = defineProps({ errors: Object });

const handleRegister = () => {
    form.post("/auth/register", {
        email: form.email,
        password: form.password,
        fullName: form.fullName,
        phone: form.phone,
        address: form.address,
        onSuccess: () => {
            form.reset('password');
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
