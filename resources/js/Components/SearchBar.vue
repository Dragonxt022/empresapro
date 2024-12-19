<template>
  <div class="mb-4">
    <input
      v-model="searchTerm"
      type="text"
      placeholder="Pesquisar por nome ou código de barras"
      class="border border-gray-300 rounded-lg w-full p-2"
      @input="onSearch"
    />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

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
