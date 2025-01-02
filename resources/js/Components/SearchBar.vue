<template>
  <div class="relative mb-1 w-full">
    <!-- Input de pesquisa -->
    <input
      v-model="searchTerm"
      type="text"
      placeholder="     Pesquisar por nome ou código de barras"
      class="border border-gray-300 rounded-lg w-full p-3 pl-5 pr-4 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 ease-in-out"
      @input="onSearch"
    />
    <!-- Ícone de pesquisa -->
    <i
      class="fa-solid fa-search absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-500"
    ></i>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Estado para armazenar o termo de pesquisa
const searchTerm = ref('');

// Emitir o evento de pesquisa sempre que o input mudar
const emit = defineEmits(['search']);

const onSearch = () => {
  emit('search', searchTerm.value);
};
</script>

<style scoped>
/* Personalize o estilo da barra de pesquisa, se necessário */
</style>
