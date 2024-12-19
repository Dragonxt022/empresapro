<template>
  <AppLayout title="Caixa">
    <div class="content-wrapper">
      <section>
        <div class="grid grid-cols-1 lg:grid-cols-[450px_auto] gap-1 p-1">
          <!-- Card Esquerdo -->
          <div class="bg-white shadow-sm rounded-lg p-3 flex flex-col h-full">
            <div class="flex items-center space-x-3 shadow-sm">
              <div class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping"></i>
              </div>
              <h4 class="text-xl font-bold">Mesa 1</h4>
            </div>
            <div class="h-96 overflow-y-scroll mt-3 flex-grow">
              <div class="flex flex-col space-y-2">
                <div
                  v-for="item in formattedCartItems"
                  :key="item.id"
                  class="flex items-center border-b pb-1 mb-1"
                >
                  <div class="mr-2">
                    <p
                      class="w-7 h-7 rounded-lg bg-stone-400 flex items-center justify-center text-white"
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
            </div>

            <div>
              <div class="text-gray-600 flex justify-between">
                <p class="font-semibold">Subtotal</p>
                <p class="font-semibold text-gray-600">
                  {{ formattedSubtotal }}
                </p>
              </div>
              <div class="text-gray-600 flex justify-between">
                <p class="font-semibold text-warning">Desconto</p>
                <p class="font-semibold text-gray-600 text-warning">
                  {{ formattedDiscount }}
                </p>
              </div>
              <div class="text-gray-800 flex justify-between">
                <p class="text-xl font-bold">Total</p>
                <p class="font-semibold text-xl text-gray-800">
                  {{ formattedTotalWithDiscountAndAdd }}
                </p>
              </div>
              <div class="text-gray-600 flex justify-between">
                <p class="font-semibold">Restante</p>
                <!-- <p class="font-semibold text-gray-600">{{ remaining }}</p> -->
              </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-1">
              <button
                class="bg-blue-500 hover:bg-blue-600 text-white p-3 rounded-md flex items-center justify-center w-full"
              >
                <i class="fa-solid fa-print"></i>
              </button>
              <!-- Botão para Limpar Carrinho -->
              <button
                @click="showClearCartModal = true"
                class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-md flex items-center justify-center w-full"
              >
                <i class="fa-solid fa-trash"></i>
              </button>

              <!-- Modal de Confirmação para Limpar Carrinho -->
              <ClearCartModal
                v-if="showClearCartModal"
                @close="showClearCartModal = false"
                @clear-cart="clearCart"
              />

              <button
                @click="showDiscountModal = true"
                class="bg-yellow-500 hover:bg-yellow-600 text-white p-3 rounded-md flex items-center justify-center w-full"
              >
                <i class="fa-solid fa-dollar-sign mr-2"></i>
              </button>

              <!-- Modal de Desconto -->

              <DiscountModal
                v-if="showDiscountModal"
                @close="showDiscountModal = false"
                :cart-total="subtotal"
                @apply-discount="applyDiscount"
                :total-amount="formattedSubtotal"
              />
              <button
                @click="showAddValueModal = true"
                class="bg-green-500 hover:bg-green-600 text-white p-3 rounded-md flex items-center justify-center w-full"
              >
                <i class="fa-solid fa-dollar-sign mr-2"></i>
              </button>

              <!-- Modal de Adicionar Valor -->
              <AddValueModal
                v-if="showAddValueModal"
                @close="showAddValueModal = false"
                @apply-value="applyAddedValue"
              />
            </div>
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-6 w-full"
            >
              Adicionar Forma de Pagamento
            </button>
          </div>

          <!-- Card Direito -->
          <div class="bg-white shadow-sm rounded-lg">
            <ProductList
              :categories="categories"
              :cart-items="cartItems"
              @add-to-cart="addToCart"
            />
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded mt-6 w-full"
            >
              Salvar ({{ totalItems }} itens)
            </button>
          </div>
        </div>
      </section>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ProductList from '@/Components/ProductList.vue';
import { defineProps, ref, computed } from 'vue';
import DiscountModal from '@/Components/DiscountModal.vue';
import AddValueModal from '@/Components/AddValueModal.vue';
import ClearCartModal from '@/Components/ClearCartModal.vue';

// Variáveis reativas
const showDiscountModal = ref(false); // Controle da modal de desconto
const showAddValueModal = ref(false); // Controle da modal de adicionar valor

const showClearCartModal = ref(false); // Controle da modal de limpeza

const addedValue = ref(0); // Valor adicionado ao total
const discountValue = ref(0); // Valor do desconto em centavos

const cartItems = ref([]); // Itens do carrinho

// Props do Laravel
const props = defineProps({
  products: { type: Object, required: true },
  categories: { type: Array, required: true },
  filters: { type: Object, required: true },
});

// Função para limpar o carrinho
const clearCart = () => {
  cartItems.value = []; // Limpar itens do carrinho
  showClearCartModal.value = false; // Fechar a modal
};
// Adicionar produto ao carrinho
const addToCart = (product) => {
  const priceInCents = Math.round(parseFloat(product.price) * 100) || 0;
  const existingProduct = cartItems.value.find(
    (item) => item.id === product.id
  );

  if (existingProduct) {
    existingProduct.quantity += 1;
    existingProduct.totalPrice += priceInCents;
  } else {
    cartItems.value.push({
      id: product.id,
      name: product.name,
      quantity: 1,
      totalPrice: priceInCents,
    });
  }
};

const applyDiscount = (value) => {
  // Converter o valor para centavos
  const valueInCents = Math.round(parseFloat(value) * 100);

  // Garantir que o desconto seja válido (maior ou igual a zero)
  const validatedValue = valueInCents >= 0 ? valueInCents : 0;

  // Converter novamente para reais se necessário
  const finalValue = validatedValue / 100;

  discountValue.value = finalValue;
};

// Função para aplicar o valor adicionado
const applyAddedValue = (value) => {
  addedValue.value = Math.round(parseFloat(value) * 100); // Armazenar em centavos
};

// Função para formatar valores em reais
const formatCurrency = (valueInCents) => {
  const valueInReais = valueInCents / 100;
  return valueInReais.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  });
};

// Computed: Subtotal (sem desconto)
const subtotal = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.totalPrice, 0)
);

// Computed: Total com desconto aplicado
const totalWithDiscount = computed(() => {
  const total = subtotal.value - discountValue.value;
  return total >= 0 ? total : 0;
});

// Computed: Total com desconto e valor acrescido
const totalWithDiscountAndAdd = computed(() => {
  return totalWithDiscount.value + addedValue.value;
});

// Computed: Formatação de valores
const formattedSubtotal = computed(() => formatCurrency(subtotal.value));
const formattedDiscount = computed(() => formatCurrency(discountValue.value));
const formattedTotalWithDiscount = computed(() =>
  formatCurrency(totalWithDiscount.value)
);
const formattedTotalWithDiscountAndAdd = computed(() =>
  formatCurrency(totalWithDiscountAndAdd.value)
);

// Computed: Itens do carrinho formatados
const formattedCartItems = computed(() =>
  cartItems.value.map((item) => ({
    ...item,
    formattedPrice: formatCurrency(item.totalPrice),
  }))
);

// Total de itens no carrinho
const totalItems = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
);
</script>

<style scoped>
/* Adicione estilos personalizados, se necessário */
</style>
