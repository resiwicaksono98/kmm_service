<template>
    <AdminLayout>
        <UISearch class="col-span-4" @update-value="handleSearchValue" />

        <div class="flex justify-end mb-4 mt-4">
            <UILink
                to="/admin/tasks/create"
                class="bg-primary text-white hover:bg-primary/90 text-sm flex items-center gap-2"
            >
                <UIIcon name="mdi:plus" class="h-4 w-4" />
                Add New Task</UILink
            >
        </div>
        <MyTable
            :headers="headers"
            :items="tasks.data"
            :searchValue="filterOptions.search"
        >
            <template v-slot:item-icon="{ item }">
                <img
                    :src="item.icon"
                    alt="Icon"
                    class="w-16 h-16 object-cover rounded-md shadow-md"
                />
            </template>
            <template v-slot:item-actions="{ item }">
                <div class="flex gap-1">
                    <UILinkBase :to="`/admin/tasks/edit/${item.id}`">
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

const props = defineProps(["tasks"]);
const page = usePage();
const headers = [
    { text: "name", value: "name" },
    { text: "icon", value: "icon" },
    { text: "Duration Of Work (Minutes)", value: "durationOfWork" },
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
    form.delete(`/admin/tasks/delete/${selectedItem?.value?.id}`, {
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
