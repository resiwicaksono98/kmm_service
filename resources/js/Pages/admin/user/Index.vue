<template>
    <AdminLayout>
        <div class="grid grid-cols-5 items-center gap-4 mb-4">
            <UISingleSelect
                name="searchFieldRole"
                :options="['tidak pilih', 'user', 'admin', 'worker']"
                placeholder="Role"
                @update-value="handleFilter"
                :value="filterOptions.role"
            />
            <UISearch class="col-span-4" @update-value="handleSearchValue" />
        </div>
        <div class="flex justify-start mb-4">
            <UILink
                to="/admin/users/create"
                class="bg-primary text-white hover:bg-primary/90 text-sm flex items-center gap-2"
            >
                <UIIcon name="mdi:plus" class="h-4 w-4" />
                Add New User</UILink
            >
        </div>
        <MyTable
            :headers="headers"
            :items="users.data"
            :searchValue="filterOptions.search"

        >
            <template v-slot:item-actions="{ item }">
                <div v-if="item.role != 'admin'" class="flex gap-1">
                    <UILinkBase :to="`/admin/users/edit/${item.id}`">
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

const props = defineProps(["users"]);
const page = usePage();
const headers = [
    { text: "name", value: "fullName" },
    { text: "email", value: "email" },
    { text: "phone", value: "phone" },
    { text: "address", value: "address" },
    { text: "role", value: "role" },
    { text: "ACTIONS", value: "actions" },
];

const filterOptions = reactive({
    role: "",
    search: "",
});
function handleSearchValue(value) {
    filterOptions.search = value;
}

function handleFilter(value) {
    if (value == "tidak pilih") {
        filterOptions.search = "";
        filterOptions.role = "";
    } else {
        filterOptions.search = value;
        filterOptions.role = value;
    }
}

const dialogDeleteConfirm = ref(false);
const selectedItem = ref(null);

function handleDialogDelete(item) {
    dialogDeleteConfirm.value = true;
    selectedItem.value = item;
}

const form = useForm({});
function handleDelete() {
    form.delete(`/admin/users/delete/${selectedItem?.value?.id}`, {
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
