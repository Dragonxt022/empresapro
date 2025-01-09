<template>
  <div class="grid grid-cols-1 gap-4 mt-12">
    <div>
      <table
        class="min-w-full table-auto bg-white rounded-lg shadow-lg overflow-hidden"
      >
        <thead>
          <tr>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Status
            </th>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Data / Hora Abertura
            </th>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Valor de Abertura
            </th>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Data / Hora Fechamento
            </th>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Valor de Fechamento
            </th>
            <th
              class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider"
            >
              Ações
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading">
            <td colspan="6" class="text-center py-4 text-gray-500">
              Carregando...
            </td>
          </tr>
          <tr
            v-for="caixa in caixas"
            :key="caixa.id"
            class="cursor-pointer hover:bg-gray-50 transition-colors duration-200"
          >
            <!-- Status -->
            <td
              :class="{
                'bg-green-500': caixa.status === 'aberto',
                'bg-gray-700': caixa.status === 'fechado',
              }"
              class="px-6 py-4 text-sm text-white text-center"
            >
              {{ caixa.status === 'aberto' ? 'Aberto' : 'Fechado' }}
            </td>
            <!-- Data/Hora de Abertura -->
            <td class="px-6 py-4 text-sm text-gray-700 text-center">
              {{ new Date(caixa.data_hora_abertura).toLocaleString() }}
            </td>
            <!-- Valor de Abertura -->
            <td class="px-6 py-4 text-sm text-gray-700 text-center">
              R$ {{ caixa.valor_inicial }}
            </td>
            <!-- Data/Hora de Fechamento -->
            <td
              class="px-6 py-4 text-sm text-gray-700 text-center"
              v-if="caixa.data_hora_fechamento"
            >
              {{ new Date(caixa.data_hora_fechamento).toLocaleString() }}
            </td>
            <td
              class="px-6 py-4 text-sm text-gray-700 text-center"
              v-if="caixa.valor_final"
            >
              R$ {{ caixa.valor_final }}
            </td>
            <!-- Ações -->
            <td class="px-6 py-4 text-center">
              <button
                v-if="caixa.status === 'fechado'"
                @click="mostrarDetalhes(caixa.id)"
                class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600"
              >
                Ver Detalhes
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <ModalDetalheCaixa
    :isModalOpen="isModalOpen"
    :modalTitle="'Resumo do Caixa'"
    :caixaId="caixaIdSelecionada"
    @update:isModalOpen="isModalOpen = $event"
  />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ModalDetalheCaixa from './ModalDetalheCaixa.vue';

const caixas = ref([]);

const caixaIdSelecionada = ref(null);
const isModalOpen = ref(false);

const isLoading = ref(true);

// Função para buscar os dados dos caixas
const fetchCaixas = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/api/relatorio-caixas');
    caixas.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar caixas:', error);
  } finally {
    isLoading.value = false;
  }
};

const mostrarDetalhes = (caixaId) => {
  caixaIdSelecionada.value = caixaId; // Define o ID da venda selecionada
  isModalOpen.value = true; // Abre a modal
};

// Carregar os caixas ao montar o componente
onMounted(() => {
  fetchCaixas();
});
</script>

<style scoped>
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
