<!-- Compnet SalesPainel -->
<template>
  <div class="modal-wrapper" v-if="isVisible">
    <div class="content-wrapper">
      <section>
        <div
          class="bg-blue-100 grid grid-cols-1 lg:grid-cols-[450px_auto] gap-1"
        >
          <!-- Card Esquerdo -->
          <div class="bg-white shadow-sm rounded-lg p-2 flex flex-col h-full">
            <div class="flex items-center space-x-3 shadow-sm p-1 mb-2">
              <div
                class="w-9 h-9 flex items-center justify-center text-sky-500"
              >
                <i class="fa-solid fa-cart-shopping"></i>
              </div>
              <h4 class="text-xl font-bold text-sky-500">
                {{ mesaNome || 'Selecione uma mesa' }}
              </h4>
              <div class="flex justify-end flex-grow">
                <button
                  @click="isFecharModalOpen = true"
                  class="rounded-full p-2 bg-red-500 hover:bg-red-600 text-white font-bold flex items-center space-x-2"
                >
                  Voltar
                </button>
              </div>
            </div>
            <div v-if="isLoading" class="loading-overlay">
              <div class="spinner"></div>
            </div>

            <!-- Carrinho -->
            <Cart
              :formattedCartItems="cartItems"
              @updateCart="updateCartItems"
            />

            <div class="p-2 mb-2">
              <div class="text-gray-600 flex justify-between">
                <p class="font-semibold">Subtotal</p>
                <p class="font-semibold text-gray-600">
                  {{ formattedSubtotal }}
                </p>
              </div>
              <div class="text-gray-600 flex justify-between">
                <p class="font-semibold">Desconto</p>
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
                @click="isDeleteModalOpen = true"
                class="bg-red-500 hover:bg-red-600 text-white p-3 rounded-md flex items-center justify-center w-full"
              >
                <i class="fa-solid fa-trash"></i>
              </button>

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

            <div v-if="paymentMethodsSelected.length === 0">
              <button
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 w-full"
                @click="showPaymentMethodsModal = true"
              >
                Adicionar Forma de Pagamento
              </button>
            </div>

            <div v-else class="flex space-x-2 mt-6">
              <button
                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded w-full"
                @click="showPaymentMethodsModal = true"
              >
                Alterar Pagamentos
              </button>
              <button
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded w-full"
                @click="ConfirmarPagamento = true"
              >
                Finalizar Pagamento
              </button>
            </div>

            <!-- Modal de Confirmação -->
            <ConfirmModal
              v-if="showConfirmationModal"
              @confirm="saveSale"
              @close="showConfirmationModal = false"
            />

            <!-- Modal de Confirmação -->
            <ConfirmModalPagamento
              v-if="ConfirmarPagamento"
              @confirm="finalizeSale"
              @close="ConfirmarPagamento = false"
            />

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
            class="bg-white shadow-sm rounded-lg p-2 flex flex-col h-[calc(100vh)]"
          >
            <ProductList
              :categories="categories"
              :cart-items="cartItems"
              @add-to-cart="addToCart"
            />
            <button
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded mt-1 w-full"
              @click="showConfirmationModal = true"
            >
              Salvar ({{ totalItems }} itens)
            </button>
          </div>
        </div>
      </section>
    </div>

    <!-- Modal de Confirmação -->
    <transition name="fade">
      <div
        v-if="isDeleteModalOpen"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-center items-center"
      >
        <!-- Modal Content -->
        <div
          class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
        >
          <h3 class="text-xl font-semibold mb-4 text-center">
            Tem certeza que deseja excluir esta venda?
          </h3>
          <p class="mb-6 text-gray-600">Essa ação não pode ser desfeita.</p>
          <div class="flex justify-between">
            <button
              @click="excluirVenda(mesaId)"
              class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 w-full mr-2"
            >
              Comfirmar
            </button>
            <button
              @click="isDeleteModalOpen = false"
              class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 w-full"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </transition>

    <transition name="fade">
      <div
        v-if="isFecharModalOpen"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-center items-center"
      >
        <!-- Modal Content -->
        <div
          class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
        >
          <h3 class="text-xl font-semibold mb-4 text-center">
            Tem certeza que deseja sair sem salvar esta venda?
          </h3>
          <p class="mb-6 text-gray-600">Essa ação não pode ser desfeita.</p>
          <div class="flex justify-between">
            <button
              @click="closePanel()"
              class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 w-full mr-2"
            >
              Comfirmar
            </button>
            <button
              @click="isFecharModalOpen = false"
              class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 w-full"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import ProductList from '@/Components/ProductList.vue';
import { defineProps, ref, computed, watch, onMounted } from 'vue';
import DiscountModal from '@/Components/DiscountModal.vue';
import AddValueModal from '@/Components/AddValueModal.vue';
import { useModalStore } from '@/store/store';
import PaymentMethods from './PaymentMethods.vue';
import ConfirmModal from './Elementos/ConfirmModal.vue';
import { notify } from '@/Plugins/Notify';
import axios from 'axios';
import ConfirmModalPagamento from './Elementos/ConfirmModalPagamento.vue';
import Cart from './Elementos/Cart.vue';

const modalStore = useModalStore();

const emit = defineEmits();

const isVisible = computed(() => modalStore.isSalesPanelVisible);

// Variáveis reativas
const showDiscountModal = ref(false);
const showAddValueModal = ref(false);
const showPaymentMethodsModal = ref(false);

const isDeleteModalOpen = ref(false);
const isFecharModalOpen = ref(false);

