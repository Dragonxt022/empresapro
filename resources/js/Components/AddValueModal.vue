<template>
  <div class="fixed inset-0 flex items-center justify-center bg-gray-900">
    <div class="bg-white p-6 rounded-md shadow-md text-center">
      <h2 class="text-lg font-semibold mb-4">Adicionar Valor</h2>
      <p>Insira o valor a ser adicionado:</p>
      <InputNumber
        v-model="valueInput"
        type="number"
        min="0"
        class="border w-full mt-2"
        placeholder="Ex: 10,00"
      />
      <div class="flex justify-between mt-4">
        <button
          @click="$emit('close')"
          class="bg-gray-300 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-300"
        >
          Cancelar
        </button>
        <button
          @click="applyValue"
          class="bg-green-500 text-white px-4 py-2 rounded-md"
        >
          Adicionar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import InputNumber from './InputNumber.vue';

const valueInput = ref(0); // Input do usuário para adicionar valor

// Emitir o valor a ser adicionado
const applyValue = () => {
  const value = parseFloat(valueInput.value) || 0;
  const valueInReais = value / 100; // Converter de centavos para reais
  if (value >= 0) {
    emit('apply-value', valueInReais); // Emitir em reais
    emit('close');
  }
};

const emit = defineEmits(['close', 'apply-value']);
</script>

<style scoped>
.fixed {
  z-index: 9999;
}

.bg-gray-900 {
  background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
}
</style>
