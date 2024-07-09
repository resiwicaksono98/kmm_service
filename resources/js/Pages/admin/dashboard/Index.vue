<template >
    <AdminLayout>
        <div class="p-6 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="p-4 bg-primary text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Customer</h3>
                    <p class="text-2xl">123</p>
                </div>
                <div class="p-4 bg-secondary text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservations</h3>
                    <p class="text-2xl">456</p>
                </div>
                <div class="p-4 bg-teal text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservation Active</h3>
                    <p class="text-2xl">789</p>
                </div>
                <div class="p-4 bg-blue-700 text-white rounded-lg shadow">
                    <h3 class="text-lg font-semibold">Reservation Finished</h3>
                    <p class="text-2xl">101</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Components/Layouts/AdminLayout.vue"
</script>
<style lang="">

</style>
