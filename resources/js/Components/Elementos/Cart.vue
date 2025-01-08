<template>
  <div class="h-96 overflow-y-scroll mt-3 flex-grow">
    <div
      v-if="formattedCartItems.length > 0"
      class="bg-yellow-400 mb-2 text-center font-bold text-gray-100"
    >
      NOVOS
    </div>

    <div class="flex flex-col space-y-2 font-semibold">
      <div
        v-for="item in formattedCartItems"
        :key="item.id"
        class="flex items-center pb-1 mb-1 cursor-pointer"
        @click="openProductModal(item)"
      >
        <div class="mr-2">
          <p
            class="w-7 h-7 rounded-lg bg-blue-400 flex items-center justify-center text-white"
          >
            {{ item.quantity }}
          </p>
        </div>

        <p class="text-gray-500 flex-grow font-weight-bold">
          {{ item.name }}
        </p>
        <div class="flex items-center space-x-2 flex-shrink-0">
          <p class="text-gray-500 font-weight-bold">
            {{ item.formattedPrice }}
          </p>
        </div>
      </div>
    </div>

    <ProductEditModal
      v-if="isProductModalVisible"
      :product="selectedProduct"
      :isVisible="isProductModalVisible"
      @close="isProductModalVisible = false"
      @apply="applyProductChanges"
      @remove="removeProduct"
    />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import ProductEditModal from './ProductEditModal.vue';
import { defineProps, defineEmits } from 'vue';

// Função de formatação de moeda, convertendo centavos para reais
const formatCurrency = (value) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  }).format(value / 100); // Convertendo para reais
};

const props = defineProps({
  formattedCartItems: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits();

const isProductModalVisible = ref(false);
const selectedProduct = ref(null);

// Computed para formatar e calcular o total de cada item
const formattedCartItems = computed(() =>
  props.formattedCartItems.map((item) => {
    // Verifica se o valor de price está presente e se totalPrice está sendo usado corretamente
    const totalPrice = item.totalPrice || 0; // Caso não tenha totalPrice, usa 0
    const formattedPrice = formatCurrency(totalPrice); // Formata o preço total para reais
    return {
      ...item,
      totalPriceFormatted: formattedPrice, // Formata o total para reais
      formattedPrice: formattedPrice, // Formata o preço unitário para reais
    };
  })
);

const openProductModal = (product) => {
  selectedProduct.value = product;
  isProductModalVisible.value = true;
};

const applyProductChanges = (updatedProduct) => {
  const index = props.formattedCartItems.findIndex(
    (item) => item.id === updatedProduct.id
  );
  if (index !== -1) {
    props.formattedCartItems[index] = updatedProduct;
    emit('updateCart', props.formattedCartItems); // Emite a atualização para o SalesPanel
  }
};

const removeProduct = (productId) => {
  const updatedCartItems = props.formattedCartItems.filter(
    (item) => item.id !== productId
  );
  emit('updateCart', updatedCartItems); // Emite a atualização para o SalesPanel
};
</script>
