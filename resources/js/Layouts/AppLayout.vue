<script setup>
import { defineProps, defineAsyncComponent } from 'vue';

import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Slidebar from '@/Components/Slidebar.vue';
import Notification2 from '@/Components/Notification2.vue';


// Recebendo as props do componente pai
const props = defineProps({
    title: String,

});
</script>

<template>
    <Head :title="title" />
    <Notification2 />
    <div class="wrapper">
        <Navbar />
        <!-- Passando o user para o Slidebar -->
        <Slidebar/>
        <slot />
    </div>
</template>
