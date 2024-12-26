<template>
  <div class="fixed inset-0 payment-methods bg-modal rounded-lg p-4">
    <div
      class="bg-white p-6 rounded-md shadow-md text-center tamanho-modal flex flex-col"
    >
      <h3 class="text-lg font-bold mb-4">Adicionar Forma de Pagamento</h3>

      <!-- Botões de seleção de métodos -->
      <div class="grid grid-cols-4 gap-2 mb-4">
        <button
          v-for="method in paymentMethods"
          :key="method.name"
          :disabled="isMethodDisabled(method.name)"
          :class="[
            'py-2 px-4 rounded',
            selectedMethod === method.name
              ? 'bg-blue-500 text-white'
              : 'bg-gray-200 text-gray-700',
            isMethodDisabled(method.name)
              ? 'opacity-50 cursor-not-allowed'
              : '',
          ]"
          @click="addPaymentMethod(method)"
        >
          {{ method.label }}
        </button>
      </div>

      <!-- Lista de pagamentos -->
      <div class="mb-4">
        <h4 class="text-sm font-semibold mb-2">Pagamentos Adicionados</h4>
        <ul>
          <li
            v-for="(value, methodKey) in paymentValues"
            :key="methodKey"
            class="flex justify-between items-center bg-gray-100 p-2 rounded mb-2"
          >
            <!-- Exibir ID e Nome -->
            <span>
              {{ methodKey }} - {{ getPaymentMethodLabel(methodKey) }}:
            </span>

            <!-- Input editável formatado em Real -->
            <input
              v-model="formattedPaymentValues[methodKey]"
              @blur="updatePaymentValue(methodKey)"
              type="text"
              class="border border-gray-300 rounded w-24 p-2 text-right"
            />

            <!-- Botão de remover -->
            <button
              class="text-red-500 hover:text-red-700"
              @click="removePaymentMethod(methodKey)"
            >
              <i class="fa-solid fa-trash"></i>
            </button>
          </li>
        </ul>
      </div>

      <!-- Resumo financeiro -->
      <div class="text-sm mb-4">
        <div class="flex justify-between">
          <span>Subtotal:</span>
          <span>{{ formatCurrency(subtotal) }}</span>
        </div>
        <div class="flex justify-between text-green-500">
          <span>Acréscimo:</span>
          <span>{{ formatCurrency(addedValue) }}</span>
        </div>
        <div class="flex justify-between text-yellow-500">
          <span>Desconto:</span>
          <span>{{ formatCurrency(discountValue) }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg">
          <span>Restante:</span>
          <span>{{ formatCurrency(remaining) }}</span>
        </div>
        <div v-if="remaining === 0" class="text-green-600 font-semibold mt-2">
          Pagamento completo!
        </div>
      </div>

      <!-- Área de Botões -->
      <div class="flex justify-between mt-auto">
        <button
          class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded mr-2 w-full"
          @click="$emit('close')"
        >
          Cancelar
        </button>
        <button
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded w-full"
          @click="savePayments"
          :disabled="remaining !== 0"
        >
          Salvar pagamento
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const paymentMethods = ref([]); // Métodos disponíveis
const paymentValues = ref({}); // Valores de pagamento por método
const formattedPaymentValues = ref({}); // Valores formatados (R$)
const selectedMethod = ref('');

const props = defineProps({
  subtotal: {
    type: Number,
    default: 0,
  },
  addedValue: {
    type: Number,
    default: 0,
  },
  discountValue: {
    type: Number,
    default: 0,
  },
});

// Função para carregar métodos de pagamento
const loadPaymentMethods = async () => {
  try {
    const response = await axios.get('/api/paymentMethod');

    // Mapeia os métodos de pagamento para o formato esperado
    paymentMethods.value = response.data.map((method) => ({
      id: method.id, // ID do método
      name: method.id.toString(), // Nome único usado como key
      label: method.name, // Nome amigável exibido na interface
    }));

    console.log(paymentMethods.value); // Verificar dados carregados para depuração
  } catch (error) {
    console.error('Erro ao carregar métodos de pagamento:', error);
  }
};

onMounted(() => {
  loadPaymentMethods();
});

// Adiciona um método de pagamento
const addPaymentMethod = (method) => {
  if (!paymentValues.value[method.name] && remaining.value > 0) {
    // Define o valor restante para o novo método
    const newValue = remaining.value;
    paymentValues.value[method.name] = newValue;
    formattedPaymentValues.value[method.name] = formatCurrency(newValue);
    selectedMethod.value = method.name;
  }
};

// Remove um método de pagamento
const removePaymentMethod = (methodKey) => {
  delete paymentValues.value[methodKey];
  delete formattedPaymentValues.value[methodKey];
  selectedMethod.value = null;
};

// Atualiza o valor editado no input
const updatePaymentValue = (methodKey) => {
  const parsedValue = parseCurrency(formattedPaymentValues.value[methodKey]);
  const maxAllowed = remaining.value + (paymentValues.value[methodKey] || 0);
  paymentValues.value[methodKey] = Math.min(parsedValue, maxAllowed);
  formattedPaymentValues.value[methodKey] = formatCurrency(
    paymentValues.value[methodKey]
  );
};

// Retorna o rótulo do método de pagamento
const getPaymentMethodLabel = (methodKey) => {
  const method = paymentMethods.value.find((m) => m.name === methodKey);
  return method ? method.label : methodKey;
};

// Calcula o valor restante
const remaining = computed(() => {
  const totalPaid = Object.values(paymentValues.value).reduce(
    (sum, value) => sum + value,
    0
  );
  return Math.max(
    props.subtotal + props.addedValue - props.discountValue - totalPaid,
    0
  );
});

// Desabilita métodos que já foram selecionados ou quando não há valor restante
const isMethodDisabled = (methodKey) => {
  return !!paymentValues.value[methodKey] || remaining.value <= 0;
};

// Formata valor em moeda brasileira
const formatCurrency = (valueInCents) => {
  const valueInReais = valueInCents / 100;
  return valueInReais.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL',
  });
};

