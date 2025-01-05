<template>
  <div class="space-y-4">
    <!-- Data Início -->
    <div class="flex items-center space-x-4">
      <label
        for="data_inicio"
        class="text-sm font-semibold text-gray-700 w-1/3"
      >
        Data Início
      </label>
      <input
        id="data_inicio"
        type="date"
        v-model="dataInicio"
        class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-2/3"
      />
    </div>

    <!-- Data Fim -->
    <div class="flex items-center space-x-4">
      <label for="data_fim" class="text-sm font-semibold text-gray-700 w-1/3">
        Data Fim
      </label>
      <input
        id="data_fim"
        type="date"
        v-model="dataFim"
        class="p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-2/3"
      />
    </div>

    <!-- Aviso sobre o limite de 90 dias -->
    <p class="text-sm text-red-500 mt-5 text-3xl text-center">
      * Registros só serão rastreados nos últimos 90 dias.
      <br />
      Para mais informações, entre em contato com o suporte.
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const dataInicio = ref('');
const dataFim = ref('');
const emit = defineEmits(['atualizarFiltro']);

const aplicarFiltro = () => {
  emit('atualizarFiltro', {
    data_inicio: dataInicio.value,
    data_fim: dataFim.value,
  });
};
</script>

<style scoped>
/* Estilos adicionais, se necessário */
input {
  font-size: 1rem;
  padding-left: 0.75rem;
}
</style>
