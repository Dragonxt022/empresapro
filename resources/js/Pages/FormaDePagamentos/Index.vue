<!-- pages/Dashboard.vue -->
<script setup>
import IconHomePreto from '@/Components/Icons/IconHomePreto.vue';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useModalStore } from '@/store/store';
import CadastroMetoPagamento from '@/Components/CadastroMetoPagamento.vue';
import EditarMetodoPagamento from '@/Components/EditarMetodoPagamento.vue';

// Recebendo os props do Laravel
const props = defineProps({
  paymentMethods: {
    type: Array,
    required: true,
  },
});

const tab = ref('Metodos');

// Referência da store
const modalStore = useModalStore();

const ativarMetodoPagamento = () => {
  modalStore.activateComponent('metodo_pagamento');
};

// Descrições para os tipos de pagamento
const typeDescriptions = {
  D: 'Dinheiro',
  C: 'Crédito',
  T: 'Débito',
  P: 'Pix',
};
</script>

<template>
  <AppLayout title="Formas de Pagamento">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <!-- Sidebar está contido no layout, então não é necessário aqui -->
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <!-- Seção de conteúdo -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- Adicione o conteúdo aqui -->
              <div class="col-12">
                <h1 class="text-2xl font-bold text-sky-500 flex items-center">
                  <!-- Ícone -->
                  <IconHomePreto class="h-6 w-6 mr-2" />
                  Formas de Pagamento
                </h1>
                <div class="mt-4"></div>
                <div class="container-fluid">
                  <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                      <button
                        class="px-4 py-3 rounded-t-lg focus:outline-none"
                        :class="{
                          'bg-white text-blue-600 font-semibold':
                            tab === 'Metodos',
                          'bg-gray-100 text-gray-600': tab !== 'Metodos',
                        }"
                        @click="tab = 'Metodos'"
                      >
                        Metodos
                      </button>
                    </div>

                    <div class="flex gap-2">
                      <button
                        @click="ativarMetodoPagamento"
                        class="px-4 py-2 bg-blue-500 text-white rounded"
                      >
                        <i class="fa-solid fa-plus"></i>
                        Nova Forma de Pagamento
                      </button>

                      <transition name="slide">
                        <CadastroMetoPagamento
                          v-if="
                            modalStore.activeComponent === 'metodo_pagamento'
                          "
                          :paymentMethods="paymentMethods"
                        />
                      </transition>

                      <transition name="slide">
                        <EditarMetodoPagamento
                          v-if="
                            modalStore.activeComponent ===
                            'editar_metodo_pagamento'
                          "
                          :paymentMethods="modalStore.selectedPaymentMethod"
                        />
                      </transition>
                    </div>
                  </div>
                  <!-- Tabela -->
                  <div class="bg-white shadow-md rounded-lg p-4">
                    <table
                      class="table-auto w-full text-left border-collapse mt-4 shadow-md rounded-lg overflow-hidden"
                    >
                      <thead class="bg-gray-100 border-b">
                        <tr>
                          <th class="px-4 py-3">Nome</th>
                          <th
                            class="px-4 py-3 text-center hidden sm:table-cell"
                          >
                            Tipo
                          </th>
                          <th
                            class="px-4 py-3 text-center hidden lg:table-cell"
                          >
                            Taxa (%)
                          </th>
                          <th
                            class="px-4 py-3 text-center hidden xl:table-cell"
                          >
                            Conta Bancária
                          </th>
                          <th
                            class="px-4 py-3 text-center hidden xl:table-cell"
                          >
                            Status
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="method in paymentMethods"
                          :key="method.id"
                          @click="
                            modalStore.activateComponent(
                              'editar_metodo_pagamento'
                            );
                            modalStore.setSelectedPaymentMethod(method);
                          "
                          class="border-b cursor-pointer"
                        >
                          <td class="px-4 py-3">{{ method.name }}</td>
                          <td
                            class="px-3 py-3 text-center hidden md:table-cell"
                          >
                            {{
                              typeDescriptions[method.type] || 'Desconhecido'
                            }}
                          </td>
                          <td
                            class="px-4 py-3 text-center hidden lg:table-cell"
                          >
                            {{ method.fee_percentage }}%
                          </td>
                          <td
                            class="px-4 py-3 text-center hidden xl:table-cell"
                          >
                            {{ method.bank_account || 'Não informado' }}
                          </td>
                          <td
                            class="px-4 py-3 text-center hidden xl:table-cell"
                          >
                            {{ method.is_active ? 'Ativo' : 'Inativo' }}
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<style>
.dropdown-menu {
  display: none;
}
.dropdown:hover .dropdown-menu {
  display: block;
}

/* Efeito de brilho suave */
tr:hover {
  box-shadow: 0 0 0 2px rgba(0, 115, 255, 0.8); /* Brilho suave ao redor da linha */
  border-radius: 6px; /* Bordas arredondadas */
  transition: box-shadow 0.3s ease-in-out; /* Transição suave */
}
@keyframes slideFromRight {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(0);
  }
}

/* Animação de deslizar da direita para a saída */
@keyframes slideToRight {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(100%);
  }
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  animation: slideToRight 0.3s ease-in;
}

.slide-fade-enter,
.slide-fade-leave-to {
  opacity: 0;
}
</style>
