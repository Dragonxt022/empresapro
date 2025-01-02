<template>
  <div class="modal-wrapper" v-if="isVisible">
    <div class="content-wrapper">
      <section>
        <div class="grid grid-cols-1 lg:grid-cols-[450px_auto] gap-1 p-1">
          <!-- Card Esquerdo -->
          <div class="bg-white shadow-sm rounded-lg p-2 flex flex-col h-full">
            <MesaNameEdit
              :mesaId="mesaId"
              :nome="mesaNome"
              @nome-alterado="handleNomeAlterado"
              @close="closePanel"
            />
            <div v-if="isLoading" class="loading-overlay">
              <div class="spinner"></div>
            </div>
            <div v-else class="h-96 overflow-y-scroll mt-3 flex-grow">
              <!-- O restante do conteúdo -->
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import MesaNameEdit from './Elementos/MesaNameEdit.vue';

const mesaId = ref(null);
const mesaNome = ref('Mesa Padrão'); // Nome inicial da mesa

// Método para lidar com o evento de alteração do nome
const handleNomeAlterado = (novoNome) => {
  mesaNome.value = novoNome; // Atualiza o nome da mesa
  console.log(`Novo nome da mesa: ${novoNome}`);
};
</script>
