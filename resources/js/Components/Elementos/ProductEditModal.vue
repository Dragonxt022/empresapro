<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-center items-center"
  >
    <div class="bg-white p-6 rounded-lg shadow-xl w-96 max-w-sm relative">
      <!-- Fechar com X -->
      <button
        @click="closeModal"
        class="absolute top-4 right-4 text-gray-600 hover:text-gray-800 transition"
      >
        ×
      </button>

      <h3 class="text-xl font-semibold mb-4 text-center">Editar Produto</h3>
      <p class="mb-4 text-gray-600 text-center">{{ product.name }}</p>

      <!-- Quantidade e Lixeira -->
      <div class="flex items-center mb-4">
        <label class="text-gray-600 font-medium w-1/3">Quantidade</label>
        <div class="flex items-center w-2/3">
          <input
            type="number"
            v-model="quantity"
            min="1"
            class="border p-2 rounded w-20 text-center"
          />
          <button
            @click="removeProduct"
            class="ml-2 text-red-500 hover:text-red-700"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Botão de Aplicar -->
      <button
        @click="applyChanges"
        class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
      >
        Aplicar
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  isVisible: {
    type: Boolean,
    required: true,
  },
  product: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits();

const quantity = ref(props.product.quantity);

const closeModal = () => {
  emit('close');
};

const applyChanges = () => {
  emit('apply', { ...props.product, quantity: quantity.value });
  emit('close');
};

const removeProduct = () => {
  emit('remove', props.product.id);
  emit('close');
};
</script>

<style scoped>
/* Ajustes de estilo para o layout e o botão */
button {
  cursor: pointer;
}
</style>
