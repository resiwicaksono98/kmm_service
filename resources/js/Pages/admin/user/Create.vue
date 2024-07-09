<template>
    <AdminLayout>
        <div class="px-8">
            <form @submit.prevent="handleSubmit">
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">* Email</label>
                    <input
                        type="email"
                        id="email"
                        v-model="form.email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <p v-if="errors.email" class="text-red text-sm">
                        {{ errors.email }}
                    </p>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700"
                        >* Password</label
                    >
                    <input
                        type="password"
                        id="password"
                        v-model="form.password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <p v-if="errors.password" class="text-red text-sm">
                        {{ errors.password }}
                    </p>
                </div>
                <div class="mb-4">
                    <label for="fullName" class="block text-gray-700"
                        >* Nama Lengkap</label
                    >
                    <input
                        type="text"
                        id="fullName"
                        v-model="form.fullName"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.fullName" class="text-red">
                        {{ form.errors.fullName }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700"
                        >* Nomor Telepon</label
                    >
                    <input
                        type="text"
                        id="phone"
                        v-model="form.phone"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.phone" class="text-red">
                        {{ form.errors.phone }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700"
                        >Alamat</label
                    >
                    <textarea
                        id="address"
                        v-model="form.address"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        rows="3"
                    ></textarea>
                    <div v-if="form.errors?.address" class="text-red">
                        {{ form.errors.address }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700"
                        >* Role</label
                    >
                    <UISingleSelect
                        class="mt-1"
                        name="searchFieldRole"
                        :options="['user', 'admin', 'worker']"
                        placeholder="Role"
                        @update-value="form.role = $event"
                        :value="form.role"
                    />
                    <div v-if="form.errors?.role" class="text-red">
                        {{ form.errors.role }}
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

const page = usePage();
const { errors } = defineProps({ errors: Object });

const form = useForm({
    email: "",
    password: "",
    fullName: "",
    phone: "",
    address: "",
    role: "",
});

function handleSubmit() {
    form.post("/admin/users/create", {
        email: form.email,
        password: form.password,
        fullName: form.fullName,
        phone: form.phone,
        address: form.address,
        role: form.role,
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
}
</script>
<style lang=""></style>
