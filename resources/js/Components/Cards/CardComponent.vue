<script setup>
import { defineProps } from 'vue';

defineProps({
    icon: { type: String, required: true }, // Classe do ícone, exemplo: 'fas fa-cog'
    title: { type: String, required: true }, // Texto do título
    bgColor: { type: String, default: 'bg-gray-200' }, // Cor do fundo do card
    textColor: { type: String, default: 'text-blue-500' }, // Cor do texto
    shadow: { type: Boolean, default: true }, // Se o card terá sombra
    titleSize: { type: String, default: 'text-lg' }, // Tamanho da fonte do título
});
</script>

<template>
    <div
        :class="[
            'flex flex-col items-center justify-center p-6 rounded-lg transform transition-all duration-300',
            bgColor,
            textColor,
            shadow ? 'shadow-lg' : ''
        ]"
        class="hover:translate-y-[-4px] hover:bg-blue-100"
    >
        <div class="text-5xl mb-4">
            <i :class="icon"></i> <!-- Ícone -->
        </div>
        <p :class="[titleSize, 'font-bold']">{{ title }}</p> <!-- Título -->
    </div>
</template>

<style scoped>
/* Adiciona uma transição suave ao passar o mouse */
div:hover {
    cursor: pointer;
}
</style>
