<template>
    <ClientLayout>
        <Loader v-if="loading" />
        <div class="md:mx-24 pb-12">
            <div class="text-2xl text-center font-bold mt-12">Reservasi</div>
            <Steppy
                v-model:step="step"
                :loading="loading"
                primaryColor1="#7c85b3"
                nextText="Selanjutnya"
                backText="Kembali"
                doneText="Selesai"
            >
                <template #1>
                    <div class="text-xl font-semibold mb-8 font-mono">
                        Pilih Paket
                    </div>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div
                            v-for="(pkg, i) in packages?.data"
                            :key="i"
                            class="max-w-sm rounded overflow-hidden shadow-lg bg-white cursor-pointer transform transition-transform duration-300 hover:scale-105"
                            @click="handleSelectPackage(pkg)"
                            :class="{
                                'border-secondary border-2':
                                    form.packageId == pkg.id,
                            }"
                        >
                            <img
                                class="w-full max-h-[200px] object-cover"
                                :src="pkg.image"
                                alt="Service Image"
                            />
                            <div class="px-6 py-4">
                                <div class="font-bold text-xl mb-2">
                                    {{ pkg.name }}
                                </div>
                                <p class="text-gray-700 text-base">
                                    {{ pkg.description }}
                                </p>
                            </div>
                            <div class="px-4 md:px-6 pt-4 pb-2">
                                <ul class="space-y-2">
                                    <li
                                        v-for="(task, i) in pkg.tasks"
                                        :key="i"
                                        class="flex items-center text-gray-700 text-sm md:text-base"
                                    >
                                        <img
                                            :src="task.icon"
                                            class="h-5 w-5 text-teal mr-2"
                                        />
                                        {{ task.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <UIButton
                            class="mt-12"
                            :disabled="!form.packageId"
                            :loading="loading"
                            @click="step = 2"
                        >
                            Selanjutnya
                        </UIButton>
                    </div>
                </template>
                <template #2>
                    <div class="text-xl font-semibold mb-8 font-mono">
                        Pilih Jadwal
                    </div>
                    <!-- Select Date -->
                    <div class="text-sm font-semibold flex text-start mb-4">
                        * Pilih Tanggal Terlebih Dahulu
                    </div>
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
                    <!-- Select session -->
                    <div class="grid md:grid-cols-2 gap-6 mt-8">
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
                    <div class="flex justify-between">
                        <Link
                            class="mt-12 py-2 px-4 rounded text-white bg-secondary"
                            :loading="loading"
                            href="/reservations"
                        >
                            Kembali
                        </Link>
                        <UIButton
                            class="mt-12"
                            :loading="loading"
                            @click="step = 3"
                            :disabled="!form.session"
                        >
                            Selanjutnya
                        </UIButton>
                    </div>
                </template>
                <template #3>
                    <div class="text-xl font-semibold mb-8 font-mono">
                        Detail Reservasi
                    </div>
                    <div class="text-sm font-semibold flex text-start mb-4">
                        * Pastikan informasi reservasi anda sudah benar
                    </div>
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="border-t border-gray-200">
                            <dl>
                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Paket
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{ selectedPackageName }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Sesi
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{
                                            form.session == "pagi"
                                                ? "Pagi (09:00 - 11:00)"
                                                : "Siang (12:00 - 02:00)"
                                        }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Tanggal
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{ selectedDate }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Atas Nama
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{ user.full_name }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Nomor Handphone
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{ user.phone }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Alamat
                                    </dt>
                                    <dd
                                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                    >
                                        {{ user.address }}
                                    </dd>
                                </div>
                                <div
                                    class="bg-white px-4 py-5 sm:grid sm:grid-cols-3  sm:gap-4 sm:px-6"
                                >
                                    <dt
                                        class="text-sm font-medium text-gray-500"
                                    >
                                        Catatan Tambahan
                                    </dt>
                                    <div class="col-span-2">
                                        <textarea
                                            id="note"
                                            v-model="form.note"
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                            rows="3"
                                            placeholder="Catatan tambahan jika diperlukan"
                                        ></textarea>
                                    </div>
                                </div>
                            </dl>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <UIButton
                            class="mt-12 bg-secondary"
                            :loading="loading"
                            @click="step = 2"
                        >
                            Kembali
                        </UIButton>
                        <UIButton
                            class="mt-12"
                            :loading="loading"
                            @click="onSubmit"
                        >
                            Buat Reservasi
                        </UIButton>
                    </div>
                </template>
            </Steppy>
        </div>
    </ClientLayout>
</template>
<script setup>
import { Steppy } from "vue3-steppy";
import { ref, reactive, onMounted } from "vue";
import ClientLayout from "@/Components/Layouts/ClientLayout.vue";
import UIIcon from "@/Components/UI/UIIcon.vue";
import UIButton from "@/Components/UI/UIButton.vue";
import { toastSuccess, toastError } from "@/Composables/useToast";
import Loader from "@/Components/UI/Loader.vue";
import { useForm } from "@inertiajs/vue3";
import { format } from "date-fns";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

const props = defineProps({ packages: Object, user: Object });

const step = ref(1);
const loading = ref(false);

const selectedPackageName = ref("");
const selectedDate = ref("");
const form = useForm({
    packageId: null,
    session: null,
    date: null,
    note: null
});

function handleSelectPackage(pkg) {
    selectedPackageName.value = pkg.name;
    form.packageId = pkg.id;
}

function handleCheckAvailableBooking(date) {
    const myDate = new Date(date);
    const formateDate = date ? format(date, "yyyy-MM-dd") : null;
    selectedDate.value = format(date, "dd MMMM yyyy");

    if (myDate.getDay() === 0) {
        toastError("Pemesanan tidak tersedia pada hari Minggu.");
        form.date = null;
    }
    if (formateDate) {
        loading.value = true;
        form.date = formateDate;
        router.visit(`/reservations`, {
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
    morning: false,
    afternoon: false,
});

const onSubmit = () => {
    form.post("reservations", {
        packageId: form.packageId,
        date: form.date,
        session: form.session,
        onSuccess: () => {
            toastSuccess("Reservasi berhasil dibuat");
        },
    });
};
</script>
<style>
.wrapper-steppy {
    padding: 0 !important;
    margin-top: 28px;
}

.wrapper-steppy .controls {
    display: none !important;
}

.steppy {
    /* Start of Selection */
    @media (max-width: 768px) {
        margin: 0 50px !important;
    }
    @media (min-width: 769px) {
        margin: 0 200px !important;
    }
    /* End of Selection */
}

.steppy-pane {
    margin: 0 !important;
    margin-top: 24px !important;
    box-shadow: none !important;
}

.steppy-item-title {
    display: none;
}
</style>
