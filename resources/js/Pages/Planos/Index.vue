<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';

const planos = ref([]);

// Função para buscar os planos da API
const fetchPlanos = async () => {
  try {
    const response = await axios.get('/api/planos');
    planos.value = response.data; // Armazena os planos na variável `planos`
  } catch (error) {
    console.error('Erro ao buscar planos:', error);
  }
};

onMounted(() => {
  fetchPlanos(); // Chama a função para buscar os planos assim que o componente for montado
});
</script>

<template>
  <AppLayout title="Planos de Assinatura">
    <!-- Conteúdo principal -->
    <div class="container max-w-full mx-auto py-12 px-6">
      <h1
        class="text-center text-4xl text-black font-medium leading-snug tracking-wider"
      >
        Planos de Assinatura
      </h1>
      <p class="text-center text-lg text-gray-700 mt-2 px-6">
        Escolha o plano ideal para o seu negócio e aproveite todos os
        benefícios.
      </p>
      <div class="h-1 mx-auto bg-indigo-200 w-24 opacity-75 mt-4 rounded"></div>

      <!-- Cards dos planos -->
      <div class="flex flex-wrap justify-center mt-8">
        <div
          v-for="(plano, index) in planos"
          :key="plano.id"
          :class="{
            'w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 p-4': true,
            'border-4 border-yellow-500 bg-yellow-50': index === 1, // Destacar o meio
          }"
        >
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-8 py-6 text-center">
              <h2 class="text-xl font-semibold text-gray-900 mb-4">
                {{ plano.nome }}
              </h2>
              <p class="text-2xl font-bold text-sky-500 mb-6">
                R$ {{ plano.valor_mensal }}
                <span class="text-sm">/ Mês</span>
              </p>
              <p class="text-gray-600 text-sm mb-4">
                Duração: {{ plano.duracao_dias }} dias
              </p>
              <ul class="text-left text-gray-700 mb-6 space-y-4">
                <li class="flex items-center">
                  <svg
                    class="w-5 h-5 text-green-500 mr-3"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Controle de Mesa
                </li>
                <li class="flex items-center">
                  <svg
                    class="w-5 h-5 text-green-500 mr-3"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Controle de Balcão
                </li>
                <li class="flex items-center">
                  <svg
                    class="w-5 h-5 text-green-500 mr-3"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Controle de Estoque
                </li>
                <li class="flex items-center">
                  <svg
                    class="w-5 h-5 text-green-500 mr-3"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Análises de Vendas
                </li>
                <li class="flex items-center">
                  <svg
                    class="w-5 h-5 text-green-500 mr-3"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  >
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                  </svg>
                  Controle de Caixa
                </li>
              </ul>
              <button
                class="w-full py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition"
              >
                Contratar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
