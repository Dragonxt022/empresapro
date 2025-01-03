<script setup>
import { ref, onMounted } from 'vue';
import IconAnalytcPreto from '@/Components/Icons/IconAnalytcPreto.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';
import ModalDetalheVendas from '@/Components/Elementos/ModalDetalheVendas.vue';
import StatusCaixa from '@/Components/Elementos/StatusCaixa.vue';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
// Definindo variáveis reativas
const emit = defineEmits();

const vendas = ref([]);
const isLoading = ref(true);
const isModalOpen = ref(false); // Controle da visibilidade da modal
const vendaIdSelecionada = ref(null); // Armazenar o id da venda selecionada

// Função para buscar os dados do histórico de vendas
const fetchVendas = async () => {
  try {
    const response = await axios.get('/api/historico/vendas');
    vendas.value = response.data;
  } catch (error) {
    console.error('Erro ao carregar o histórico de vendas:', error);
  } finally {
    isLoading.value = false;
  }
};

// Chama a função para buscar as vendas ao carregar o componente
onMounted(() => {
  fetchVendas();
});

// Função para exibir detalhes no modal
const mostrarDetalhes = (vendaId) => {
  vendaIdSelecionada.value = vendaId; // Define o ID da venda selecionada
  isModalOpen.value = true; // Abre a modal
};

function updateComponets() {
  console.log('Atualizando componentes');
  router.visit(window.location.href, {
    preserveScroll: true, // Mantém a posição do scroll
    preserveState: false, // Atualiza o estado do componente
  });
}
</script>

<template>
  <AppLayout title="Histórico">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <h1 class="text-2xl font-bold text-sky-500 flex items-center">
                  <IconAnalytcPreto class="h-6 w-6 mr-2" />
                  Histórico de Vendas
                </h1>
                <p class="text-gray-600 mt-2">
                  Visualize o histórico de vendas realizadas na sua empresa.
                </p>
              </div>
            </div>

            <StatusCaixa @updateComponents="updateComponets" />

            <!-- Tabela de vendas -->
            <div class="col-12 mt-8">
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
                        <span
                          v-for="(pagamento, index) in venda.pagamentos"
                          :key="index"
                        >
                          {{ pagamento.forma_pagamento }}
                          <span v-if="index < venda.pagamentos.length - 1">
                            ,
                          </span>
                        </span>
                      </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 text-center">
                      R$ {{ venda.valor_total }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700 text-center">
                      {{
                        venda.vendedor ? venda.vendedor.name : 'Não informado'
                      }}
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
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

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
