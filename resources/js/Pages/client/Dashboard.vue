<template>
    <ClientLayout>
        <div class="md:mx-24 p-5 pb-12 bg-white rounded-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-3">Dashboard</h2>
            <ul class="flex flex-col gap-3">
                <Link
                    :href="`/dashboard/${reservation.uniqueNumber}`"
                    v-for="(reservation, i) in reservations.data"
                    :key="i"
                    class="py-4 cursor-pointer border px-4 rounded-lg transition duration-300 ease-in-out hover:bg-gray-300 hover:scale-105"
                >
                    <div class="flex justify-between">
                        <span class="font-semibold text-gray-800"
                            >Reservasi #{{ reservation.uniqueNumber }}</span
                        >
                        <span class="text-sm text-gray-500">{{
                            formatDateID(reservation.date)
                        }}</span>
                    </div>
                    <div class="text-sm text-gray-800">
                        Paket: {{ reservation.package?.name }}
                    </div>
                    <div class="text-sm text-gray-800">
                        Sesi:
                        {{
                            reservation.session == "pagi"
                                ? "Pagi (09:00 - 11:00)"
                                : "Siang (12:00 - 02:00)"
                        }}
                    </div>
                    <div
                        class="text-sm text-gray-800 inline-flex py-1 px-3 mt-2 rounded uppercase font-bold"
                        :class="{
                            'bg-yellow-200': reservation.status === 'pending',
                            'bg-green-200': reservation.status === 'confirmed',
                            'bg-blue-200': reservation.status === 'waiting',
                            'bg-gray-200': reservation.status === 'finished',
                            'bg-red': reservation.status === 'rejected',
                        }"
                    >
                        {{ reservation.status }}
                    </div>
                </Link>
            </ul>
        </div>
    </ClientLayout>
</template>
<script setup>
import ClientLayout from "@/Components/Layouts/ClientLayout.vue";
import { format as formatDate } from "date-fns";
import { computed, watch } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { toastSuccess, toastError } from "@/Composables/useToast";

const { toast } = usePage().props;

if(toast){
    if(toast == 'Anda memiliki reservasi yang sedang diproses'){
        toastError(toast);

    }
}
const props = defineProps({
    reservations: {
        type: Object,
        required: true,
    },
});

function formatDateID(dateString) {
    const options = { year: 'numeric', month: 'long', day: '2-digit' };
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', options);
}

</script>
<style lang=""></style>
