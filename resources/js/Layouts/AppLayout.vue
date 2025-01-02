<script setup>
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import Slidebar from '@/Components/Slidebar.vue';

// Recebendo as props do componente pai
const props = defineProps({
  title: String,
});
</script>

<template>
  <Head :title="title" />
  <div class="h-screen flex flex-col md:flex-row">
    <!-- Sidebar -->
    <Slidebar />

    <!-- Content Area -->
    <main class="flex-1 bg-gray-100 p-6 overflow-y-auto">
      <slot />
    </main>
  </div>
</template>
