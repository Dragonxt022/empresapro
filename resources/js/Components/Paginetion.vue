<template>
    <div class="flex flex-wrap justify-center gap-2 mt-4">

        <!-- Botões de páginas -->
        <div class="flex flex-wrap gap-2">
            <button
                v-for="(link, index) in links.filter(link => link.label && link.url)"
                :key="index"
                @click="navegarPagina(link.url)"
                :class="{
                    'px-4 py-2 text-gray-700 rounded-lg text-sm sm:text-base': true,
                    'bg-blue-500 text-white': link.active,
                    'bg-gray-200': !link.active,
                    'cursor-not-allowed opacity-50': !link.url
                }"
                :disabled="!link.url"
            >
                <!-- Usando v-html para renderizar o conteúdo com HTML -->
                <span v-html="link.label"></span>
            </button>
        </div>

    </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

// Definir as props recebidas pelo componente
const props = defineProps({
    links: {
        type: Array,
        required: true,
    },
});

// Emitir evento para navegação
const emit = defineEmits();

const navegarPagina = (url) => {
    // Emite o evento de navegação para o componente pai
    emit('navegar', url);
};
</script>
