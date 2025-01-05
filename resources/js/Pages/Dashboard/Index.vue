<template>
  <AppLayout title="Dashboard">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <!-- Seção de conteúdo -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div v-if="isLoading" class="loading-overlay">
                  <div class="spinner"></div>
                </div>

                <div v-else>
                  <p v-if="caixaAberto" class="text-green-600">
                    O caixa está aberto. Você será redirecionado para as mesas
                    em instantes...
                  </p>
                  <div v-else class="flex flex-col items-center">
                    <img
                      src="/storage/images/abrir_caixa.svg"
                      alt="imagem do caixa"
                      class="w-[30%] h-auto"
                    />
                    <p class="text-2xl text-center text-gray-800">
                      Para começar efetuar vendas é preciso
                      <br />
                      <span class="font-bold text-2xl">abrir o caixa</span>
                    </p>
                    <button
                      @click="abrirCaixaModal = true"
                      class="mt-4 px-10 py-3 bg-blue-500 text-white rounded-lg"
                    >
                      <span class="font-bold">Abrir Caixa</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
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
  </AppLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente

import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import { notify } from '@/Plugins/Notify';

// Estados reativos
const isLoading = ref(false);
const caixaAberto = ref(false);
const abrirCaixaModal = ref(false);
const valorInicial = ref(0);

// Função para verificar o status do caixa
const checkCaixaStatus = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/caixa/status');
    caixaAberto.value = response.data.status === 'aberto';
  } catch (error) {
    console.error('Erro ao verificar o status do caixa:', error);
  } finally {
    isLoading.value = false;
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
      notify.success('Caixa aberto com sucesso!', 'success');
    }
  } catch (error) {
    console.error('Erro ao abrir o caixa:', error);
    notify.error('Erro ao abrir o caixa. Tente novamente.', 'error');
  } finally {
    isLoading.value = false;
  }
};

// Função personalizada para navegação
const navigateTo = (routeName) => {
  router.visit(route(routeName), {
    preserveScroll: true, // Mantém o scroll atual
    preserveState: false, // Reseta o estado
  });
};

// Redirecionando para as mesas se o caixa estiver aberto
watch(caixaAberto, (newVal) => {
  if (newVal) {
    navigateTo('mesas');
  }
});

// Verifica o status do caixa ao carregar a página
onMounted(() => {
  checkCaixaStatus();
});
</script>

<style lang="css">
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
