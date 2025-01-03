<template>
  <div>
    <!-- Modal -->
    <transition name="slide">
      <div
        v-if="isModalOpen"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end items-start"
      >
        <!-- Modal Content -->
        <div
          class="modal-content w-1/2 bg-white h-full p-6 overflow-y-auto transform transition-transform rounded-lg shadow-lg"
          :class="{
            'slide-from-right': isModalOpen,
            'slide-to-right': !isModalOpen,
          }"
        >
          <!-- Header -->
          <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h2 class="text-2xl font-semibold flex items-center space-x-2">
              <span class="material-icons text-blue-500">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  style="fill: rgba(8, 118, 178, 1); transform: 0; msfilter: "
                >
                  <path
                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"
                  ></path>
                  <path d="M11 11h2v6h-2zm0-4h2v2h-2z"></path>
                </svg>
              </span>
              <span class="text-gray-800">Detalhes da Venda</span>
            </h2>
            <button
              @click="closeModal"
              class="close-button absolute top-4 right-4 text-gray-500"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Venda Detalhes -->
          <div v-if="detalhesVenda">
            <div
              class="grid grid-cols-8 gap-4 mb-6 flex justify-between text-center"
            >
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Cliente</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesVenda.cliente }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Data</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesVenda.data_venda }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Vendido por</p>
                <p class="font-semibold text-gray-800">
                  {{
                    detalhesVenda.vendedor
                      ? detalhesVenda.vendedor.full_name
                      : 'Não informado'
                  }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Valor Total</p>
                <p class="font-semibold text-gray-800">
                  R$ {{ detalhesVenda.valor_total }}
                </p>
              </div>
            </div>

            <!-- Resumo de Valores -->
            <div class="border-t pt-4 mb-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span class="text-gray-800">Resumo de Valores</span>
              </h3>
              <div class="grid grid-cols-2 gap-4">
                <div class="text-gray-600">Desconto</div>
                <div class="font-semibold text-red-500 text-right">
                  - R$ {{ detalhesVenda.desconto }}
                </div>

                <div class="text-gray-600">Acréscimo</div>
                <div class="font-semibold text-green-500 text-right">
                  + R$ {{ detalhesVenda.acrescimo }}
                </div>

                <div class="text-gray-800 font-semibold">Total</div>
                <div class="font-semibold text-right">
                  R$ {{ detalhesVenda.valor_total }}
                </div>
              </div>
            </div>

            <!-- Formas de Pagamento -->
            <div class="border-t pt-4 mb-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span>Formas de Pagamento</span>
                <span class="text-gray-500 text-sm">
                  ({{ detalhesVenda.pagamentos.length }} métodos)
                </span>
              </h3>
              <ul>
                <li
                  v-for="(pagamento, index) in detalhesVenda.pagamentos"
                  :key="index"
                  class="flex justify-between py-1"
                >
                  <span>{{ pagamento.name }}</span>
                  <span class="font-semibold">R$ {{ pagamento.valor }}</span>
                </li>
              </ul>
            </div>

            <!-- Resumo de Produtos -->
            <div class="border-t pt-4">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span>Resumo de Produtos</span>
                <span class="text-gray-500 text-sm">
                  ({{ detalhesVenda.produtos.length }} produtos)
                </span>
              </h3>
              <ul>
                <li
                  v-for="(produto, index) in detalhesVenda.produtos"
                  :key="index"
                  class="grid grid-cols-3 gap-4 py-1"
                >
                  <span class="col-span-1">{{ produto.nome }}</span>
                  <span class="col-span-1 text-center">
                    x{{ produto.quantidade }}
                  </span>
                  <span class="col-span-1 text-right font-semibold">
                    R$ {{ produto.valor_total }}
                  </span>
                </li>
              </ul>
            </div>

            <!-- Botão para excluir a venda -->
            <div class="mt-6 flex justify-center">
              <button
                class="text-red-500 px-4 py-2 rounded-md flex items-center font-bold"
                @click="openDeleteConfirmationModal"
              >
                <i class="fa fa-trash text-red-500"></i>
                <span class="me-2">Excluir Venda</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Modal de Confirmação -->
    <transition name="fade">
      <div
        v-if="isDeleteModalOpen"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-center items-center"
      >
        <!-- Modal Content -->
        <div
          class="bg-white p-8 rounded-lg shadow-xl w-96 max-w-sm transform transition-all duration-300 ease-in-out animate-modal"
        >
          <h3 class="text-xl font-semibold mb-4 text-center">
            Tem certeza que deseja excluir esta venda?
          </h3>
          <p class="mb-6 text-gray-600">Essa ação não pode ser desfeita.</p>
          <div class="flex justify-between">
            <button
              @click="deleteVenda(detalhesVenda.id)"
              class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 w-full mr-2"
            >
              Comfirmar
            </button>
            <button
              @click="closeDeleteConfirmationModal"
              class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 w-full"
            >
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
import { notify } from '@/Plugins/Notify';

export default {
  props: {
    isModalOpen: Boolean,
    vendaId: Number,
  },
  data() {
    return {
      detalhesVenda: null,
      isDeleteModalOpen: false,
    };
  },
  watch: {
    vendaId(newId) {
      if (newId) {
        this.fetchDetalhesVenda(newId);
      }
    },
  },
  methods: {
    // Função para buscar os detalhes da venda
    async fetchDetalhesVenda(vendaId) {
      try {
        const response = await axios.get(`/api/vendas/detalhes/${vendaId}`);
        if (response.data.success) {
          this.detalhesVenda = response.data.venda;
        }
      } catch (error) {
        console.error('Erro ao buscar os detalhes da venda:', error);
      }
    },
    closeModal() {
      this.$emit('update:isModalOpen', false);
    },

    // Função para abrir a modal de confirmação de exclusão
    openDeleteConfirmationModal() {
      this.isDeleteModalOpen = true;
    },

    // Função para fechar a modal de confirmação
    closeDeleteConfirmationModal() {
      this.isDeleteModalOpen = false;
    },
    // Função para excluir a venda

    async deleteVenda(vendaId) {
      try {
        // Excluir a venda
        const response = await axios.delete(`/api/vendas/deletar/${vendaId}`);

        if (response.data.success) {
          // Excluída com sucesso, feche o modal ou faça algo.
          this.$emit('update:isModalOpen', false);
          this.$emit('updateComponents');
          notify.success('Venda excluída com sucesso');
        } else {
          notify.error('Erro ao excluir a venda');
        }
      } catch (error) {
        console.error('Erro ao excluir a venda:', error);
        notify.error('Erro ao excluir a venda');
      } finally {
        this.isDeleteModalOpen = false;
      }
    },
  },
};
</script>

<style scoped>
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

.close-button {
  transition: all 0.2s ease-in-out;
  border-radius: 50%; /* Deixa o botão arredondado */
  padding: 5px; /* Adiciona espaço interno suficiente para o ícone */
  background-color: transparent; /* Fundo transparente por padrão */
  border: 2px solid #cccccc00; /* Adiciona uma borda para o botão */
  display: flex;
  justify-content: center;
  align-items: center;
}

.close-button:hover {
  color: #ffffff; /* Cor do ícone ao passar o mouse */
  background-color: red; /* Fundo vermelho */
  transform: scale(1.1); /* Aumenta o tamanho ao passar o mouse */
  box-shadow: 0px 4px 10px rgba(16, 16, 16, 0.005); /* Adiciona sombra com tom de vermelho */
}

.close-button i {
  font-size: 32px; /* Aumenta o tamanho do ícone "X" */
}

.slide-from-right {
  animation: slide-in 0.3s ease-out;
}
.slide-to-right {
  animation: slide-out 0.3s ease-in;
}

@keyframes slide-in {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(0);
  }
}
@keyframes slide-out {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(100%);
  }
}

.modal-content {
  position: relative;
}
</style>
