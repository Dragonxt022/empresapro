<!-- pages/Dashboard.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import MesaComponent from '@/Components/MesaComponent.vue';
import SalesPanel from '@/Components/SalesPanel.vue';
import { onMounted, ref, watch } from 'vue';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
import axios from 'axios';

import IconShopPrimary from '@/Components/Icons/IconShopPrimary.vue';
import ModalConfiguracaoMesa from '@/Components/Elementos/ModalConfiguracaoMesa.vue';

const emit = defineEmits();
const TABS = {
  MESAS: 'mesas',
  BALCAO: 'balcao',
};

const activeTab = ref(TABS.MESAS);
const MesaSelecionada = ref(null);
const caixaAberto = ref(false);
const isLoading = ref(false);

const props = defineProps({
  mesas: {
    type: Array,
    required: true,
  },
  products: {
    type: Object,
    required: true,
  },
  categories: {
    type: Array,
    required: true,
  },
  filters: {
    type: Object,
    required: true,
  },
});

let mesasKey = ref(0);

function selectMesa(data) {
  MesaSelecionada.value = data;
}

function updateMesa() {
  mesasKey.value++;
  console.log('Mesa atualizada');
}

const checkCaixaStatus = async () => {
  try {
    isLoading.value = true;
    const response = await axios.get('/api/caixa/status');
    caixaAberto.value = response.data.status === 'fechado';
  } catch (error) {
    console.error('Erro ao verificar o status do caixa:', error);
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
    navigateTo('dashboard');
  }
});

// Verifica o status do caixa ao carregar a página
onMounted(() => {
  checkCaixaStatus();
});
</script>

<template>
  <AppLayout title="Mesas">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <!-- Sidebar está contido no layout, então não é necessário aqui -->
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <!-- Seção de conteúdo -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- Título -->
              <div class="col-12">
                <h1 class="text-2xl font-bold text-sky-500 flex items-center">
                  <!-- Ícone -->
                  <IconShopPrimary class="h-6 w-6 mr-2" />
                  Caixa
                </h1>
                <section class="mt-4">
                  <!-- Navegação de Abas -->
                  <div class="flex justify-between items-center">
                    <div class="flex space-x-4">
                      <button
                        class="px-4 py-3 rounded-t-lg focus:outline-none"
                        :class="{
                          'bg-white text-blue-600 font-semibold':
                            activeTab === TABS.MESAS,
                          'bg-gray-100 text-gray-600': activeTab !== TABS.MESAS,
                        }"
                        @click="activeTab = TABS.MESAS"
                      >
                        Mesas
                      </button>
                      <button
                        class="px-4 py-3 rounded-t-lg focus:outline-none"
                        :class="{
                          'bg-white text-blue-600 font-semibold':
                            activeTab === TABS.BALCAO,
                          'bg-gray-100 text-gray-600':
                            activeTab !== TABS.BALCAO,
                        }"
                        @click="activeTab = TABS.BALCAO"
                      >
                        Balcão
                      </button>
                    </div>

                    <div class="flex gap-2">
                      <ModalConfiguracaoMesa @updateMesa="updateMesa" />
                    </div>
                  </div>

                  <!-- Conteúdo das Abas -->
                  <section class="content mt-4">
                    <div class="container-fluid">
                      <div class="row">
                        <div v-if="activeTab === TABS.MESAS" class="col-12">
                          <div class="bg-white shadow rounded-lg p-6">
                            <MesaComponent
                              :mesas="mesas"
                              @mesa-click="selectMesa"
                              :key="mesasKey"
                            />
                            <SalesPanel
                              :products="products"
                              :categories="categories"
                              :filters="filters"
                              :Mesa-selecionada="MesaSelecionada"
                              @updateMesa="updateMesa"
                            />
                          </div>
                        </div>

                        <div v-if="activeTab === TABS.BALCAO" class="col-12">
                          <div class="bg-white shadow rounded-lg p-6">
                            <p class="text-gray-500">
                              Nenhuma funcionalidade implementada ainda.
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </section>
                </section>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
