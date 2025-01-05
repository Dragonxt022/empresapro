<script setup>
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import SlidebarAdmin from '@/Components/SlidebarAdmin.vue';

// Recebendo as props do componente pai
const props = defineProps({
  title: String,
});
</script>

<template>
  <Head :title="title" />
  <div class="h-screen flex flex-col md:flex-row">
    <!-- Sidebar -->
    <SlidebarAdmin />

    <!-- Content Area -->
    <main class="flex-1 bg-gray-100 p-6 overflow-y-auto">
      <slot />
    </main>
  </div>
</template>
