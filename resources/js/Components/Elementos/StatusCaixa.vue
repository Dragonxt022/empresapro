<template>
  <div class="grid grid-cols-2 gap-1 mt-5 w-full">
    <!-- Seção do texto à esquerda -->
    <div class="flex items-center space-x-2">
      <h2 class="text-2xl font-bold text-gray-700 flex items-center">
        Resumo de Vendas
      </h2>
      <div class="flex items-center space-x-3">
        <!-- Fundo alterado conforme o status -->
        <div
          class="flex items-center px-2 py-1 rounded-lg text-sm"
          :class="{
            'bg-green-100 text-green-700': caixaStatus,
            'bg-red-100 text-red-700': !caixaStatus,
          }"
        >
          <!-- Bolinha alterada conforme o status -->
          <div
            :class="{
              'bg-green-500': caixaStatus,
              'bg-red-500': !caixaStatus,
            }"
            class="w-2 h-2 rounded-full mr-2"
          ></div>
          <span>{{ caixaStatus ? 'aberto' : 'fechado' }}</span>
        </div>
      </div>
    </div>

    <!-- Seção dos botões à direita -->
    <div
      v-if="caixaStatus === 'aberto'"
      class="flex justify-end items-center space-x-2"
    >
      <!-- Botões -->
      <button
        class="px-4 py-2 bg-blue-500 text-white rounded flex items-center space-x-2"
        @click="abrirModalEntrada"
      >
        <i class="fa-solid fa-plus"></i>
        <span>Adicionar entrada</span>
      </button>
      <button
        class="px-4 py-2 bg-red-500 text-white rounded flex items-center space-x-2"
        @click="abrirModalSaida"
      >
        <i class="fa-solid fa-minus"></i>
        <span>Adicionar saída</span>
      </button>
    </div>
  </div>

  <div v-if="caixaStatus === 'aberto'" class="grid grid-cols-4 gap-4 mt-5">
    <div class="bg-white shadow rounded-md p-6">
      <div class="flex items-center space-x-2">
        <!-- Ícone de Vendas -->
        <i class="fas fa-shopping-cart text-gray-500"></i>
        <h3 class="text-gray-900 font-bold">Vendas</h3>
      </div>
      <p class="text-gray-700">{{ totalVendas }}</p>
    </div>

    <div class="bg-white shadow rounded-md p-6">
      <div class="flex items-center space-x-2">
        <!-- Ícone de Valor de Abertura -->
        <i class="fas fa-dollar-sign text-gray-500"></i>
        <h3 class="text-gray-900 font-bold">Valor de Abertura</h3>
      </div>
      <p class="text-gray-700">{{ valorAbertura }}</p>
    </div>

    <div class="bg-white shadow rounded-md p-6">
      <div class="flex items-center space-x-2">
        <!-- Ícone de Entradas -->
        <i class="fas fa-arrow-circle-up text-gray-500"></i>
        <h3 class="text-gray-900 font-bold">Entradas</h3>
      </div>
      <p class="text-gray-700">{{ entradas }}</p>
    </div>

    <div class="bg-white shadow rounded-md p-6">
      <div class="flex items-center space-x-2">
        <!-- Ícone de Saídas -->
        <i class="fas fa-arrow-circle-down text-gray-500"></i>
        <h3 class="text-gray-900 font-bold">Saídas</h3>
      </div>
      <p class="text-gray-700">{{ saidas }}</p>
    </div>
  </div>

  <div class="grid grid-cols-1 mt-12">
    <button
      :class="caixaStatus === 'aberto' ? 'bg-red-500' : 'bg-blue-500'"
      class="px-5 py-3 text-white rounded"
      @click="
        caixaStatus === 'aberto'
          ? (fecharCaixaModal = true)
          : (abrirCaixaModal = true)
      "
    >
      <i class="fas fa-store mr-2"></i>
      <span>
        {{
          caixaStatus === 'aberto'
            ? 'Quero fechar meu caixa'
            : 'Quero abrir meu caixa'
        }}
      </span>
    </button>
  </div>

  <!-- Modal de Fechar Caixa -->
  <div
    v-if="fecharCaixaModal"
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-md transition-all duration-300"
  >
    <div
      class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
    >
      <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
        Fechar Caixa
      </h2>

      <p class="text-sm text-gray-700 mb-4 text-center">
        Tem certeza que deseja fechar o caixa? Qualquer operação pendente será
        encerrada.
      </p>

      <!-- Campo de entrada de valor do caixa -->
      <div class="mb-4">
        <label
          for="entradaValor"
          class="block text-sm font-medium text-gray-700"
        >
          Valor em Caixa
        </label>
        <input
          v-model="valorEntradaCaixa"
          type="number"
          id="entradaValor"
          class="mt-2 p-2 w-full border border-gray-300 rounded-lg"
          placeholder="0.00"
          min="0"
          @input="calcularDiferenca"
        />
      </div>

      <!-- Soma total das vendas em dinheiro -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">
          Total Vendas em Dinheiro
        </label>
        <p class="mt-2 text-lg font-semibold">R$ {{ vendasDinheiro }}</p>
      </div>

      <!-- Diferença -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Diferença</label>
        <p class="mt-2 text-lg font-semibold">R$ {{ diferenca }}</p>
      </div>

      <div class="flex justify-between mt-6">
        <button
          @click="fecharCaixaModal = false"
          class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition duration-200"
        >
          Cancelar
        </button>
        <button
          @click="fecharCaixa"
          class="px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition duration-200"
        >
          Confirmar
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de abertura do caixa -->
  <div
    v-if="abrirCaixaModal"
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 backdrop-blur-md transition-all duration-300"
  >
    <div
      class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
    >
      <h2 class="text-2xl font-semibold text-gray-900 mb-6 text-center">
        Abrir Caixa
      </h2>

      <label class="block text-sm font-medium text-gray-700 mb-4 text-center">
        Insira abaixo o valor de abertura do caixa:
      </label>
      <input
        v-model="valorInicial"
        type="number"
        class="w-full p-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Digite o valor inicial do caixa"
      />

      <div class="flex justify-between mt-6">
        <button
          @click="abrirCaixaModal = false"
          class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition duration-200"
        >
          Cancelar
        </button>
        <button
          @click="openCaixa"
          class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-200"
        >
          Confirmar
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de Adicionar Entrada -->
  <div
    v-if="isModalEntradaOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
  >
    <div
      class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
    >
      <h3 class="text-lg font-bold">Adicionar Entrada</h3>
      <input
        v-model="valorEntrada"
        type="number"
        class="mt-4 p-2 border border-gray-300 rounded w-full"
        placeholder="Valor da entrada"
      />
      <input
        v-model="descricaoEntrada"
        type="text"
        class="mt-2 p-2 border border-gray-300 rounded w-full"
        placeholder="Descrição (opcional)"
      />
      <div class="mt-4 flex justify-between">
        <button
          @click="fecharModalEntrada"
          class="px-4 py-2 bg-gray-300 rounded"
        >
          Cancelar
        </button>
        <button
          @click="adicionarEntrada"
          class="px-4 py-2 bg-blue-500 text-white rounded"
        >
          Adicionar
        </button>
      </div>
    </div>
  </div>

  <!-- Modal de Adicionar Saída -->
  <div
    v-if="isModalSaidaOpen"
    class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center"
  >
    <div
      class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
    >
      <h3 class="text-lg font-bold">Adicionar Saída</h3>
      <input
        v-model="valorSaida"
        type="number"
        class="mt-4 p-2 border border-gray-300 rounded w-full"
        placeholder="Valor da saída"
      />
      <input
        v-model="descricaoSaida"
        type="text"
        class="mt-2 p-2 border border-gray-300 rounded w-full"
        placeholder="Descrição (opcional)"
      />
      <div class="mt-4 flex justify-between">
        <button @click="fecharModalSaida" class="px-4 py-2 bg-gray-300 rounded">
          Cancelar
        </button>
        <button
          @click="adicionarSaida"
          class="px-4 py-2 bg-red-500 text-white rounded"
        >
          Adicionar
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { notify } from '@/Plugins/Notify';
import { defineEmits } from 'vue';

