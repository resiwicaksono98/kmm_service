<template>
    <nav class="bg-primary text-white py-4">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div
                    class="absolute inset-y-0 left-0 flex items-center sm:hidden"
                >
                    <!-- Mobile menu button-->
                    <button
                        type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        @click="isMenuOpen = !isMenuOpen"
                    >
                        <!-- Icon for menu open -->
                        <UIIcon
                            name="mdi:hamburger-menu"
                            v-show="!isMenuOpen"
                            class="h-6 w-6 text-white"
                        />
                        <!-- Icon for menu close -->
                        <UIIcon
                            name="mdi:close"
                            v-show="isMenuOpen"
                            class="h-6 w-6 text-white"
                        />
                    </button>
                </div>
                <div
                    class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start"
                >
                    <div
                        class="flex-shrink-0 flex items-center text-base font-bold bg-secondary px-4 py-2 rounded-lg"
                    >
                        Karya Motor Mandiri
                    </div>
                    <div class="hidden sm:block sm:ml-6 w-full">
                        <div
                            class="flex space-x-4 justify-between items-center"
                        >
                            <div class="flex">
                                <UILink
                                    to="/"
                                    class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                                    >Beranda</UILink
                                >
                                <UILink
                                    href="/reservations"
                                    class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                                    v-if="user"
                                    >Reservasi</UILink
                                >
                            </div>
                            <div>
                                <div v-if="user" class="flex items-center">
                                    <UILink
                                        href="/dashboard"
                                        class="text-white hover:bg-slate-50 hover:text-black px-3 py-2 rounded-md font-medium flex gap-1"
                                        >
                                        <UIIcon name="tabler:dashboard" class="h-6 w-6" />
                                        Dashboard</UILink
                                    >
                                    <button
                                        @click.prevent="handleLogout"
                                        class="text-white hover:bg-slate-50 hover:text-black flex gap-1 px-3 py-2 rounded-md font-medium"
                                    >
                                    <UIIcon name="tabler:logout" class="h-6 w-6" />
                                        Logout
                                    </button>
                                </div>
                                <div v-else class="flex items-center">
                                    <UILink
                                        to="/auth/login"
                                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                                        >Login</UILink
                                    >
                                    <UILink
                                        to="/auth/register"
                                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                                        >Register</UILink
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile menu, show/hide based on menu state. -->
        <div class="sm:hidden" id="mobile-menu" v-show="isMenuOpen">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <UILink
                    to="/"
                    class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                    >Beranda</UILink
                >
                <UILink
                href="/reservations"
                    class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                    v-if="user"
                    >Reservasi</UILink
                >
                <div v-if="user">
                    <UILink
                        href="/dashboard"
                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                        >Dashboard</UILink
                    >
                    <UILink
                        href="#"
                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                        >Profile</UILink
                    >
                    <button
                        @click.prevent="handleLogout"
                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                    >
                        Logout
                    </button>
                </div>
                <div v-else>
                    <UILink
                        href="#"
                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                        >Login</UILink
                    >
                    <UILink
                        href="#"
                        class="text-white hover:bg-slate-50 hover:text-black block px-3 py-2 rounded-md font-medium"
                        >Register</UILink
                    >
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup>
import UIIcon from "@/Components/UI/UIIcon.vue";
import UILink from "@/Components/UI/UILink.vue";
import { ref, computed } from "vue";
import { usePage, useForm } from "@inertiajs/vue3";
import { toastSuccess } from "@/Composables/useToast";
const page = usePage();

const user = computed(() => page.props.user);
const isMenuOpen = ref(false);
const form = useForm({});

const handleLogout = () => {
    form.post("/auth/logout", {
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
};
</script>

<style scoped>
/* Tailwind CSS styles specific to this component */
</style>
