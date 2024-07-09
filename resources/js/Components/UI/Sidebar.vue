<template>
    <div class="w-64 bg-primary text-white shadow-lg">
        <div class="p-5 text-xl font-bold">Karya Motor Mandiri</div>
        <ul class="space-y-2 p-2">
            <UILink
                to="/admin"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url === '/admin' }"
            >
                <UIIcon name="tabler:dashboard" class="h-6 w-6" />
                <div>Dashboard</div>
            </UILink>
            <UILink
                to="/admin/users"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/users') }"
            >
                <UIIcon name="tabler:users" class="h-6 w-6" />
                <div>Users</div>
            </UILink>
            <UILink
                to="/admin/tasks"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/tasks') }"
            >
                <UIIcon name="mdi:calendar-task" class="h-6 w-6" />
                <div>Tasks</div>
            </UILink>
            <UILink
                to="/admin/packages"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/packages') }"
            >
                <UIIcon name="tabler:clipboard-data" class="h-6 w-6" />
                <div>Package</div>
            </UILink>
            <UILink
                to="/admin/reservations"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/reservations') }"
            >
                <UIIcon name="tabler:calendar" class="h-6 w-6" />
                <div>Reservations</div>
            </UILink>
            <UILink
                to="/admin/schedules"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/schedules') }"
            >
                <UIIcon name="tabler:checklist" class="h-6 w-6" />
                <div>Schedules</div>
            </UILink>
            <UILink
                to="/admin/reports"
                class="flex items-center gap-2"
                :class="{ 'bg-secondary': url.startsWith('/admin/reports') }"
            >
                <UIIcon name="tabler:file-report" class="h-6 w-6" />
                <div>Reports</div>
            </UILink>
        </ul>
    </div>
</template>
<script setup>
import { usePage } from "@inertiajs/vue3";
import UIIcon from "./UIIcon.vue";
import UILink from "./UILink.vue";

const { url } = usePage();
</script>
<style lang=""></style>