// Definindo os eventos emitidos pelo componente
const emit = defineEmits(['updateComponents']);

// Variáveis reativas

const fecharCaixaModal = ref(false);

const totalVendas = ref('R$ 0,00');
const valorAbertura = ref('R$ 0,00');
const entradas = ref('R$ 0,00');
const saidas = ref('R$ 0,00');

// Variáveis para o modal de fechar caixa
const vendasDinheiro = ref(0); // Total das vendas em dinheiro
const valorEntradaCaixa = ref(0); // Valor inserido pelo usuário
const diferenca = ref(0); // Diferença entre o valor do caixa e as vendas em dinheiro

// Variáveis para os modais
const isModalEntradaOpen = ref(false); // Controle para abrir/fechar a modal de entrada
const isModalSaidaOpen = ref(false); // Controle para abrir/fechar a modal de saída
const valorEntrada = ref('');
const descricaoEntrada = ref('');
const valorSaida = ref('');
const descricaoSaida = ref('');

// Variáveis para o modal de abrir caixa
const isLoading = ref(false);
const caixaStatus = ref('');
const caixaAberto = ref(false);

const abrirCaixaModal = ref(false);
const valorInicial = ref(0);

// Função para emitir o evento 'updateComponents'

// Função para fazer a requisição e popular os dados
const obterDadosCaixa = async () => {
  try {
    const response = await axios.get('/api/historico/caixa');

    if (response.data.success) {
      caixaStatus.value = response.data.data.status_caixa;
      totalVendas.value = response.data.data.total_vendas;
      valorAbertura.value = response.data.data.valor_abertura;
      entradas.value = response.data.data.total_entradas;
      saidas.value = response.data.data.total_saidas;
      vendasDinheiro.value = response.data.data.valor_total_final;
      // Aqui você pode adicionar a lógica para entradas e saídas, se necessário.
    }
  } catch (error) {
    console.error('Erro ao obter dados do caixa:', error);
  }
};

