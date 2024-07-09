<template>
    <div class="flex justify-center items-center h-screen absolute inset-0 bg-gray-800 bg-opacity-50 z-50">
      <span class="loader"></span>
    </div>
</template>
<script setup lang="ts"></script>
<style scoped>
.loader {
  width: 48px;
  height: 48px;
  border: 5px solid;
  border-color: #240750 transparent;
  border-radius: 50%;
  display: inline-block;
  box-sizing: border-box;
  animation: rotation 1s linear infinite;
}

@keyframes rotation {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
