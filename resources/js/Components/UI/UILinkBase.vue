<template>
    <Link :href="to" class="">
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
