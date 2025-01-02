<script setup>
import { ref, onMounted } from 'vue';
import IconAnalytcPreto from '@/Components/Icons/IconAnalytcPreto.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import axios from 'axios';

// Definindo variáveis reativas
const vendas = ref([]);
const isLoading = ref(true);

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
  // Aqui você pode abrir um modal com os detalhes da venda (se necessário)
  console.log('Exibindo detalhes da venda ID:', vendaId);
};
</script>

<template>
  <AppLayout title="Histórico">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <!-- Sidebar está contido no layout, então não é necessário aqui -->
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <!-- Seção de conteúdo -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- Título -->
              <div class="col-12">
                <h1 class="text-2xl font-bold text-sky-500 flex items-center">
                  <IconAnalytcPreto class="h-6 w-6 mr-2" />
                  Histórico de Vendas
                </h1>
                <p class="text-gray-600 mt-4">
                  Visualize o histórico de vendas realizadas na sua empresa.
                </p>
              </div>
            </div>

            <!-- Tabela de vendas -->
            <div class="col-12 mt-6">
              <table class="min-w-full table-auto bg-white shadow rounded-lg">
                <thead>
                  <tr>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Cliente
                    </th>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Data / Hora
                    </th>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Forma de Pagamento
                    </th>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Valor
                    </th>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Vendido por
                    </th>
                    <th
                      class="px-4 py-2 text-left text-sm font-semibold text-gray-600"
                    >
                      Ver Detalhes
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Carregando as vendas -->
                  <tr v-if="isLoading">
                    <td colspan="6" class="text-center py-4 text-gray-500">
                      Carregando...
                    </td>
                  </tr>
                  <!-- Listando as vendas -->
                  <tr v-for="venda in vendas" :key="venda.id">
                    <td class="px-4 py-2 text-sm text-gray-700">
                      {{ venda.cliente }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                      {{ venda.data_venda }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
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

                    <td class="px-4 py-2 text-sm text-gray-700">
                      R$ {{ venda.valor_total }}
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-700">
                      {{
                        venda.vendedor ? venda.vendedor.name : 'Não informado'
                      }}
                    </td>
                    <td
                      class="px-4 py-2 text-sm text-blue-500 cursor-pointer"
                      @click="mostrarDetalhes(venda.id)"
                    >
                      <span class="text-blue-600 hover:text-blue-800">
                        Ver Detalhes
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Estilos personalizados */
.table-auto th,
.table-auto td {
  border-bottom: 1px solid #e0e0e0;
}
</style>
