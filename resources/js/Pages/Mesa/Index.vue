<script setup>
import MesaComponent from '@/Components/MesaComponent.vue';
import SalesPanel from '@/Components/SalesPanel.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const TABS = {
  MESAS: 'mesas',
  BALCAO: 'balcao',
};

const activeTab = ref(TABS.MESAS);
const MesaId = ref(null);

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

function selectMesa(id) {
  MesaId.value = id;
}
</script>

<template>
  <AppLayout title="Dashboard">
    <div class="content-wrapper">
      <!-- Cabeçalho -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Gerenciar</h1>
            </div>
          </div>
        </div>
      </section>

      <!-- Navegação de Abas -->
      <div class="border-b border-gray-200">
        <nav class="flex space-x-4 px-4" role="tablist">
          <button
            :class="
              activeTab === TABS.MESAS
                ? 'text-purple-600 border-b-4 border-purple-600'
                : 'text-gray-500'
            "
            class="px-4 py-2 text-sm font-medium focus:outline-none"
            @click="activeTab = TABS.MESAS"
            role="tab"
            :aria-selected="activeTab === TABS.MESAS"
          >
            Mesas
          </button>
          <button
            :class="
              activeTab === TABS.BALCAO
                ? 'text-purple-600 border-b-4 border-purple-600'
                : 'text-gray-500'
            "
            class="px-4 py-2 text-sm font-medium focus:outline-none"
            @click="activeTab = TABS.BALCAO"
            role="tab"
            :aria-selected="activeTab === TABS.BALCAO"
          >
            Balcão
          </button>
        </nav>
      </div>

      <!-- Conteúdo das Abas -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div v-if="activeTab === TABS.MESAS" class="col-12">
              <MesaComponent :mesas="mesas" @mesa-click="selectMesa" />
              <SalesPanel
                :products="products"
                :categories="categories"
                :filters="filters"
                :mesa-id="MesaId"
              />
            </div>

            <div v-if="activeTab === TABS.BALCAO" class="col-12">
              <div class="card">
                <div class="card-body">
                  <p>Nenhuma funcionalidade implementada ainda.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </AppLayout>
</template>
