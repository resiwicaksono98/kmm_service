<template>
    <AdminLayout>
        <UISearch class="col-span-4 mb-4" @update-value="handleSearchValue" />
        <MyTable
            :headers="headers"
            :items="assignments.data"
            :searchValue="filterOptions.search"
        >
            <template v-slot:item-status="{ item }">
                <div
                    :class="{
                        'bg-yellow-500': item.status === 'pending',
                        'bg-blue-500': item.status === 'assignment',
                        'bg-green-500': item.status === 'completed',
                        'bg-red': item.status === 'cancelled',
                    }"
                    class="text-white p-1 rounded-lg font-semibold text-center uppercase"
                >
                    {{ item.status }}
                </div>
            </template>
            <template v-slot:item-actions="{ item }">
                <div class="flex gap-1">
                    <div @click="handleAssignJob(item)">
                        <UIIcon
                            name="mdi:square-edit-outline"
                            class="h-5 w-5 cursor-pointer"
                        />
                    </div>

                    <UIIcon
                        v-if="item.status == 'assignment' | item.status == 'pending'"
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
<script setup>
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

const props = defineProps(["assignments", "workerAvailable"]);
const page = usePage();

const headers = [
    { text: "Tanggal", value: "date" },
    { text: "Nama Customer", value: "reservation.user.fullName" },
    { text: "Nama Paket", value: "reservation.package.name" },
    { text: "Mekanik", value: "worker.fullName" },
    { text: "Status", value: "status" },
    { text: "Jam Mulai", value: "startTime" },
    { text: "Jam Selesai", value: "endTime" },
    { text: "ACTIONS", value: "actions" },
];

const filterOptions = reactive({
    search: "",
});
function handleSearchValue(value) {
    filterOptions.search = value;
}

const dialogDeleteConfirm = ref(false);
const selectedItem = ref(null);

function handleDialogDelete(item) {
    dialogDeleteConfirm.value = true;
    selectedItem.value = item;
}

const form = useForm({});
function handleDelete() {
    form.delete(`/admin/assignments/${selectedItem?.value?.id}`, {
        onSuccess: () => {
            if (page.props.toast) {
                dialogDeleteConfirm.value = false;
                selectedItem.value = null;
                toastSuccess(page.props.toast);
            }
        },
    });
}

const dialogAssignJob = ref(false);
const selectedMechanic = ref(null);

function handleAssignJob(item) {
    dialogAssignJob.value = true;
    selectedItem.value = item;
}

function onSelectMechanic(value) {
    selectedMechanic.value = value;
}
</script>
<style lang=""></style>
