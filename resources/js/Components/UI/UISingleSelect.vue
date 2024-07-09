<template>
    <Multiselect
        :options="options"
        :searchable="true"
        :close-on-select="true"
        :show-labels="false"
        :placeholder="placeholder"
        :label="selectLabel"
        :trackBy="selectValue"
        :caret="false"
        @select="updateValue"
        :class="newClass"
        v-model="model"
        class="rounded-md placeholder:text-sm focus:outline-primary"
        :allowEmpty="allowEmpty"
    >
        <template v-slot:caret>
            <div class="multiselect__select">
                <div class="flex justify-center items-center">
                    <UIIcon
                        name="mdi:chevron-down-circle"
                        class="h-8 w-8 text-black"
                    />
                </div>
            </div>
        </template>
    </Multiselect>
</template>
<script setup>
import Multiselect from "vue-multiselect";
import UIIcon from "./UIIcon.vue";
import { ref, watch } from "vue";
const model = ref();
const emit = defineEmits(["update-value"]);

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        required: false,
    },
    selectLabel: {
        type: String,
        required: false,
    },
    selectValue: {
        type: String,
        required: false,
    },
    newClass: {
        type: String,
        required: false,
    },
    value: {
        type: [String, Object],
        required: false,
    },
    allowEmpty: {
        type: Boolean,
        required: false,
    },
});

watch(() => props.value, (newValue) => {
    model.value = newValue;
});

function updateValue(newValue) {
    model.value = newValue;
    emit("update-value", newValue);
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>

<style>
.multiselect__select:before {
    display: none;
}
.multiselect__single {
    padding: 4px 6px;
}

.multiselect .multiselect__option--highlight {
    background-color: #f1f5f9;
    color: black;
}

.multiselect .multiselect__option--selected {
    background-color: #240750;
    color: white;
}
</style>
