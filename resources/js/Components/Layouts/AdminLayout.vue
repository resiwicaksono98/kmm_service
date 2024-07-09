<template>
    <div class="flex h-screen bg-gray-100">
      <!-- Sidebar -->
      <Sidebar />
      <!-- Content Area -->
      <div class="flex-1 flex flex-col">
        <!-- Header -->
        <Header />
        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-5">
          <slot />
        </main>
        <Footer />
      </div>
    </div>
  </template>

  <script setup>
  import Sidebar from "@/Components/UI/Sidebar.vue"
  import Header from "@/Components/UI/Header.vue"
import Footer from "../UI/Footer.vue";
  </script>

  <style scoped>
  /* Additional styles can be added here */
  </style>