const addedValue = ref(0);
const discountValue = ref(0);
const remainingAmount = ref(0);

const isLoading = ref(false);

const cartItems = ref([]);
const paymentMethodsSelected = ref([]);

const showConfirmationModal = ref(false);
const ConfirmarPagamento = ref(false);

const mesaId = computed(() => props.MesaSelecionada?.id || null);
const mesaNome = computed(() => props.MesaSelecionada?.nome || 'Sem Nome');

const handlePaymentMethods = (selectedPayments) => {
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

    saveSalePagamento();
  } else {
    console.error(
      'selectedPayments.methods não é um array:',
      selectedPayments.methods
    );
  }
};

const updateCartItems = (updatedCartItems) => {
  cartItems.value = updatedCartItems; // Atualiza os itens do carrinho no SalesPanel
};

const props = defineProps({
  MesaSelecionada: {
    type: Object,
    required: true,
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

watch(
  () => props.mesaId,
  (newMesaId) => {
    if (newMesaId) {
      console.log(`Mesa ID mudou para: ${newMesaId}`);
    }
  }
);

watch(isVisible, (newValue) => {
  if (newValue) {
    // Quando o painel de vendas for visível, carregue os detalhes da venda
    if (props.MesaSelecionada.id) {
      fetchSaleDetails(props.MesaSelecionada.id);
    }
  }
});

// Função para buscar a venda associada à mesa
const fetchSaleDetails = async (mesaId) => {
  try {
    isLoading.value = true;
    const response = await axios.get(`/api/vendas/${mesaId}`);

    if (response.data.success) {
      const venda = response.data.venda;

      // Preenchendo os dados da venda no painel
      cartItems.value = venda.produtos.map((product) => ({
        id: product.product_id, // Alterado para product_id ao invés de product.id
        name: product.nome,
        quantity: product.quantidade,
        totalPrice: (product.valor_total / 100) * 100, // Armazenando em centavos
      }));
      paymentMethodsSelected.value = venda.pagamentos.map((payment) => ({
        method: payment.metodo,
        amount: (payment.valor / 100) * 100, // Armazenando em centavos
      }));
      discountValue.value = (venda.desconto / 100) * 100; // Armazenando em centavos
      addedValue.value = (venda.acrescimo / 100) * 100; // Armazenando em centavos
      remainingAmount.value =
        (venda.valor_total / 100) * 100 - totalWithDiscountAndAdd.value;
    } else {
      console.error('Essa mesa ainda não foi salva!');
    }
  } catch (error) {
    console.error('Essa mesa ainda não foi salva!');
  } finally {
    isLoading.value = false;
  }
};

const closePanel = () => {
  isFecharModalOpen.value = false;
  resetFields();
  emit('updateMesa');
  modalStore.closeSalesPanel();
};

const resetFields = () => {
  cartItems.value = [];
  paymentMethodsSelected.value = [];
  addedValue.value = 0;
  discountValue.value = 0;
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

const saveSale = async () => {
  const saleData = {
    mesaId: props.MesaSelecionada.id,
    products: cartItems.value,
    total: totalWithDiscountAndAdd.value,
  };

  try {
    const response = await axios.post('/api/vendas/salvar', saleData);
    if (response.data.success) {
      notify.success('Venda salva com sucesso!');

      closePanel();
    }
  } catch (error) {
    notify.error(error.response?.data?.message || 'Erro ao salvar a venda.');
  }
};

const saveSalePagamento = async () => {
  const saleData = {
    mesaId: props.MesaSelecionada.id,
    products: cartItems.value,
    total: totalWithDiscountAndAdd.value,
  };

  try {
    const response = await axios.post('/api/vendas/salvar', saleData);
    if (response.data.success) {
      emit('updateMesa');
    }
  } catch (error) {
    notify.error(error.response?.data?.message || 'Erro ao salvar a venda.');
  }
};

const finalizeSale = async () => {
  const saleData = {
    mesaId: props.MesaSelecionada.id,
    products: cartItems.value,
    payments: paymentMethodsSelected.value,
  };

  try {
    const response = await axios.post(
      `/api/vendas/finalizar/${props.mesaId}`,
      saleData
    );
    if (response.data.success) {
      notify.success('Venda finalizada com sucesso!');
      emit('updateMesa');
      closePanel();
    }
  } catch (error) {
    notify.error(error.response?.data?.message || 'Erro ao finalizar a venda.');
  }
};

const excluirVenda = async (vendaId) => {
  try {
    isLoading.value = true;

    // Chamada à API para excluir a venda
    const response = await axios.delete(`/api/vendas/${vendaId}/excluir`);

    if (response.data.success) {
      notify.success(response.data.message);

      // Atualizar o estado local ou emitir evento para atualizar a lista de mesas
      emit('updateMesa');
      closePanel(); // Fecha o painel da venda, se necessário
    } else {
      notify.error(response.data.message || 'Erro ao excluir a venda.');
    }
  } catch (error) {
    notify.error(
      error.response?.data?.message || 'Erro inesperado ao excluir a venda.'
    );
  } finally {
    isLoading.value = false;
  }
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
</script>

<style scoped>
.modal-wrapper {
  position: fixed;
  width: 100%;
  height: 100vh;
  top: 0;
  left: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
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

.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

/* Estilos para o spinner */
.spinner {
  border: 4px solid #f3f3f3; /* Cor de fundo */
  border-top: 4px solid #3498db; /* Cor do topo */
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 2s linear infinite;
}

/* Animação do spinner */
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
