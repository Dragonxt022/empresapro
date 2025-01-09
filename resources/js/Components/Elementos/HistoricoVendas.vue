<template>
  <div class="grid grid-cols-3 gap-4 mt-12">
    <div class="col-span-2">
      <SearchBarVendas @search="onSearch" />
    </div>
    <div>
      <!-- Botão de Filtro que abre a modal -->
      <button
        @click="abrirModalFiltro"
        class="p-3 bg-gray-400 text-white rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 w-full flex items-center justify-center space-x-2 transition-colors duration-200"
      >
        <i class="fas fa-filter"></i>
        <span>Filtro</span>
      </button>
    </div>
  </div>

  <div class="col-12 mt-5">
    <table
      class="min-w-full table-auto bg-white rounded-lg shadow-lg overflow-hidden"
    >
      <thead>
        <tr>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Cliente
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Data / Hora
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Forma de Pagamento
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Valor
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Vendido por
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="isLoading">
          <td colspan="5" class="text-center py-4 text-gray-500">
            Carregando...
          </td>
        </tr>
        <tr
          v-for="venda in vendas"
          :key="venda.id"
          class="cursor-pointer hover:bg-gray-50 transition-colors duration-200"
          @click="mostrarDetalhes(venda.id)"
        >
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ venda.cliente }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ venda.data_venda }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            <span v-if="venda.pagamentos.length > 1">Composto</span>
            <span v-else>
              <span v-for="(pagamento, index) in venda.pagamentos" :key="index">
                {{ pagamento.forma_pagamento }}
                <span v-if="index < venda.pagamentos.length - 1">,</span>
              </span>
            </span>
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            R$ {{ venda.valor_total }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ venda.vendedor ? venda.vendedor.full_name : 'Não informado' }}
          </td>
        </tr>
      </tbody>
    </table>

    <ModalDetalheVendas
      :isModalOpen="isModalOpen"
      :modalTitle="'Detalhes da Venda'"
      :vendaId="vendaIdSelecionada"
      @updateComponents="updateComponets"
      @update:isModalOpen="isModalOpen = $event"
    />
  </div>

  <!-- Modal de Filtro -->
  <div
    v-if="isModalFiltroOpen"
    class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center z-50"
  >
    <div
      class="bg-white p-6 rounded-lg shadow-lg w-full max-w-3xl transform transition-all duration-300 ease-in-out"
    >
      <h2 class="text-xl font-semibold mb-6 text-center text-gray-800">
        Filtro de Data
      </h2>

      <!-- Botões de Filtro Rápido -->
      <div class="mb-4 flex space-x-4 justify-center">
        <button
          v-for="dias in [10, 30, 60, 80, 90]"
          :key="dias"
          @click="aplicarFiltroData(dias)"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Últimos {{ dias }} dias
        </button>
      </div>

      <!-- Component DataFiltro -->
      <DataFiltro @atualizarFiltro="atualizarFiltro" />

      <div class="mt-6 flex justify-between">
        <button
          @click="fecharModalFiltro"
          class="px-6 py-2 bg-gray-300 w-full mr-2 text-gray-700 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Fechar
        </button>
        <button
          @click="aplicarFiltro"
          class="px-6 py-2 bg-blue-500 w-full text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Aplicar Filtro
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineEmits } from 'vue';
import { ref, onMounted, watch } from 'vue';
import ModalDetalheVendas from '@/Components/Elementos/ModalDetalheVendas.vue';
import axios from 'axios';
import SearchBarVendas from '../SearchBarVendas.vue';
import DataFiltro from './DataFiltro.vue';

const emit = defineEmits(['updateComponents']);

const vendas = ref([]);
const isLoading = ref(true);
const isModalOpen = ref(false); // Controle da visibilidade da modal
const vendaIdSelecionada = ref(null); // Armazenar o id da venda selecionada

const isModalFiltroOpen = ref(false); // Controle da visibilidade da modal de filtro
const filtroDataInicio = ref('');
const filtroDataFim = ref('');
const filtroPesquisa = ref('');

// Função para atualizar os componentes
const updateComponets = () => {
  emit('updateComponents');
  fetchVendas();
};

const atualizarFiltro = (filtro) => {
  filtroDataInicio.value = filtro.data_inicio;
  filtroDataFim.value = filtro.data_fim;
  fetchVendas(filtroPesquisa.value); // Incluindo o filtro de pesquisa ao buscar as vendas
};

const aplicarFiltroData = (dias) => {
  const hoje = new Date();
  const inicio = new Date(hoje);
  inicio.setDate(hoje.getDate() - dias); // Subtrai os dias selecionados

  // Formata a data para o formato YYYY-MM-DD
  filtroDataInicio.value = inicio.toISOString().split('T')[0];

  // Ajusta o final do dia para 23:59:59 para garantir que o filtro inclua todo o dia de hoje
  const fim = new Date(hoje);
  fim.setHours(23, 59, 59, 999); // Final do dia
  filtroDataFim.value = fim.toISOString().split('T')[0];

  fetchVendas(filtroPesquisa.value); // Aplica o filtro com a pesquisa atual
};

// Função para buscar as vendas com base no filtro de pesquisa
const fetchVendas = async (searchTerm = '') => {
  isLoading.value = true;

  try {
    // Adiciona um delay de 2 segundos
    await new Promise((resolve) => setTimeout(resolve, 2000)); // Delay de 2 segundos

    // Incluindo o parâmetro de pesquisa na requisição
    const response = await axios.get('/api/historico/vendas', {
      params: {
        search: searchTerm, // Passa o termo de pesquisa para a API
        data_inicio: filtroDataInicio.value,
        data_fim: filtroDataFim.value,
      },
    });

    vendas.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar o histórico de vendas:', error);
  } finally {
    isLoading.value = false;
  }
};

const onSearch = (searchTerm) => {
  filtroPesquisa.value = searchTerm;
  fetchVendas(searchTerm); // Refaz a busca com o termo de pesquisa
};

// Também podemos usar o watch para garantir que a busca seja feita quando filtroPesquisa ou filtroData mudarem
watch([filtroPesquisa, filtroDataInicio, filtroDataFim], () => {
  fetchVendas(filtroPesquisa.value); // Realiza a busca novamente sempre que qualquer filtro mudar
});

// Chama a função para buscar as vendas ao carregar o componente
onMounted(() => {
  // Chama a função para buscar as vendas com o filtro de data dos dois dias
  aplicarFiltroData(2);
});

// Função para exibir detalhes no modal
const mostrarDetalhes = (vendaId) => {
  vendaIdSelecionada.value = vendaId; // Define o ID da venda selecionada
  isModalOpen.value = true; // Abre a modal
};

const abrirModalFiltro = () => {
  isModalFiltroOpen.value = true;
};

const fecharModalFiltro = () => {
  isModalFiltroOpen.value = false;
};
</script>

<style lang="css" scoped>
.table-auto th,
.table-auto td {
  border-bottom: 1px solid #e0e0e0;
}

.table-auto th {
  background-color: #f7f7f7;
}

.table-auto tbody tr:nth-child(odd) {
  background-color: #f9fafb;
}

.table-auto tbody tr:hover {
  background-color: #f1f5f9;
}
</style>
