<template>
    <ClientLayout>
        <div class="md:mx-24 p-5 pb-12 bg-white rounded-xl">
            <div class="flex items-center mb-5">
                <Link href="/dashboard">
                    <UIIcon
                        name="mdi:arrow-left"
                        class="text-gray-900 h-6 w-6 cursor-pointer"
                    />
                </Link>
                <h2 class="text-2xl font-bold text-gray-900 ml-2">
                    Detail Reservasi
                </h2>
            </div>
            <div class="text-sm font-semibold flex text-start mb-4">
                * Pastikan kedatangan sesuai tanggal dan sesi yang dijadwalkan,
                jika melebihi itu reservasi akan dibatalkan
            </div>
            <div class="border p-6 rounded-xl mb-6 bg-gray-50">
                <div class="flex justify-between items-center mb-4">
                    <span class="font-bold text-gray-9000"
                        >Reservasi #{{ reservation.data.uniqueNumber }}</span
                    >
                    <span class="text-sm text-gray-60">{{
                        formatDate(reservation.data.date)
                    }}</span>
                </div>
                <div>
                    <div class="text-base text-gray-900 mb-3">
                        Atas Nama:
                        <span class="font-semibold">{{
                            reservation.data.user.fullName
                        }}</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Nomor Handphone:
                        <span class="font-semibold">{{
                            reservation.data.user.phone
                        }}</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Alamat :
                        <span class="font-semibold">{{
                            reservation.data.user.address
                        }}</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Paket:
                        <span class="font-semibold">{{
                            reservation.data.package.name
                        }}</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Sesi:
                        <span class="font-semibold">{{
                            reservation.data.session == "pagi"
                                ? "Pagi (09:00 - 11:00)"
                                : "Siang (12:00 - 02:00)"
                        }}</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Estimasi Waktu :
                        <span class="font-semibold">{{ reservation.data?.package.estimatedTime }} Menit</span>
                    </div>
                    <div class="text-base text-gray-900 mb-3">
                        Nama Mekanik :
                        <span class="font-semibold">{{ reservation.data?.workerName }}</span>
                    </div>
                    <div class="flex gap-2">
                        <div>Status :</div>
                        <div
                            class=""
                            :class="{
                                'text-base mb-3': true,
                                'text-green-600':
                                    reservation.data.status === 'confirmed',
                                'text-amber-500':
                                    reservation.data.status === 'waiting',
                                'text-blue-500':
                                    reservation.data.status === 'finished',
                                'text-red':
                                    reservation.data.status === 'rejected',
                                'text-yellow-500':
                                    reservation.data.status === 'pending',
                            }"
                        >
                            {{
                                reservation.data.status === "confirmed"
                                    ? "Dikonfirmasi"
                                    : reservation.data.status === "waiting"
                                    ? "Menunggu Pengerjaan"
                                    : reservation.data.status === "finished"
                                    ? "Selesai"
                                    : reservation.data.status === "pending"
                                    ? "Menunggu Konfirmasi"
                                    : "Ditolak"
                            }}
                        </div>
                    </div>
                    <div class="text-base text-gray-90 mb-2">
                        Detail Layanan:
                    </div>
                    <ul
                        v-for="service in reservation.data.package.tasks"
                        class="list-disc list-inside text-base text-gray-900 font-semibold"
                    >
                        <li>{{ service.name }}</li>
                    </ul>
                    <div
                        v-if="reservation.data.status === 'pending'"
                        class="mt-4 cursor-pointer"
                    >
                        <button
                            class="bg-red py-2 px-4 rounded-lg text-white"
                            @click="dialogCancelOrder = true"
                        >
                            Batalkan Pesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <UIConfirmDelete
            :show="dialogCancelOrder"
            @confirm="cancelOrder"
            @cancel="dialogCancelOrder = false"
            message="Apakah Anda yakin ingin membatalkan reservasi ini?"
        />
    </ClientLayout>
</template>
<script setup>
import ClientLayout from "@/Components/Layouts/ClientLayout.vue";
import UIIcon from "@/Components/UI/UIIcon.vue";

import { Link, usePage, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import UIConfirmDelete from "../../Components/UI/UIConfirmDelete.vue";
import { toastSuccess, toastError } from "@/Composables/useToast";

const form = useForm({});
const { reservation } = usePage().props;

const props = defineProps({
    reservation: Object,
});

const dialogCancelOrder = ref(false);
function cancelOrder() {
    form.delete(`/dashboard/${reservation.data.uniqueNumber}`, {
        onSuccess: () => {
            toastSuccess("Reservasi berhasil dibatalkan");
        },
    });
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: '2-digit' };
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', options);
}

</script>
<style lang=""></style>
