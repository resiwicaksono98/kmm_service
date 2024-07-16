<template >
    <AdminLayout>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-4 bg-primary text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Pengguna</h3>
                    <p class="text-2xl">{{customer}}</p>
                </div>
                <div class="p-4 bg-secondary text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservasi</h3>
                    <p class="text-2xl">{{reservation}}</p>
                </div>
                <div class="p-4 bg-teal text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservasi Aktif</h3>
                    <p class="text-2xl">{{reservationActive}}</p>
                </div>
                <div class="p-4 bg-blue-700 text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservasi Selesai</h3>
                    <p class="text-2xl">{{reservationFinished}}</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Components/Layouts/AdminLayout.vue"
import { usePage } from "@inertiajs/vue3";

const {customer,reservation,reservationActive, reservationFinished} = usePage().props
</script>
<style lang="">

</style>
