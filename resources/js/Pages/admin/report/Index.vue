<template>
    <AdminLayout>
        <div class="flex justify-end mb-4 mt-4">
            <UILink to="/admin/reports/create"
                class="bg-primary text-white hover:bg-primary/90 text-sm flex items-center gap-2">
                <UIIcon name="mdi:plus" class="h-4 w-4" />
                Add New Report
            </UILink>
        </div>
        <MyTable :headers="headers" :items="reports.data">
            <template v-slot:item-status="{ item }">
                <div :class="{
                    'bg-yellow-500': item.status === 'pending',
                    'bg-blue-500': item.status === 'confirmed',
                    'bg-orange-500': item.status === 'waiting',
                    'bg-green-500': item.status === 'finished',
                    'bg-red': item.status === 'rejected',
                }" class="text-white p-1 rounded-lg font-semibold text-center uppercase">
                    {{ item.status }}
                </div>
            </template>
            <template v-slot:item-actions="{ item }">
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 cursor-pointer">
                        <UIIcon name="mdi:trash-outline" class="h-5 w-5 cursor-pointer"
                            @click="handleDialogDelete(item)" />
                        <p>Delete</p>
                    </div>
                    <div class="flex items-center gap-1 cursor-pointer" @click="excelDownloadHandle(item)">
                        <UIIcon name="mdi:file" class="h-5 w-5 cursor-pointer"  />
                        <p>Export Excel</p>

                    </div>

                </div>
            </template>
        </MyTable>
        <UIConfirmDelete :show="dialogDeleteConfirm" @confirm="handleDelete" @cancel="dialogDeleteConfirm = false" />
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Components/Layouts/AdminLayout.vue";
import MyTable from "@/Components/UI/MyTable.vue";
import UIIcon from "@/Components/UI/UIIcon.vue";
import UILink from "@/Components/UI/UILink.vue";
import UILinkBase from "@/Components/UI/UILinkBase.vue";
import UISearch from "../../../Components/UI/UISearch.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { reactive, ref } from "vue";
import UIConfirmDelete from "../../../Components/UI/UIConfirmDelete.vue";
import { toastSuccess } from "@/Composables/useToast";

const props = defineProps(["reports", "workerAvailable"]);


const page = usePage();

const headers = [
    { text: "Name", value: "name" },
    { text: "Start Date", value: "startDate" },
    { text: "End Date", value: "endDate" },
    { text: "ACTIONS", value: "actions" },
];

function excelDownloadHandle(item){
    window.open(item.fileUrl, '_blank');
}

const dialogDeleteConfirm = ref(false);
const selectedItem = ref(null);

function handleDialogDelete(item) {
    dialogDeleteConfirm.value = true;
    selectedItem.value = item;
}

const form = useForm({});
function handleDelete() {
    form.delete(`/admin/reports/${selectedItem?.value?.id}`, {
        onSuccess: () => {
            if (page.props.toast) {
                dialogDeleteConfirm.value = false;
                selectedItem.value = null;
                toastSuccess(page.props.toast);
            }
        },
    });
}

</script>
<style lang=""></style>
