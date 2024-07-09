<template>
    <Icon :icon="name" />
</template>
<script setup>
import { Icon } from "@iconify/vue";
defineProps({
    name: {
        type : String
    }
})
</script>
<style lang=""></style>
