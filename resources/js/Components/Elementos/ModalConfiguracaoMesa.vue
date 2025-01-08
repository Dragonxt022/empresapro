<template>
  <div>
    <!-- Botão para abrir a modal de configurações -->
    <button @click="openModal" class="px-4 py-2 bg-blue-500 text-white rounded">
      <i class="fa-solid fa-plus"></i>
      Configurações
    </button>

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
            <h2 class="text-2xl font-semibold text-gray-800">
              Configurações de Mesas
            </h2>
            <button
              @click="closeModal"
              class="close-button absolute top-4 right-4 text-gray-500"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Corpo do Modal -->
          <div class="mb-6">
            <label for="mesaCount" class="block text-gray-600 text-sm">
              Número de Mesas
            </label>
            <input
              v-model="numMesas"
              id="mesaCount"
              type="number"
              min="1"
              max="50"
              class="w-full p-2 mt-2 border rounded-md"
              placeholder="Digite o número de mesas"
            />
          </div>

          <div class="flex justify-end">
            <button
              @click="generateMesas"
              class="px-4 py-2 bg-blue-500 text-white rounded"
            >
              Salvar
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
  data() {
    return {
      isModalOpen: false,
      numMesas: 1, // Inicialmente 1 mesa
    };
  },
  methods: {
    // Abre o modal
    openModal() {
      this.isModalOpen = true;
    },

    // Fecha o modal
    closeModal() {
      this.isModalOpen = false;
    },

    // Gera as mesas com base no número escolhido
    async generateMesas() {
      try {
        const response = await axios.post('/api/mesas/alterar', {
          num_mesas: this.numMesas,
        });

        // Exibe uma mensagem de sucesso

        this.$emit('updateMesa');
        notify.success(response.data.message);

        // Fecha o modal após gerar ou remover mesas
        this.closeModal();
      } catch (error) {
        notify.error(error.response.data.message);
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
