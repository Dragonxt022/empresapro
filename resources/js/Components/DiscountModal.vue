<template>
  <div class="fixed inset-0 flex items-center justify-center bg-gray-900">
    <div class="bg-white p-6 rounded-md shadow-md text-center">
      <h2 class="text-lg font-semibold mb-4">Aplicar Desconto</h2>
      <p>Insira o valor do desconto:</p>
      <InputNumber
        v-model="discountInput"
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
          @click="applyDiscount"
          class="bg-green-500 text-white px-4 py-2 rounded-md"
        >
          Aplicar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import InputNumber from './InputNumber.vue';
import { notify } from '@/Plugins/notify';

// Input do desconto inicializado
const discountInput = ref(0);

// Receber o valor total do carrinho como prop
const props = defineProps({
  cartTotal: { type: Number, required: true }, // Total do carrinho em centavos
});

// Emitir os eventos de fechar e aplicar desconto
const emit = defineEmits(['close', 'apply-discount']);

const applyDiscount = () => {
  // Verificar se o carrinho está vazio
  if (!props.cartTotal || props.cartTotal <= 0) {
    notify.error('Carrinho vazio, não é possível aplicar desconto.');
    return; // Não faz nada
  }

  // Desconto já está em centavos
  const discountInCents = parseInt(discountInput.value) || 0;

  // Verificar se o desconto é válido
  if (discountInCents > 0 && discountInCents <= props.cartTotal) {
    emit('apply-discount', discountInCents); // Emitir o desconto válido
    emit('close'); // Fechar a modal
  } else {
    notify.warning('Desconto inválido ou maior que o total do carrinho.');
  }
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
