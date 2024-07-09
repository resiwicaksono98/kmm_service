<template>
    <header class="bg-white shadow p-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <UILinkBase v-if="props.back" class="bg-white" :to="props.back">
                <UIIcon name="ant-design:backward-outlined" class="h-5 w-5" />
            </UILinkBase>
            <h1 class="text-xl font-semibold text-gray-800">
                {{ props.title }}
            </h1>
        </div>
        <UIButton class="flex items-center gap-1.5" @click.prevent="logout">
            <UIIcon name="ant-design:logout-outlined" class="h-5 w-5" />
            <span>Logout</span>
        </UIButton>
    </header>
</template>
<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import UIButton from "./UIButton.vue";
import UIIcon from "./UIIcon.vue";
import UILinkBase from "./UILinkBase.vue";

const props = usePage().props;

const form = useForm({});

const logout = () => {
    form.post("/auth/logout", {
        onSuccess: () => {
            if (page.props.toast) {
                toastSuccess(page.props.toast);
            }
        },
    });
};
</script>
<style lang=""></style>
