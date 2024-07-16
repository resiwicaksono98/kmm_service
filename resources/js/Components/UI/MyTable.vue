<template>
    <div>
        <EasyDataTable
            :headers="headers"
            :items="items"
            :search-field="searchField"
            :search-value="searchValue"
            alternating
            border-cell
            show-index
        >
            <template v-slot:item-status="item">
                <slot name="item-status" :item="item"></slot>
            </template>
            <template v-slot:item-description="item">
                <slot name="item-description" :item="item">
                    <div class="max-w-lg">
                        {{ item.description }}
                    </div>
                </slot>
            </template>
            <template v-slot:item-icon="item">
                <slot name="item-icon" :item="item"></slot>
            </template>
            <template v-slot:item-image="item">
                <slot name="item-image" :item="item"></slot>
            </template>
            <template v-slot:item-assignJob="item">
                <slot name="item-assignJob" :item="item"></slot>
            </template>
            <template v-slot:item-note="item">
                <slot name="item-note" :item="item">
                    <div class="max-w-sm">{{ item.note }}</div>
                </slot>
            </template>
            <template v-slot:item-actions="item">
                <slot name="item-actions" :item="item"></slot>
            </template>
        </EasyDataTable>
    </div>
</template>
<script setup>
defineProps({
    headers: {
        type: Array,
        required: true,
    },
    items: {
        type: Array,
        required: true,
    },
    searchField: {
        type: String,
        required: false,
    },
    searchValue: {
        type: String,
        required: false,
    },
});
</script>
<style lang=""></style>
