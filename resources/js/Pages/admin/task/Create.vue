<template>
    <AdminLayout>
        <div class="px-8">
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">* Nama</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <p v-if="errors.name" class="text-red text-sm">
                        {{ errors.name }}
                    </p>
                </div>
                <div class="mb-4">
                    <label for="durationOfWork" class="block text-gray-700"
                        >* Durasi Pekerjaan (Menit)</label
                    >
                    <input
                        type="number"
                        id="durationOfWork"
                        v-model="form.durationOfWork"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.durationOfWork" class="text-red">
                        {{ form.errors.durationOfWork }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="icon" class="block text-gray-700">* Icon</label>
                    <input
                        type="file"
                        id="icon"
                        @change="previewImage"
                        accept="image/*"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.icon" class="text-red">
                        {{ form.errors.icon }}
                    </div>
                    <div v-if="iconPreview" class="mt-4">
                        <img :src="iconPreview" alt="Icon Preview" class="w-32 h-32 object-cover rounded-md shadow-md" />
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
import { useForm, usePage } from "@inertiajs/vue3";
import { toastSuccess } from "@/Composables/useToast";
import UISingleSelect from "@/Components/UI/UISingleSelect.vue";
import { ref } from 'vue';

const page = usePage();
const { errors } = defineProps({ errors: Object });

const iconPreview = ref('');

const previewImage = (event) => {
    const file = event.target.files[0];
    form.icon = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            iconPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};


const form = useForm({
    name: '',
    icon: '',
    durationOfWork: '',
});

function handleSubmit() {
    form.post("/admin/tasks/create", {
        name: form.name,
        icon: form.icon,
        durationOfWork: form.durationOfWork,
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
}
</script>
<style lang=""></style>
