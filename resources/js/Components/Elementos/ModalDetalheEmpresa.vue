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
              <span class="text-gray-800">Detalhes da Empresa</span>
            </h2>
            <button
              @click="closeModal"
              class="close-button absolute top-4 right-4 text-gray-500"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Empresa Detalhes -->
          <div v-if="detalhesEmpresa">
            <div
              class="grid grid-cols-8 gap-4 mb-6 flex justify-between text-center"
            >
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Nome</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesEmpresa.name }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">CNPJ</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesEmpresa.cnpj }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Endereço</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesEmpresa.address }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Celular</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesEmpresa.phone }}
                </p>
              </div>
            </div>

            <!-- Resumo de Contato -->
            <div class="border-t pt-4 mb-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span class="text-gray-800">Resumo de Contato</span>
              </h3>
              <div class="grid grid-cols-2 gap-4">
                <div class="text-gray-600">E-mail</div>
                <div class="font-semibold text-gray-800 text-right">
                  {{ detalhesEmpresa.email }}
                </div>

                <div class="text-gray-600">Website</div>
                <div class="font-semibold text-blue-500 text-right">
                  <a :href="detalhesEmpresa.website" target="_blank">
                    {{ detalhesEmpresa.website }}
                  </a>
                </div>
              </div>
            </div>

            <!-- Resumo de Funcionários -->
            <!-- <div class="border-t pt-4 mb-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span>Funcionários</span>
                <span class="text-gray-500 text-sm">
                  ({{ detalhesEmpresa.funcionarios.length }} funcionários)
                </span>
              </h3>
              <ul>
                <li
                  v-for="(funcionario, index) in detalhesEmpresa.funcionarios"
                  :key="index"
                  class="flex justify-between py-1"
                >
                  <span>{{ funcionario.nome }}</span>
                  <span class="font-semibold">{{ funcionario.cargo }}</span>
                </li>
              </ul>
            </div> -->

            <!-- Botão para excluir a empresa -->
            <div class="mt-6 flex justify-center">
              <button
                class="text-red-500 px-4 py-2 rounded-md flex items-center font-bold"
                @click="openDeleteConfirmationModal"
              >
                <i class="fa fa-trash text-red-500"></i>
                <span class="me-2">Excluir Empresa</span>
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
            Tem certeza que deseja excluir esta empresa?
          </h3>
          <p class="mb-6 text-gray-600">Essa ação não pode ser desfeita.</p>
          <div class="flex justify-between">
            <button
              @click="deleteEmpresa(detalhesEmpresa.id)"
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
    empresaId: Number,
  },
  data() {
    return {
      detalhesEmpresa: null,
      isDeleteModalOpen: false,
    };
  },
  watch: {
    empresaId(newId) {
      if (newId) {
        this.fetchDetalhesEmpresa(newId);
      }
    },
  },
  methods: {
    // Função para buscar os detalhes da empresa
    async fetchDetalhesEmpresa(empresaId) {
      try {
        const response = await axios.get(`/api/empresas/detalhes/${empresaId}`);
        if (response.data.success) {
          this.detalhesEmpresa = response.data.empresa;
        }
      } catch (error) {
        console.error('Erro ao buscar os detalhes da empresa:', error);
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

    // Função para excluir a empresa
    async deleteEmpresa(empresaId) {
      try {
        const response = await axios.delete(
          `/api/empresas/deletar/${empresaId}`
        );
        if (response.data.success) {
          this.$emit('update:isModalOpen', false);
          this.$emit('updateComponents');
          notify.success('Empresa excluída com sucesso');
        } else {
          notify.error('Erro ao excluir a empresa');
        }
      } catch (error) {
        console.error('Erro ao excluir a empresa:', error);
        notify.error('Erro ao excluir a empresa');
      } finally {
        this.isDeleteModalOpen = false;
      }
    },
  },
};
</script>

<style scoped>
/* Estilo similar ao anterior, adaptado para a empresa */
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

.animate-modal {
  animation: modalFadeIn 0.4s ease-out;
}

.close-button {
  transition: all 0.2s ease-in-out;
  border-radius: 50%;
  padding: 5px;
  background-color: transparent;
  border: 2px solid #cccccc00;
  display: flex;
  justify-content: center;
  align-items: center;
}

.close-button:hover {
  color: #ffffff;
  background-color: red;
  transform: scale(1.1);
  box-shadow: 0px 4px 10px rgba(16, 16, 16, 0.005);
}

.close-button i {
  font-size: 32px;
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
