<!-- Compnet SalesPainel -->
<template>
  <div class="modal-wrapper" v-if="isVisible">
    <div class="content-wrapper">
      <section>
        <div class="grid grid-cols-1 lg:grid-cols-[450px_auto] gap-1 p-1">
          <!-- Card Esquerdo -->
          <div
            class="bg-white shadow-sm rounded-lg p-2 flex flex-col h-[calc(100vh-60px)]"
          >
            <div class="flex items-center space-x-3 shadow-sm">
              <div class="w-9 h-9 flex items-center justify-center">
                <i class="fa-solid fa-cart-shopping"></i>
              </div>
              <h4 class="text-xl font-bold">
                {{ mesaId ? 'Mesa ' + mesaId : 'Selecione uma mesa' }}
              </h4>
              <button @click="closePanel" class="btn btn-red mt-4">
                Fechar
              </button>
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
              @click="showPaymentMethodsModal = true"
            >
              Adicionar Forma de Pagamento
            </button>

            <!-- Modal de Adicionar Valor -->
            <PaymentMethods
              v-if="showPaymentMethodsModal"
              :subtotal="subtotal"
              :added-value="addedValue"
              :discount-value="discountValue"
              @save-payments="handlePaymentMethods"
              @close="showPaymentMethodsModal = false"
            />
          </div>

          <!-- Card Direito -->
          <div
            class="bg-white shadow-sm rounded-lg p-2 flex flex-col h-[calc(100vh-60px)]"
          >
            <ProductList
              :categories="categories"
              :cart-items="cartItems"
              @add-to-cart="addToCart"
            />
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded mt-1 w-full"
            >
              Salvar ({{ totalItems }} itens)
            </button>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import ProductList from '@/Components/ProductList.vue';
import { defineProps, ref, computed } from 'vue';
import DiscountModal from '@/Components/DiscountModal.vue';
import AddValueModal from '@/Components/AddValueModal.vue';
import ClearCartModal from '@/Components/ClearCartModal.vue';
import { useModalStore } from '@/store/store';
import PaymentMethods from './PaymentMethods.vue';

const modalStore = useModalStore();

const isVisible = computed(() => modalStore.isSalesPanelVisible);

// Variáveis reativas
const showDiscountModal = ref(false);
const showAddValueModal = ref(false);
const showPaymentMethodsModal = ref(false);
const showClearCartModal = ref(false);

const addedValue = ref(0);
const discountValue = ref(0);
const remainingAmount = ref(0);

const cartItems = ref([]);
const paymentMethodsSelected = ref([]);

const handlePaymentMethods = (selectedPayments) => {
  console.log('selectedPayments recebido no SalesPanel:', selectedPayments);

  // Verificar se 'methods' dentro de selectedPayments é um array
  if (Array.isArray(selectedPayments.methods)) {
    paymentMethodsSelected.value = selectedPayments.methods;

    // Calcular o valor total pago pelos métodos selecionados
    const totalPaid = selectedPayments.methods.reduce(
      (sum, payment) => sum + payment.amount,
      0
    );

    // Calcular o valor restante
    const totalToPay = totalWithDiscountAndAdd.value;
    remainingAmount.value = totalToPay - totalPaid;

    if (remainingAmount.value <= 0) {
      remainingAmount.value = 0;
    }

    // Log para verificar os valores
    console.log(
      'Métodos de pagamento selecionados:',
      paymentMethodsSelected.value
    );
    console.log('Total pago:', totalPaid);
    console.log('Valor restante:', remainingAmount.value);
  } else {
    console.error(
      'selectedPayments.methods não é um array:',
      selectedPayments.methods
    );
  }
};

const props = defineProps({
  mesaId: {
    type: Number,
    required: false,
  },
  products: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  filters: {
    type: Object,
    required: true,
  },
});

const closePanel = () => {
  resetFields();
  modalStore.closeSalesPanel();
};

const resetFields = () => {
  cartItems.value = [];
  addedValue.value = 0;
  discountValue.value = 0;
};

const clearCart = () => {
  cartItems.value = [];
  showClearCartModal.value = false;
};

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
  const valueInCents = Math.round(parseFloat(value) * 100);
  const validatedValue = valueInCents >= 0 ? valueInCents : 0;
  const finalValue = validatedValue / 100;

  discountValue.value = finalValue;
};

const applyAddedValue = (value) => {
  addedValue.value = Math.round(parseFloat(value) * 100); // Armazenar em centavos
};

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

const totalItems = computed(() =>
  cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
);

const saleDetails = computed(() => ({
  mesaId: props.mesaId,
  products: cartItems.value,
  payments: paymentMethodsSelected.value,
  total: totalWithDiscountAndAdd.value,
}));
</script>

<style scoped>
.modal-wrapper {
  position: fixed;
  top: 60px;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  overflow: hidden; /* Evita que a modal se estenda além da tela */
}

.content-wrapper {
  width: 100%;
  max-width: 100%; /* Largura máxima */
  overflow-y: auto; /* Adiciona rolagem se necessário */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 0px; /* Ajuste de padding conforme necessário */
  background: #fff;
  box-sizing: border-box;
}
</style>
