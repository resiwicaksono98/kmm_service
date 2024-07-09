<template>
  <div class="flex flex-col h-screen">
    <!-- Navbar -->
    <Navbar />
    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto">
      <slot />
    </div>
    <!-- Footer -->
    <FooterClient />
  </div>
</template>

<script setup>
import Navbar from "@/Components/UI/Navbar.vue";
import FooterClient from "@/Components/UI/FooterClient.vue";
</script>

<style scoped>
/* Style untuk layout bisa ditambahkan di sini */
</style>
