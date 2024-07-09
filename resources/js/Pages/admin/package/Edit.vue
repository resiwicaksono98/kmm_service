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
                    <label for="image" class="block text-gray-700"
                        >* Image</label
                    >
                    <input
                        type="file"
                        id="image"
                        @change="previewImage"
                        accept="image/*"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                    />
                    <div v-if="form.errors?.image" class="text-red">
                        {{ form.errors.image }}
                    </div>
                    <div v-if="imagePreview" class="mt-4">
                        <img
                            :src="imagePreview"
                            alt="Image Preview"
                            class="w-32 h-32 object-cover rounded-md shadow-md"
                        />
                    </div>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700"
                        >Deskripsi</label
                    >
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        rows="8"
                    ></textarea>
                    <div v-if="form.errors?.description" class="text-red">
                        {{ form.errors.description }}
                    </div>
                </div>
                <div class="mb-4">
                    <label for="tasks" class="block text-gray-7000 mb-4"
                        >Tugas</label
                    >
                    <div
                        v-for="(task, index) in form.tasks"
                        :key="task.id"
                        class="my-4"
                    >
                        <div class="flex items-center gap-2">
                            <UISingleSelect
                                name="tasks"
                                :options="myTask"
                                placeholder="Tugas"
                                label="name"
                                trackBy="id"
                                @update-value="onSelectTask($event, index)"
                                v-model="selectedTasks[index]"
                                :allowEmpty="false"
                            />
                            <UIIcon
                                name="tabler:trash"
                                class="h-6 w-6 cursor-pointer"
                                @click.prevent="removeTask(task, index)"
                            />
                        </div>
                        <div class="text-sm text-slate-500 mt-2">
                            * Wajib dipilih, Hapus jika tidak dipilih
                        </div>
                    </div>
                    <div v-if="form.errors?.tasks" class="text-red">
                        {{ form.errors.tasks }}
                    </div>
                    <div
                        class="inline-flex items-center gap-1 bg-primary text-white py-2 px-4 rounded-md hover:bg-secondary transition duration-300 text-sm cursor-pointer"
                        @click.prevent="addTask"
                    >
                        <UIIcon
                            name="tabler:circle-plus-filled"
                            class="h-5 w-5"
                        />
                        Tambah Tugas
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
import { ref, onMounted, watch } from "vue";
import UIIcon from "@/Components/UI/UIIcon.vue";

const page = usePage();
const {
    errors,
    tasks,
    package: packageProps,
} = defineProps({
    errors: Object,
    tasks: Object,
    package: Object,
});

onMounted(() => {
    myTask.value = tasks.data;
});
const imagePreview = ref(packageProps.data.image || "");

const previewImage = (event) => {
    const file = event.target.files[0];
    form.image = file;
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

onMounted(() => {
    myTask.value = myTask.value.filter((task) => {
        return !packageProps.data.tasks.some(
            (selected) => selected.id === task.id
        );
    });
});

const form = useForm({
    name: packageProps.data.name || "",
    image: "",
    description: packageProps.data.description || "",
    tasks: packageProps.data.tasks.map((task) => task.id) || [],
});

const myTask = ref([]);

const selectedTasks = ref(packageProps.data.tasks || []);

watch(
    selectedTasks,
    (newVal, oldValue) => {
        myTask.value = myTask.value.filter(
            (task) =>
                !selectedTasks.value.some((selected) => selected.id === task.id)
        );
    },
    { deep: true }
);
const onSelectTask = (value, index) => {
    selectedTasks.value[index] = value;
    form.tasks[index] = value.id;
};

const addTask = () => {
    form.tasks.push("");
};

const removeTask = (taskId, index) => {
    if (taskId) {
        const getTask = tasks.data.find((t) => t.id === taskId);
        myTask.value.push(getTask);
    }
    form.tasks.splice(index, 1);
    selectedTasks.value.splice(index, 1);
};

function handleSubmit() {
    form.post("/admin/packages/edit/" + packageProps.data.id, {
        name: form.name,
        image: form.image,
        description: form.description,
        tasks: form.tasks,
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
}
</script>
<style lang=""></style>
