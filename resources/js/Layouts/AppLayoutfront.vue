<script setup>
import { defineProps } from 'vue';
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Slidebar from '@/Components/Slidebar.vue';

// Recebendo as props do componente pai
const props = defineProps({
    title: String,

});
</script>

<template>
    <Head :title="title" />
    <div class="wrapper">
        <Navbar />
        <!-- Passando o user para o Slidebar -->
        <Slidebar/>
        <slot />
    </div>
</template>
