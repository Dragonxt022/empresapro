<template>
  <div class="mb-1 w-full">
    <!-- Input de pesquisa -->
    <input
      v-model="searchTerm"
      type="text"
      placeholder="Realizar busca..."
      class="border border-gray-300 rounded-lg w-full p-3 pl-6 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 ease-in-out"
      @input="onSearch"
    />
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
