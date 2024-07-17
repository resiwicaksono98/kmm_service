<template>
    <AdminLayout>
        <div class="grid grid-cols-5 items-center gap-4 mb-4">
            <UISingleSelect
                name="searchFieldRole"
                :options="[
                    'tidak pilih',
                    'pending',
                    'confirmed',
                    'waiting',
                    'finished',
                    'rejected',
                ]"
                placeholder="Status"
                @update-value="handleFilter"
                :value="filterOptions.status"
            />
            <div class="flex items-center">
                <input
                    id="today"
                    type="checkbox"
                    name="dateFilter"
                    v-model="filterToday"
                    class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                />
                <label
                    for="today"
                    class="ml-2 block text-sm leading-5 text-gray-700"
                >
                    Hari Ini
                </label>
            </div>
        </div>

        <MyTable
            :headers="headers"
            :items="reservations.data"
            :searchValue="filterOptions.search"
        >
            <template v-slot:item-status="{ item }">
                <div
                    :class="{
                        'bg-yellow-500': item.status === 'pending',
                        'bg-blue-500': item.status === 'confirmed',
                        'bg-orange-500': item.status === 'waiting',
                        'bg-green-500': item.status === 'finished',
                        'bg-red': item.status === 'rejected',
                    }"
                    class="text-white p-1 rounded-lg font-semibold text-center uppercase"
                >
                    {{ item.status }}
                </div>
            </template>
            <template v-slot:item-assignJob="{ item }">
                <div
                    v-if="item.status == 'confirmed'"
                    class="text-white bg-primary cursor-pointer p-1 rounded-lg font-semibold text-center uppercase"
                    @click="openDialogAssignJob(item)"
                >
                    Assign Job
                </div>
            </template>
            <template v-slot:item-actions="{ item }">
                <div class="flex gap-1">
                    <UIIcon
                        name="mdi:eye-outline"
                        class="h-5 w-5 cursor-pointer"
                        @click="openDialogUpdateStatus(item)"
                    />
                    <UILinkBase :to="`/admin/reservations/${item.id}/edit`">
                        <UIIcon
                            name="mdi:square-edit-outline"
                            class="h-5 w-5 cursor-pointer"
                        />
                    </UILinkBase>
                    <UIIcon
                        name="mdi:trash-outline"
                        class="h-5 w-5 cursor-pointer"
                        @click="handleDialogDelete(item)"
                    />
                </div>
            </template>
        </MyTable>
        <UIConfirmDelete
            :show="dialogDeleteConfirm"
            @confirm="handleDelete"
            @cancel="dialogDeleteConfirm = false"
        />
        <!-- Dialog quick update status -->
        <div
            v-if="dialogQuickUpdateStatus"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Update Status</h2>
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
                    @update-value="onSelectStatus"
                    :value="selectedStatus"
                    v-model="selectedStatus"
                />
                <div class="flex justify-end mt-4">
                    <button
                        class="bg-gray-500 text-white px-4 py-2 rounded mr-2"
                        @click="dialogQuickUpdateStatus = false"
                    >
                        Cancel
                    </button>
                    <button
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                        @click="updateStatus"
                    >
                        Update
                    </button>
                </div>
            </div>
        </div>
        <!-- Dialog assign job -->
        <div
            v-if="dialogAssignJob"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        >
            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">
                    Assign Job To Mechanic
                </h2>
                <UISingleSelect
                    name="status"
                    :options="workerAvailable"
                    placeholder="Select Mechanic"
                    select-label="full_name"
                    select-value="id"
                    @update-value="onSelectMechanic"
                    :value="selectedMechanic"
                    v-model="selectedMechanic"
                />
                <div class="flex justify-end mt-4">
                    <button
                        class="bg-gray-500 text-white px-4 py-2 rounded mr-2"
                        @click="dialogAssignJob = false"
                    >
                        Cancel
                    </button>
                    <button
                        class="bg-blue-500 text-white px-4 py-2 rounded"
                        @click="assignJobHandler"
                    >
                        Assign
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup lang="ts">
import AdminLayout from "@/Components/Layouts/AdminLayout.vue";
import MyTable from "@/Components/UI/MyTable.vue";
import UIIcon from "@/Components/UI/UIIcon.vue";
import UILink from "@/Components/UI/UILink.vue";
import UILinkBase from "@/Components/UI/UILinkBase.vue";
import UISearch from "../../../Components/UI/UISearch.vue";
import { ref, onMounted } from "vue";
import UISingleSelect from "../../../Components/UI/UISingleSelect.vue";
import UIConfirmDelete from "../../../Components/UI/UIConfirmDelete.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { toastSuccess } from "@/Composables/useToast";
import { reactive } from "vue";
import { router } from "@inertiajs/vue3";
import { watch } from "vue";

const props = defineProps(["reservations", "workerAvailable"]);

const page = usePage();

const headers = [
    { text: "Name", value: "user.fullName" },
    { text: "Book At", value: "date" },
    { text: "Session At", value: "session" },
    { text: "Package Name", value: "package.name" },
    { text: "Status", value: "status" },
    { text: "Note", value: "note" },
    { text: "Assign Job", value: "assignJob" },
    { text: "ACTIONS", value: "actions" },
];

const filterOptions = reactive({
    status: "",
    search: "",
});

function handleFilter(value) {
    if (value == "tidak pilih") {
        filterOptions.search = "";
        filterOptions.status = "";
    } else {
        filterOptions.search = value;
        filterOptions.status = value;
    }
}

const filterToday = ref(false);

watch(filterToday, () => {
    if (filterToday.value) {
        filterOptions.search = new Date().toISOString().split("T")[0]
    } else {
        filterOptions.search = "";
    }
});

const dialogDeleteConfirm = ref(false);
const selectedItem = ref(null);

function handleDialogDelete(item) {
    dialogDeleteConfirm.value = true;
    selectedItem.value = item;
}

const form = useForm({});
function handleDelete() {
    form.delete(`/admin/reservations/${selectedItem?.value?.id}`, {
        onSuccess: () => {
            if (page.props.toast) {
                dialogDeleteConfirm.value = false;
                selectedItem.value = null;
                toastSuccess(page.props.toast);
            }
        },
    });
}

const formStatus = useForm({
    status: "",
});
const dialogQuickUpdateStatus = ref(false);
const selectedStatus = ref("");
const selectedStatusId = ref("");

function onSelectStatus(value) {
    formStatus.status = value;
}

function openDialogUpdateStatus(value) {
    dialogQuickUpdateStatus.value = true;
    selectedStatus.value = value.status;
    formStatus.status = value.status;
    selectedStatusId.value = value.id;
}

function updateStatus() {
    formStatus.post(
        `/admin/reservations/${selectedStatusId.value}/quick-update-status`,
        {
            status: formStatus.status,
            onSuccess: () => {
                toastSuccess(page.props.toast);
                dialogQuickUpdateStatus.value = false;
            },
        }
    );
}

const dialogAssignJob = ref(false);
const selectedMechanic = ref("");

function openDialogAssignJob(item) {
    dialogAssignJob.value = true;
    selectedItem.value = item;
}

function onSelectMechanic(value) {
    selectedMechanic.value = value;
}

function assignJobHandler() {
    router.post(
        `/admin/assignments/${selectedItem.value?.id}/assign-job`,
        {
            workerId: selectedMechanic.value.id,
        },
        {
            onSuccess: () => {
                toastSuccess(page.props.toast);
                dialogAssignJob.value = false;
            },
        }
    );
}
</script>
<style lang=""></style>
