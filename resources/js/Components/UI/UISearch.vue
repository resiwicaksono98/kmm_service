<template>
    <div class="flex border rounded-lg">
        <input type="text" v-model="searchValue" class="flex-grow p-2 rounded-lg" placeholder="Cari...">
        <span class="flex items-center justify-center p-2">
            <UIIcon name="mdi:magnify" class="h-5 w-5" />
        </span>
    </div>
</template>
<script setup>
import { ref, defineEmits,watch } from 'vue';
import UIIcon from './UIIcon.vue';

const searchValue = ref('');
const emits = defineEmits(['update-value']);

// Watch for changes in searchValue and emit them
watch(searchValue, (newValue) => {
    emits('update-value', newValue);
});
</script>
<style lang="">

</style>
