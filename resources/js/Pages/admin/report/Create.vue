<template>
    <AdminLayout>
        <div>
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">* Nama</label>
                    <input type="text" id="name" name="name" v-model="form.name" placeholder="Nama Laporan"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                    <p v-if="errors.name" class="text-red">
                        {{ errors.name }}
                    </p>
                </div>

                <div class="mb-4">
                    <div class="flex gap-4 items-center justify-center">
                        <div class="w-full">
                            <label for="startDate" class="block text-gray-700 mb-2">* Tanggal Mulai</label>
                            <VDatePicker expanded v-model="form.startDate" mode="date"
                                @update:modelValue="handleStartDate">
                            </VDatePicker>
                            <p v-if="errors.startDate" class="text-red">
                                {{ errors.startDate }}
                            </p>
                        </div>
                        <div class="w-full">
                            <label for="endDate" class="block text-gray-700 mb-2">* Tanggal Selesai</label>
                            <VDatePicker expanded v-model="form.endDate" mode="date" @update:modelValue="handleEndDate">
                            </VDatePicker>
                            <p v-if="errors.endDate" class="text-red">
                                {{ errors.endDate }}
                            </p>
                        </div>
                    </div>
                </div>
                <button type="submit"
                    class="mt-4 w-full bg-primary text-white py-2 px-4 rounded-md hover:bg-secondary transition duration-300">
                    Submit
                </button>
            </form>
        </div>
    </AdminLayout>
</template>
<script setup>
import AdminLayout from "@/Components/Layouts/AdminLayout.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { toastSuccess } from "@/Composables/useToast";
import { reactive } from "vue";
import { format } from "date-fns";

const { errors } = defineProps({ errors: Object });

const page = usePage();

const form = useForm({
    name: "",
    startDate: "",
    endDate: ""
})

function handleStartDate(date) {
    const formatDate = date ? format(date, "yyyy-MM-dd") : null;
    form.startDate = formatDate
}
function handleEndDate(date) {
    const formatDate = date ? format(date, "yyyy-MM-dd") : null;
    form.endDate = formatDate
}

function handleSubmit() {
    form.post("/admin/reports/create", {
        name: form.name,
        startDate: form.startDate,
        endDate: form.endDate,
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
}
</script>
<style lang="">

</style>
