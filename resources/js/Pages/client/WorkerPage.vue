<template>
    <div>
        <div class="p-4 md:mx-10">
            <!-- Profile -->
            <div class="grid md:grid-cols-2 gap-4">
                <div class="bg-slate-100 border rounded-xl p-6">
                    <div class="space-y-2">
                        <h3 class="text-xl font-bold text-gray-800">
                            Nama: {{ user?.full_name }}
                        </h3>
                        <p class="text-gray-700">Email: {{ user?.email }}</p>
                        <p class="text-gray-700">Nomor HP: {{ user?.phone }}</p>
                    </div>
                    <button
                        class="mt-4 flex items-center justify-center bg-red hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-300"
                        @click="logoutHandler"
                    >
                        <UIIcon name="mdi:logout" class="mr-2 h-5 w-5" />
                        Logout
                    </button>
                </div>
                <div class="bg-slate-100 border rounded-lg p-4">
                    <div>
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-bold">
                                Statistik Pekerjaan
                            </h3>
                            <div class="text-gray-500 text-sm">
                                {{
                                    new Date().toLocaleDateString("id-ID", {
                                        day: "2-digit",
                                        month: "long",
                                        year: "numeric",
                                    })
                                }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-blue-100 shadow-md rounded-lg p-4">
                                <h4 class="text-md font-semibold text-blue-800">
                                    Pekerjaan Hari Ini
                                </h4>
                                <p class="text-lg font-bold text-blue-900">
                                    {{ workingProgress?.today }}
                                </p>
                            </div>
                            <div class="bg-green-100 shadow-md rounded-lg p-4">
                                <h4
                                    class="text-md font-semibold text-green-800"
                                >
                                    Pekerjaan Selesai
                                </h4>
                                <p class="text-lg font-bold text-green-900">
                                    {{ workingProgress?.ended }}
                                </p>
                            </div>
                            <div class="bg-yellow-100 shadow-md rounded-lg p-4">
                                <h4
                                    class="text-md font-semibold text-yellow-800"
                                >
                                    Pekerjaan Berjalan
                                </h4>
                                <p class="text-lg font-bold text-yellow-900">
                                    {{ workingProgress?.in_progress }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white border rounded-lg md:mt-8 p-4">
                <div class="text-gray-500 text-sm text-end">
                    {{ new Date().toLocaleDateString("id-ID", {
                        day: "2-digit",
                        month: "long",
                        year: "numeric",
                    }) }}
                </div>
                <div class="border-b">
                    <ul class="flex flex-wrap">
                        <li class="mr-2">
                            <button
                                class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:border-blue-500 hover:text-blue-600"
                                :class="{
                                    'border-blue-500 text-blue-600':
                                        tab === 'tab1',
                                }"
                                @click="tab = 'tab1'"
                            >
                                Tugas
                            </button>
                        </li>
                        <li class="mr-2">
                            <button
                                class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:border-blue-500 hover:text-blue-600"
                                :class="{
                                    'border-blue-500 text-blue-600':
                                        tab === 'tab2',
                                }"
                                @click="tab = 'tab2'"
                            >
                                Riwayat
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="pt-4">
                    <div v-show="tab === 'tab1'">
                        <ul class="divide-y divide-gray-300">
                            <li
                                v-for="(job, index) in assignmentsToday.data.sort((a, b) =>
                                    a.status === 'assignment' ? -1 : (a.status === 'completed' ? 1 : 0)
                                )"
                                :key="index"
                                class="py-2"
                            >
                                <div
                                    @click="toggleDropdown(job)"
                                    class="cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition duration-200"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <span
                                                    class="font-semibold text-lg text-blue-700"
                                                    >#{{ job.reservation.uniqueNumber }}</span
                                                >
                                                <div>-</div>
                                                <div
                                                    :class="`font-semibold capitalize ${
                                                        job.status === 'completed'
                                                            ? 'text-green-600'
                                                            : job.status === 'assignment'
                                                            ? 'text-blue-600'
                                                            : job.status === 'pending'
                                                            ? 'text-yellow-600'
                                                            : job.status === 'cancelled'
                                                            ? 'text-gray-600'
                                                            : 'text-red'
                                                    }`"
                                                >
                                                    {{ job.status }}
                                                </div>
                                            </div>
                                            <span class="text-gray-6000">{{
                                                job.reservation.user.fullName
                                            }}</span>
                                        </div>

                                        <UIIcon
                                            :name="
                                                job.isDropdownOpen
                                                    ? 'mdi:chevron-up'
                                                    : 'mdi:chevron-down'
                                            "
                                            class="h-6 w-6 text-gray-700"
                                        />
                                    </div>
                                    <div
                                        v-if="job.isDropdownOpen"
                                        class="mt-3 bg-gray-50 p-3 rounded-lg shadow-sm"
                                    >
                                        <p class="">
                                            Paket
                                            <span class="font-semibold"
                                                >: {{ job.reservation.package.name }}</span
                                            >
                                        </p>
                                        <p class="my-2">Tugas :</p>
                                        <ul class="list-disc ml-6 space-y-1">
                                            <li
                                                v-for="task in job.reservation.package.tasks"
                                                :key="task.id"
                                                class="p-2 bg-white rounded-md shadow hover:bg-gray-100 transition-colors"
                                            >
                                                <span
                                                    class="text-gray-800 font-medium"
                                                    >{{ task.name }}</span
                                                >
                                            </li>
                                        </ul>
                                        <div
                                            class="flex justify-end space-x-2 mt-4"
                                            v-if="job.status != 'completed' && job.status != 'cancelled'"
                                        >
                                            <div
                                                class="bg-green-500 text-white px-4 py-2 rounded-md"
                                                @click.prevent="markAsTask(job, 'completed')"
                                            >
                                                <UIIcon
                                                    name="mdi:check"
                                                    class="h-5 w-5"
                                                />
                                            </div>
                                            <div
                                                class="bg-yellow-500 text-white px-4 py-2 rounded-md"
                                                @click.prevent="markAsTask(job, 'pending')"
                                            >
                                                <UIIcon
                                                    name="mdi:clock-outline"
                                                    class="h-5 w-5"
                                                />
                                            </div>
                                            <div
                                                class="bg-red text-white px-4 py-2 rounded-md"
                                                @click.prevent="markAsTask(job, 'cancelled')"
                                            >
                                                <UIIcon
                                                    name="mdi:close"
                                                    class="h-5 w-5"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div v-show="tab === 'tab2'">
                        <ul class="divide-y divide-gray-300">
                            <li
                                v-for="(job, index) in assignments.data.filter(job => job.status !== 'assignment')"
                                :key="index"
                                class="py-2"
                            >
                                <div
                                    @click="toggleDropdown(job)"
                                    class="cursor-pointer hover:bg-gray-100 p-2 rounded-lg transition duration-200"
                                >
                                    <div
                                        class="flex justify-between items-center"
                                    >
                                        <div class="flex flex-col">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <span
                                                    class="font-semibold text-lg text-blue-700"
                                                    >#{{ job.reservation.uniqueNumber }}</span
                                                >
                                                <div>-</div>
                                                <div
                                                    class="text-gray-500 text-sm text-end"
                                                >
                                                    {{ new Date(job.date).toLocaleDateString("id-ID", {
                                                        day: "2-digit",
                                                        month: "long",
                                                        year: "numeric",
                                                    }) }}
                                                </div>
                                            </div>
                                        </div>

                                        <UIIcon
                                            :name="
                                                job.isDropdownOpen
                                                    ? 'mdi:chevron-up'
                                                    : 'mdi:chevron-down'
                                            "
                                            class="h-6 w-6 text-gray-700"
                                        />
                                    </div>
                                    <div
                                        v-if="job.isDropdownOpen"
                                        class="mt-3 bg-gray-50 p-3 rounded-lg shadow-sm"
                                    >
                                        <p>
                                            Status
                                            <span class="text-green-500"
                                                >: {{ job.status }}</span
                                            >
                                        </p>
                                        <p class="mt-2">
                                            Nama
                                            <span>: {{ job.reservation.user.fullName }}</span>
                                        </p>
                                        <p class="mt-2">
                                            Email
                                            <span>: {{ job.reservation.user.email }}</span>
                                        </p>
                                        <p class="mt-2">
                                            Paket
                                            <span>: {{ job.reservation.package.name }}</span>
                                        </p>
                                        <p class="my-2">Tugas:</p>

                                        <ul class="list-disc ml-6 space-y-1">
                                            <li
                                                v-for="task in job.reservation.package.tasks"
                                                :key="task.id"
                                                class="p-2 bg-white rounded-md shadow hover:bg-gray-100 transition-colors"
                                            >
                                                <span
                                                    class="text-gray-800 font-medium"
                                                    >{{ task.name }}</span
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <UIConfirmDelete
        :message="messageDialogMarkTask"
        :show="dialogMarkTask"
        @confirm="changeStatus"
        @cancel="dialogMarkTask = false"
    />
</template>
<script setup>
import UIIcon from "@/Components/UI/UIIcon.vue";
import { usePage, router } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import UIConfirmDelete from "../../Components/UI/UIConfirmDelete.vue";
import { toastSuccess, toastError } from "../../Composables/useToast";

const pageProps = usePage().props;
const user = pageProps.user;
const workingProgress = pageProps.workingProgress;
const assignmentsToday = pageProps.assignmentsToday;
const assignments = pageProps.assignments;
const {toast} = pageProps

onMounted(() => {
    if (!user && user?.role != "worker") {
        router.visit("/worker/login", { method: "get" });
    }

});

const tab = ref("tab1");
const jobDesks = ref([
    {
        id: 1,
        orderId: "1234567890",
        userName: "John Doe",
        userEmail: "johndoe@example.com",
        userPhone: "+628123456789",
        package: "Hemat Murmer",
        status: "Selesai",
        tasks: [
            {
                id: 1,
                name: "Ganti Oli",
            },
            {
                id: 1,
                name: "Pengecekan Mesin",
            },
        ],
    },
    {
        id: 2,
        orderId: "224455",
        userName: "Mark Doe",
        userEmail: "markdoe@example.com",
        userPhone: "+628123456789",
        package: "10RB KILOMETER",
        status: "Belum Selesai",
        tasks: [
            {
                id: 1,
                name: "Ganti Oli",
            },
            {
                id: 1,
                name: "Pengecekan Mesin",
            },
        ],
    },
]);


const toggleDropdown = (job) => {
    job.isDropdownOpen = !job.isDropdownOpen
};

jobDesks.value.forEach((job) => {
    job.isDropdownOpen = false;
});

function logoutHandler() {
    router.visit("worker/logout", { method: "post" });
}

const dialogMarkTask = ref(false)
const messageDialogMarkTask = ref('')
const selectedJob = ref()
const jobType = ref()

function markAsTask(job, type) {
    dialogMarkTask.value = true
    messageDialogMarkTask.value = 'Apakah anda yakin ingin mengubah status tugas ini menjadi ' + type + '?'
    job.isDropdownOpen = false
    jobType.value = type
    selectedJob.value = job
}

function changeStatus() {
    router.visit(`worker/${selectedJob.value.id}/update-status`, {
        method: 'post',
        data: {
            status: jobType.value
        },
        onSuccess: (data) => {
            toastSuccess(data.props.toast);
        }
    })
}

</script>
<style lang=""></style>
