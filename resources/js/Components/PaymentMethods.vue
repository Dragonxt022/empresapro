<template>
  <div
    class="fixed inset-0 payment-methods bg-modal flex items-center justify-center p-4"
  >
    <div
      class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl transform transition-all duration-300 ease-in-out animate-modal"
    >
      <h3 class="text-xl font-bold mb-6 text-gray-800 text-center">
        Adicionar Forma de Pagamento
      </h3>

      <!-- Botões de seleção de métodos -->
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 mb-6">
        <button
          v-for="method in paymentMethods"
          :key="method.name"
          :disabled="isMethodDisabled(method.name)"
          :class="[
            'py-2 px-4 rounded-md text-sm font-medium transition-colors duration-200',
            selectedMethod === method.name
              ? 'bg-blue-600 text-white'
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
      <div class="mb-6">
        <h4 class="text-sm font-semibold text-center mb-2 text-gray-700">
          Pagamentos Adicionados
        </h4>
        <ul>
          <li
            v-for="(value, methodKey) in paymentValues"
            :key="methodKey"
            class="flex items-center justify-between bg-gray-100 p-3 rounded-md mb-3 shadow-sm"
          >
            <span class="text-sm text-gray-600 flex-1 truncate">
              {{ getPaymentMethodLabel(methodKey) }}:
            </span>
            <input
              v-model="formattedPaymentValues[methodKey]"
              @blur="updatePaymentValue(methodKey)"
              type="text"
              class="border border-gray-300 rounded-md w-28 text-right py-1 px-2 text-sm"
            />
            <button
              class="text-red-600 hover:text-red-700 ml-4"
              @click="removePaymentMethod(methodKey)"
            >
              <i class="fa-solid fa-trash"></i>
            </button>
          </li>
        </ul>
      </div>

      <!-- Resumo financeiro -->
      <div class="text-sm mb-6">
        <div class="flex justify-between font-semibold mb-2 text-gray-700">
          <span>Subtotal:</span>
          <span>{{ formatCurrency(subtotal) }}</span>
        </div>
        <div class="flex justify-between font-semibold mb-2 text-green-500">
          <span>Acréscimo:</span>
          <span>{{ formatCurrency(addedValue) }}</span>
        </div>
        <div class="flex justify-between font-semibold mb-2 text-yellow-500">
          <span>Desconto:</span>
          <span>{{ formatCurrency(discountValue) }}</span>
        </div>
        <div class="flex justify-between font-bold text-lg text-gray-800">
          <span>Restante:</span>
          <span>{{ formatCurrency(remaining) }}</span>
        </div>
        <div v-if="remaining === 0" class="text-green-600 font-semibold mt-2">
          Pagamento completo!
        </div>
      </div>

      <!-- Área de Botões -->
      <div class="flex justify-between mt-6">
        <button
          class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold py-2 px-4 rounded-md w-full mr-2"
          @click="$emit('close')"
        >
          Cancelar
        </button>
        <button
          class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md w-full"
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

const emit = defineEmits(['save-payments', 'close']);

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

// Função para salvar os pagamentos
const savePayments = () => {
  // Verificar se paymentValues está correto
  console.log('paymentValues antes de emitir:', paymentValues.value);

  const methods = Object.keys(paymentValues.value).map((methodName) => ({
    name: methodName,
    amount: paymentValues.value[methodName],
  }));

  if (!Array.isArray(methods)) {
    console.error('methods não é um array:', methods);
    return;
  }

  emit('save-payments', {
    methods,
    totalPaid: props.subtotal + props.addedValue - props.discountValue,
  });

  emit('close');
};
</script>

<style scoped>
@keyframes modalFadeIn {
  0% {
    opacity: 0;
    transform: scale(0.95);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

/* Aplicar a animação de fade-in e zoom-in */
.animate-modal {
  animation: modalFadeIn 0.2s ease-out;
}

.payment-methods {
  z-index: 9999;
  background-color: rgba(0, 0, 0, 0.6); /* Fundo escurecido */
  padding: 1rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.bg-modal {
  background-color: rgba(0, 0, 0, 0.6); /* Fundo escurecido */
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
}

.bg-white {
  background-color: #fff;
}

.shadow-lg {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.grid-cols-2 {
  grid-template-columns: repeat(2, 1fr);
}

.sm\:grid-cols-3 {
  grid-template-columns: repeat(3, 1fr);
}

.md\:grid-cols-4 {
  grid-template-columns: repeat(4, 1fr);
}

button {
  cursor: pointer;
  transition: all 0.2s ease-in-out;
}

button:focus {
  outline: none;
}

button:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

button.bg-green-600 {
  background-color: #16a34a;
}

button.bg-green-600:hover {
  background-color: #15803d;
}

button.bg-gray-300 {
  background-color: #e5e7eb;
}

button.bg-gray-300:hover {
  background-color: #d1d5db;
}

button.bg-blue-600 {
  background-color: #2563eb;
}

button.bg-blue-600:hover {
  background-color: #1d4ed8;
}

button.bg-gray-200 {
  background-color: #f3f4f6;
}

button.bg-gray-200:hover {
  background-color: #e5e7eb;
}

input {
  border-radius: 0.375rem;
}

input[type='text'] {
  text-align: right;
}

input:focus {
  outline: none;
  border-color: #2563eb;
}
</style>
