<template>
    <AdminLayout>
        <div class="px-8">
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700"
                        >Nama Paket</label
                    >
                    <UISingleSelect
                        class="mt-1"
                        name="searchFieldRole"
                        :options="packages.data"
                        label="name"
                        trackBy="id"
                        placeholder="Pilih Paket"
                        @update-value="onSelectPackage"
                        v-model="selectedPackage"
                    />
                    <div v-if="form.errors?.packageId" class="text-red">
                        {{ form.errors.packageId }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2"
                        >Tanggal Reservasi</label
                    >
                    <VDatePicker
                        expanded
                        v-model="form.date"
                        mode="date"
                        :min-date="
                            new Date(
                                new Date().setDate(new Date().getDate() + 1)
                            )
                        "
                        :max-date="
                            new Date(
                                new Date().setDate(new Date().getDate() + 7)
                            )
                        "
                        @update:modelValue="handleCheckAvailableBooking"
                    >
                    </VDatePicker>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 mb-2"
                        >Sesi</label
                    >
                    <div class="grid md:grid-cols-2 gap-6">
                        <div
                            class="bg-white p-6 border rounded-xl flex items-center space-x-4 cursor-pointer"
                            :class="{
                                'border-secondary border-2':
                                    form.session == 'pagi',
                                'opacity-50': !availableSession.morning,
                            }"
                            @click="
                                availableSession.morning &&
                                    (form.session = 'pagi')
                            "
                        >
                            <UIIcon
                                name="mdi:weather-sunset-up"
                                class="h-8 w-8 text-orange-500"
                            />
                            <div class="text-start w-full">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <h3 class="text-xl font-bold">Pagi</h3>
                                    <div>
                                        <h3
                                            v-if="availableSession.morning"
                                            class="text-base text-green-600 font-bold"
                                        >
                                            Tersedia
                                        </h3>
                                        <h3
                                            class="text-base text-red font-bold"
                                            v-if="!availableSession.morning"
                                        >
                                            Tidak Tersedia
                                        </h3>
                                    </div>
                                </div>
                                <div class="text-gray-800">
                                    Jam 09:00 - 11:00
                                </div>
                                <span class="text-gray-600 text-sm"
                                    >Kedatangan dimulai pada jam 09:00 sampai
                                    jam 11:00</span
                                >
                            </div>
                        </div>
                        <div
                            class="bg-white p-6 border rounded-xl flex items-center space-x-4 cursor-pointer"
                            :class="{
                                'border-secondary border-2':
                                    form.session == 'siang',
                                'opacity-50': !availableSession.afternoon,
                            }"
                            @click="
                                availableSession.afternoon &&
                                    (form.session = 'siang')
                            "
                        >
                            <UIIcon
                                name="mdi:weather-sunny"
                                class="h-8 w-8 text-yellow-500"
                            />
                            <div class="text-start w-full">
                                <div
                                    class="flex items-center justify-between mb-2"
                                >
                                    <h3 class="text-xl font-bold">Siang</h3>
                                    <div>
                                        <h3
                                            class="text-base text-green-600 font-bold"
                                            v-show="availableSession.afternoon"
                                        >
                                            Tersedia
                                        </h3>
                                        <h3
                                            class="text-base text-red font-bold"
                                            v-show="!availableSession.afternoon"
                                        >
                                            Tidak Tersedia
                                        </h3>
                                    </div>
                                </div>
                                <div class="text-gray-800">
                                    Jam 12:00 - 14:00
                                </div>
                                <span class="text-gray-600 text-sm"
                                    >Kedatangan dimulai pada jam 12:00 sampai
                                    jam 14:00</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="durationOfWork" class="block text-gray-700 mb-2"
                        >Status</label
                    >
                    <UISingleSelect
                        name="status"
                        :options="[
                            'pending',
                            'confirmed',
                            'waiting',
                            'finished',
                            'rejected',
                        ]"
                        placeholder="Select Status"
                        @update-value="form.status = $event"
                        :value="form.status"
                        v-model="form.status"
                    />
                    <div v-if="form.errors?.durationOfWork" class="text-red">
                        {{ form.errors.durationOfWork }}
                    </div>
                </div>
                <button
                    type="submit"
                    class="mt-4 w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-secondary transition duration-300"
                >
                    Submit
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Components/Layouts/AdminLayout.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { toastSuccess } from "@/Composables/useToast";
import UISingleSelect from "@/Components/UI/UISingleSelect.vue";
import { ref, reactive } from "vue";
import { format } from "date-fns";
import UIIcon from "@/Components/UI/UIIcon.vue";

const page = usePage();
const loading = ref(false);
const { errors, reservation, packages } = defineProps({
    errors: Object,
    reservation: Object,
    packages: Object,
});


const form = useForm({
    packageId: reservation.data.package.id || "",
    date: reservation.data.date || "",
    session: reservation.data.session || "",
    status: reservation.data.status || "",
});


const selectedPackage = ref(reservation.data.package || "");
const selectedDate = ref(reservation.data.date || "");

function handleCheckAvailableBooking(date) {
    const myDate = new Date(date);
    const formateDate = date ? format(date, "yyyy-MM-dd") : null;
    selectedDate.value = date ? format(date, "dd MMMM yyyy") : null;

    if (myDate.getDay() === 0) {
        toastError("Pemesanan tidak tersedia pada hari Minggu.");
        form.date = null;
    }
    if (formateDate) {
        loading.value = true;
        form.date = formateDate;
        router.visit(`/admin/reservations/${reservation.data.id}/edit`, {
            method: "get",
            preserveState: true,
            data: {
                date: formateDate,
            },
            onSuccess: (response) => {
                loading.value = false;
                availableSession.morning =
                    response.props.availableSession.morning;
                availableSession.afternoon =
                    response.props.availableSession.afternoon;
            },
        });
    } else {
        loading.value = false;
        form.date = null;
        form.session = null;
        availableSession.morning = false;
        availableSession.afternoon = false;
    }
}

const availableSession = reactive({
    morning: reservation.data.session == "pagi" ? true : false,
    afternoon: reservation.data.session == "siang" ? true : false,
});


function onSelectPackage(value) {
    form.packageId = value.id;
}

function handleSubmit() {
    form.post(`/admin/reservations/${reservation.data.id}/edit`, {
        packageId: form.packageId,
        date: form.date,
        session: form.session,
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
}
</script>
<style lang=""></style>
