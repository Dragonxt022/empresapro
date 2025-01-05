<template>
  <AppLayoutAdmin title="Planos">
    <div class="container mx-auto py-6">
      <h1 class="text-2xl font-bold mb-4">Lista de Planos</h1>

      <!-- Botão para adicionar novo plano -->
      <button
        class="bg-blue-500 text-white px-4 py-2 rounded mb-4"
        @click="openModal('create')"
      >
        Adicionar Novo Plano
      </button>

      <!-- Tabela de planos -->
      <table class="table-auto w-full bg-white shadow-md rounded">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2">Nome</th>
            <th class="px-4 py-2">Valor Mensal</th>
            <th class="px-4 py-2">Valor Total</th>
            <th class="px-4 py-2">Dias</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="plano in planos" :key="plano.id" class="text-center">
            <td class="border px-4 py-2">{{ plano.nome }}</td>
            <td class="border px-4 py-2">R$ {{ plano.valor_mensal }}</td>
            <td class="border px-4 py-2">R$ {{ plano.valor_total }}</td>
            <td class="border px-4 py-2">{{ plano.dias }} dias</td>
            <td class="border px-4 py-2">
              <span
                :class="{
                  'text-green-500': plano.status,
                  'text-red-500': !plano.status,
                }"
              >
                {{ plano.status ? 'Ativo' : 'Inativo' }}
              </span>
            </td>
            <td class="border px-4 py-2">
              <button
                class="bg-yellow-500 text-white px-3 py-1 rounded mr-2"
                @click="openModal('edit', plano)"
              >
                Editar
              </button>
              <button
                class="bg-red-500 text-white px-3 py-1 rounded"
                @click="deletePlano(plano.id)"
              >
                Excluir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayoutAdmin>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayoutAdmin from '@/Layouts/AppLayoutAdmin.vue';

const planos = ref([]);

const fetchPlanos = async () => {
  try {
    const response = await axios.get('/api/admin/assinaturas', {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    planos.value = response.data;
  } catch (error) {
    console.error('Erro ao buscar planos:', error.response?.data || error);
  }
};

const openModal = (action, plano = null) => {
  // Lógica para abrir modal de criação ou edição
  console.log(action, plano);
};

const deletePlano = async (id) => {
  try {
    await axios.delete(`/api/admin/assinaturas/${id}`, {
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
      },
    });
    fetchPlanos(); // Atualiza a lista após exclusão
  } catch (error) {
    console.error('Erro ao excluir plano:', error.response?.data || error);
  }
};

onMounted(() => {
  fetchPlanos();
});
</script>

<style scoped>
/* Estilos personalizados para a tabela e botões */
</style>