// Converte string formatada em moeda para centavos
const parseCurrency = (formattedValue) => {
  const numericValue = formattedValue.replace(/[^0-9,]/g, '').replace(',', '.');
  return Math.round(parseFloat(numericValue) * 100) || 0;
};

// Emite os dados de pagamento
const savePayments = () => {
  $emit('save-payments', {
    methods: paymentValues.value,
    totalPaid: props.subtotal + props.addedValue - props.discountValue,
  });
};
</script>

<style lang="css" scoped>
.payment-methods {
  z-index: 9999;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.6); /* Fundo escurecido */
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1rem;
}
.tamanho-modal {
  width: 100%;
  max-width: 600px; /* Tamanho máximo para dispositivos grandes */
  background-color: #ffffff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); /* Sombra para destacar */
  display: flex;
  flex-direction: column;
  padding: 1.5rem;
}
.modal-header {
  border-bottom: 1px solid #e5e5e5;
  padding-bottom: 1rem;
  margin-bottom: 1rem;
}
.modal-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #333333;
}
.payment-buttons {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); /* Responsivo */
  gap: 0.5rem;
  margin-bottom: 1rem;
}
.payment-buttons button {
  font-size: 0.875rem;
  padding: 0.5rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}
.payment-buttons button:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}
.payment-buttons button.bg-blue-500 {
  background-color: #2563eb; /* Azul mais profissional */
  color: white;
}
.payment-buttons button.bg-gray-200 {
  background-color: #f3f4f6;
  color: #374151;
}
.payment-buttons button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.payment-list {
  margin-bottom: 1.5rem;
}
.payment-list ul {
  list-style: none;
  padding: 0;
}
.payment-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1rem;
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  margin-bottom: 0.5rem;
}
.payment-list li span {
  font-size: 0.875rem;
  color: #374151;
}
.payment-list li input {
  font-size: 0.875rem;
  text-align: right;
  border: 1px solid #d1d5db;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
}
.payment-list li button {
  background: none;
  color: #ef4444;
  border: none;
  font-size: 1rem;
  cursor: pointer;
}
.payment-summary {
  font-size: 0.875rem;
  color: #374151;
}
.payment-summary div {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}
.payment-summary div span:first-child {
  color: #6b7280;
}
.payment-summary div span:last-child {
  font-weight: 600;
}
.payment-summary .text-green-500 {
  color: #10b981; /* Verde para valores positivos */
}
.payment-summary .text-yellow-500 {
  color: #f59e0b; /* Amarelo para descontos */
}
.payment-summary .font-bold {
  font-size: 1rem;
}
.modal-footer {
  display: flex;
  justify-content: space-between;
  margin-top: auto;
}
.modal-footer button {
  font-size: 0.875rem;
  padding: 0.5rem 1rem;
  border-radius: 8px;
  font-weight: 600;
}
.modal-footer button.bg-gray-300 {
  background-color: #d1d5db;
  color: #374151;
  transition: all 0.3s ease;
}
.modal-footer button.bg-gray-300:hover {
  background-color: #9ca3af;
}
.modal-footer button.bg-green-500 {
  background-color: #10b981;
  color: white;
}
.modal-footer button.bg-green-500:hover {
  background-color: #059669;
}
</style>
