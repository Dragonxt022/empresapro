<template>
  <div class="grid grid-cols-3 gap-4 mt-12">
    <div class="col-span-2">
      <!-- <SearchBarEmpresa @search="onSearch" /> -->
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
            Nome da Empresa
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            CNPJ
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Cidade
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Status
          </th>
          <th
            class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
          >
            Ação
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
          v-for="empresa in empresas"
          :key="empresa.id"
          class="cursor-pointer hover:bg-gray-50 transition-colors duration-200"
          @click="mostrarDetalhes(empresa.id)"
        >
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ empresa.name }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ empresa.cnpj }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ empresa.city }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            {{ empresa.status }}
          </td>
          <td class="px-6 py-4 text-sm text-gray-700 text-center">
            <button
              @click="editarEmpresa(empresa.id)"
              class="text-blue-600 hover:text-blue-800"
            >
              Editar
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal de Detalhes -->
    <ModalDetalheEmpresa
      :isModalOpen="isModalOpen"
      :modalTitle="'Detalhes da Empresa'"
      :empresaId="empresaIdSelecionada"
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
        Filtro de Empresas
      </h2>

      <!-- Filtro de Status -->
      <div class="mb-4 flex space-x-4 justify-center">
        <button
          @click="aplicarFiltroStatus('ativo')"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Ativo
        </button>
        <button
          @click="aplicarFiltroStatus('inativo')"
          class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Inativo
        </button>
      </div>

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
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ModalDetalheEmpresa from './ModalDetalheEmpresa.vue';

const empresas = ref([]);
const isLoading = ref(false);
const isModalOpen = ref(false);
const empresaIdSelecionada = ref(null);
const isModalFiltroOpen = ref(false);

const onSearch = (query) => {
  // Lógica para pesquisa de empresas
};

const abrirModalFiltro = () => {
  isModalFiltroOpen.value = true;
};

const fecharModalFiltro = () => {
  isModalFiltroOpen.value = false;
};

const aplicarFiltroStatus = (status) => {
  // Lógica para filtrar empresas por status
};

const aplicarFiltro = () => {
  // Lógica para aplicar filtros
};

const mostrarDetalhes = (empresaId) => {
  empresaIdSelecionada.value = empresaId;
  isModalOpen.value = true;
};

const editarEmpresa = (empresaId) => {
  // Lógica para editar a empresa
};

const updateComponets = () => {
  // Atualizar componentes após ação
};

// Função para buscar empresas da API
const fetchEmpresas = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/empresas');
    empresas.value = response.data.data; // Supondo que a API retorne as empresas nesse formato
  } catch (error) {
    console.error('Erro ao buscar empresas:', error);
  } finally {
    isLoading.value = false;
  }
};

// Carregar empresas na montagem do componente
onMounted(() => {
  fetchEmpresas();
});
</script>

<style scoped>
/* Estilos específicos do componente */
</style>
