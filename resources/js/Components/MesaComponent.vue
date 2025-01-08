<template>
  <div class="card">
    <div class="card-body">
      <div class="grid grid-cols-5 gap-4">
        <!-- Card para mesas -->
        <div
          class="flex bg-slate-50 shadow-md rounded-lg overflow-hidden cursor-pointer"
          v-for="mesa in mesas"
          :key="mesa.id"
          @click="alterarStatusMesa(mesa)"
        >
          <!-- Barra de status -->
          <div
            :class="{
              'w-2 bg-green-500': mesa.status === 'aberto',
              'w-2 bg-yellow-500': mesa.status === 'em_espera',
              'w-2 bg-blue-500': mesa.status === 'pendente',
              'w-2 bg-gray-400': mesa.status === 'livre',
            }"
          ></div>

          <!-- Conteúdo da mesa -->
          <div class="flex-1 p-4">
            <div class="flex items-center justify-between">
              <!-- Ícones baseados no status -->
              <div class="flex items-center">
                <svg
                  v-if="mesa.status === 'livre'"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6 text-gray-400"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="1.5"
                >
                  <path
                    d="M5 12h14M12 5v14"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>

                <svg
                  v-else-if="mesa.status === 'aberto'"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6 text-green-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="1.5"
                >
                  <path
                    d="M9 12l2 2 4-4"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>

                <svg
                  v-else-if="mesa.status === 'em_espera'"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6 text-yellow-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="1.5"
                >
                  <path
                    d="M12 6v6h6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>

                <svg
                  v-else-if="mesa.status === 'pendente'"
                  xmlns="http://www.w3.org/2000/svg"
                  class="w-6 h-6 text-blue-500"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  stroke-width="1.5"
                >
                  <path d="M16.24 7.76a6 6 0 11-8.48 8.48 6 6 0 018.48-8.48z" />
                  <path
                    d="M12 8v4l2 2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </div>

              <!-- Texto baseado no status -->
              <div
                :class="{
                  'text-green-500': mesa.status === 'aberto',
                  'text-yellow-500': mesa.status === 'em_espera',
                  'text-blue-500': mesa.status === 'pendente',
                  'text-gray-400': mesa.status === 'livre',
                }"
                class="text-sm font-bold"
              >
                {{
                  mesa.status === 'aberto'
                    ? 'Aberto'
                    : mesa.status === 'em_espera'
                    ? 'Clique para abrir'
                    : mesa.status === 'pendente'
                    ? 'Pendente'
                    : 'Livre'
                }}
              </div>
            </div>

            <!-- Nome da mesa -->
            <div class="mt-4">
              <span
                class="mt-2 font-medium text-gray-800 text-lg font-semibold"
              >
                {{ mesa.nome }}
              </span>
            </div>

            <!-- Valor total e tempo aberto -->
            <div
              v-if="mesa.venda"
              class="flex items-center justify-between mt-2"
            >
              <div class="text-gray-500 font-semibold text-md">
                {{ mesa.venda.valor_total_formatado }}
              </div>
              <div
                class="flex items-center font-semibold text-gray-500 text-sm"
              >
                <IconTimePrimary />
                {{ mesa.venda.tempo_aberta }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { defineEmits } from 'vue';
import { useModalStore } from '@/store/store';
import IconTimePrimary from './Icons/IconTimePrimary.vue';

const emit = defineEmits(); // Definir a função emit

const modalStore = useModalStore();

const mesas = ref([]); // Inicializar as mesas como um array vazio

// Função para carregar as mesas da API
const carregarMesas = async () => {
  try {
    const response = await axios.get('/api/mesas/listar');
    if (response.data.success) {
      mesas.value = response.data.data;
    } else {
      console.error('Falha ao carregar mesas');
    }
  } catch (error) {
    console.error('Erro ao buscar mesas:', error);
  }
};

// Chama a função ao montar o componente
onMounted(() => {
  carregarMesas();
});

// Função para alterar o status da mesa com um único clique
const alterarStatusMesa = (mesa) => {
  if (mesa.status === 'livre') {
    mesa.status = 'em_espera'; // Muda o status para 'em_espera'
  } else if (mesa.status === 'em_espera') {
    // Se a mesa estiver em espera, chama a API para abrir a mesa
    abrirMesa(mesa);
    emit('mesa-click', { id: mesa.id, nome: mesa.nome });

    modalStore.openSalesPanel({ mesa });
  } else if (mesa.status === 'aberto') {
    // Emitindo o ID da mesa para o componente pai
    emit('mesa-click', { id: mesa.id, nome: mesa.nome });

    modalStore.openSalesPanel({ mesa });
  } else if (mesa.status === 'pendente') {
    // Emitindo o ID da mesa para o componente pai
    emit('mesa-click', { id: mesa.id, nome: mesa.nome });

    modalStore.openSalesPanel({ mesa });
  }
};

// Função para abrir a mesa
const abrirMesa = async (mesa) => {
  try {
    await axios.post(`/mesas/${mesa.id}/abrir`);
    // Atualiza a mesa no frontend
    mesa.status = 'aberto';
    modalStore.openSalesPanel({ mesa });
  } catch (error) {
    console.error('Erro ao abrir a mesa:', error);
  }
};
</script>

<style scoped>
/* Aqui ficam os estilos do seu componente */
</style>
