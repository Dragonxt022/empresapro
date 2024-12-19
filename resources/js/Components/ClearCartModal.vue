<template>
  <!-- Modal de Confirmação para Limpar o Carrinho -->
  <div
    v-if="showClearCartModal"
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
  >
    <div class="bg-white p-6 rounded-md shadow-md text-center">
      <h2 class="text-lg font-semibold mb-4">
        Tem certeza que deseja limpar o carrinho?
      </h2>
      <div class="flex justify-between mt-4">
        <button
          @click="cancelClearCart"
          class="bg-gray-300 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-300"
        >
          Cancelar
        </button>
        <button
          @click="clearCart"
          class="bg-red-500 text-white px-4 py-2 rounded-md"
        >
          Limpar Carrinho
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const showClearCartModal = ref(false); // Controle da exibição da modal

// Função para limpar o carrinho
const clearCart = () => {
  // Emitir evento de limpar carrinho
  emit('clear-cart'); // Esse evento será tratado no componente pai
  showClearCartModal.value = false; // Fechar a modal
};

// Função para cancelar e fechar a modal
const cancelClearCart = () => {
  showClearCartModal.value = false; // Apenas fechar a modal sem limpar o carrinho
};
</script>

<style scoped>
.fixed {
  z-index: 9999;
}

.bg-gray-900 {
  background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
}
</style>
