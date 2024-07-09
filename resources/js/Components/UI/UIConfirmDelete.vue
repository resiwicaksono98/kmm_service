<template>
  <transition name="fade">
    <div v-if="show" class="fixed z-50 inset-0 bg-black bg-opacity-50 flex justify-center items-center">
      <div class="bg-white p-4 rounded-lg shadow-lg">
        <div class="mb-4">
          <p> {{message || 'Apakah Anda yakin ingin menghapus item ini?'}}</p>
        </div>
        <div class="flex justify-end gap-4">
          <button @click="onCancel" class="bg-gray-300 hover:bg-gray-400 text-black py-2 px-4 rounded">
            Batal
          </button>
          <button @click="onConfirm" class="bg-red hover:bg-red-700 text-white py-2 px-4 rounded">
            Ya, Lakukan
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue';

const props = defineProps({
  show: Boolean,
  message: String
});

const emit = defineEmits(['confirm']);

function onConfirm() {
  emit('confirm');
}

function onCancel() {
  emit('cancel');
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
