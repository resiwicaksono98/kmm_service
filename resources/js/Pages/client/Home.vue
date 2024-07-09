<template>
    <ClientLayout>
        <div class="bg-slate-50 h-full">
            <div
                class="mx-8 grid md:grid-cols-12 gap-4 justify-center items-center h-full"
            >
                <div class="col-span-7">
                    <div class="text-3xl md:text-6xl text-primary font-bold">
                        Nikmati hematnya servis kendaraan!
                    </div>
                    <div class="text-secondary text-lg md:text-2xl mt-2">
                        Jelajahi promo dan booking sekarang!
                    </div>
                    <UIButton class="bg-red font-bold md:text-xl mt-4 md:mt-10"
                        >Reservasi Sekarang!</UIButton
                    >
                </div>
                <div class="col-span-5 hidden md:block">
                    <img
                        src="@/Assets/home-service.jpg"
                        alt="service"
                        class="h-[500px] rounded-lg"
                    />
                </div>
            </div>
        </div>
    </ClientLayout>
</template>

<script setup>
import ClientLayout from "@/Components/Layouts/ClientLayout.vue";
import UIButton from "@/Components/UI/UIButton.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const user = computed(() => page.props.user);
</script>
