<template>
  <div class="card">
    <div class="card-body">
      <div class="grid grid-cols-5 gap-4">
        <!-- Card para mesas -->
        <div
          class="flex bg-gray-100 shadow-md rounded-lg overflow-hidden cursor-pointer"
          v-for="mesa in mesas"
          :key="mesa.id"
          @click="alterarStatusMesa(mesa)"
        >
          <div
            :class="{
              'w-2 bg-green-500': mesa.status === 'aberto',
              'w-2 bg-yellow-500': mesa.status === 'em_espera',
              'w-2 bg-gray-400': mesa.status === 'livre',
            }"
          ></div>

          <div class="flex-1 p-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  :class="{
                    'w-6 h-6 text-green-500': mesa.status === 'aberto',
                    'w-6 h-6 text-yellow-500': mesa.status === 'em_espera',
                    'w-6 h-6 text-gray-400': mesa.status === 'livre',
                  }"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div
                :class="{
                  'text-green-500': mesa.status === 'aberto',
                  'text-yellow-500': mesa.status === 'em_espera',
                  'text-gray-400': mesa.status === 'livre',
                }"
                class="text-sm font-bold"
              >
                {{
                  mesa.status === 'aberto'
                    ? 'Aberto'
                    : mesa.status === 'em_espera'
                    ? 'Clique para confirmar'
                    : 'Livre'
                }}
              </div>
            </div>
            <div class="mt-4">
              <span
                class="mt-2 font-medium text-gray-800 text-lg font-semibold"
              >
                {{ mesa.nome }}
              </span>
            </div>
            <div
              v-if="mesa.status === 'em_espera'"
              class="mt-2 text-gray-500 text-sm"
            >
              <span>Clique novamente para abrir a mesa.</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { defineProps, defineEmits } from 'vue';
import { useModalStore } from '@/store/store';

const emit = defineEmits(); // Definir a função emit
const modalStore = useModalStore();

const props = defineProps({
  mesas: {
    type: Array,
    required: true,
  },
});

const mesas = ref(props.mesas);

// Função para alterar o status da mesa com um único clique
const alterarStatusMesa = (mesa) => {
  if (mesa.status === 'livre') {
    mesa.status = 'em_espera'; // Muda o status para 'em_espera'
  } else if (mesa.status === 'em_espera') {
    // Se a mesa estiver em espera, chama a API para abrir a mesa
    abrirMesa(mesa);
  } else if (mesa.status === 'aberto') {
    // Emitindo o ID da mesa para o componente pai
    emit('mesa-click', mesa.id); // Usar emit
    modalStore.openSalesPanel({ mesa });
  }
};

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