// Funções para abrir/fechar modais
const abrirModalEntrada = () => {
  isModalEntradaOpen.value = true;
};

const fecharModalEntrada = () => {
  isModalEntradaOpen.value = false;
};

const abrirModalSaida = () => {
  isModalSaidaOpen.value = true;
};

const fecharModalSaida = () => {
  isModalSaidaOpen.value = false;
};

// Função para adicionar entrada
const adicionarEntrada = async () => {
  try {
    const response = await axios.post('/api/caixa/operacoes', {
      tipo: 'entrada',
      valor: valorEntrada.value,
      descricao: descricaoEntrada.value,
    });

    if (response.data.success) {
      notify.success('Entrada adicionada com sucesso!');
      obterDadosCaixa();
      fecharModalEntrada();
    }
  } catch (error) {
    console.error('Erro ao adicionar entrada:', error);
    notify.error('Erro ao adicionar entrada');
  }
};

// Função para adicionar saída
const adicionarSaida = async () => {
  try {
    const response = await axios.post('/api/caixa/operacoes', {
      tipo: 'saida',
      valor: valorSaida.value,
      descricao: descricaoSaida.value,
    });

    if (response.data.success) {
      notify.success('Saída adicionada com sucesso!');
      obterDadosCaixa();
      fecharModalSaida();
    }
  } catch (error) {
    console.error('Erro ao adicionar saída:', error);
    notify.error('Erro ao adicionar saída');
  }
};

// Função para fechar o caixa
const fecharCaixa = async () => {
  try {
    // Converter valores para centavos
    const valorFinalCentavos = Math.round(
      parseFloat(String(valorEntradaCaixa.value || '0').replace(',', '.')) * 100
    );
    const diferencaCentavos = Math.round(
      parseFloat(String(diferenca.value || '0').replace(',', '.')) * 100
    );

    // Enviar valores convertidos para a API
    const response = await axios.post('/api/caixa/fechar', {
      valor_final: valorFinalCentavos,
      diferenca: diferencaCentavos,
    });

    if (response.data.success) {
      notify.success('Caixa fechado com sucesso!');
      emit('updateComponents'); // Usando emit diretamente
      fecharCaixaModal.value = false; // Fecha a modal após a operação bem-sucedida
    } else {
      notify.error('Erro ao fechar o caixa');
    }
  } catch (error) {
    console.error('Erro ao tentar fechar o caixa:', error);
    notify.error('Erro ao tentar fechar o caixa');
  }
};

// Função para abrir o caixa
const openCaixa = async () => {
  try {
    isLoading.value = true;

    // Certifique-se de que o valorInicial é uma string antes de aplicar o replace
    const valorInicialString = valorInicial.value.toString();

    // Convertendo o valor inicial de reais para centavos
    const valorInicialCentavos =
      parseFloat(valorInicialString.replace('R$', '').replace(',', '.')) * 100;

    const response = await axios.post('/api/caixa/abrir', {
      valor_inicial: valorInicialCentavos, // Enviando o valor convertido para centavos
    });

    if (response.data.success) {
      caixaAberto.value = true;
      abrirCaixaModal.value = false;
      emit('updateComponents'); // Usando emit diretamente
      notify.success('Caixa aberto com sucesso!', 'success');
    }
  } catch (error) {
    console.error('Erro ao abrir o caixa:', error);
    notify.error('Erro ao abrir o caixa. Tente novamente.', 'error');
  } finally {
    isLoading.value = false;
  }
};

const calcularDiferenca = () => {
  const valorCaixa = parseFloat(
    String(valorEntradaCaixa.value || '0').replace(',', '.')
  );
  const vendasDinheiroValor = parseFloat(
    String(vendasDinheiro.value || '0').replace(',', '.')
  );

  // Calcular a diferença em centavos
  const diferencaCentavos =
    Math.round(valorCaixa * 100) - Math.round(vendasDinheiroValor * 100);

  // Converter a diferença de volta para reais e formatar sem "R$"
  diferenca.value = (diferencaCentavos / 100).toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
  });
};

// Chama a função ao montar o componente
onMounted(() => {
  obterDadosCaixa();
});
</script>

<style lang="css" scoped>
/* Animação de entrada para a modal */
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
  animation: modalFadeIn 0.4s ease-out;
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
