<template>
    <Link :href="to" class="block py-2 px-4  rounded hover:bg-secondary">
        <slot />
    </Link>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    to: {
        type: String,
    },
});
</script>
<style lang=""></style>
