<script setup>
import IconAnalytcPreto from '@/Components/Icons/IconAnalytcPreto.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import StatusCaixa from '@/Components/Elementos/StatusCaixa.vue';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
import HistoricoVendas from '@/Components/Elementos/HistoricoVendas.vue';

const emit = defineEmits();

function updateComponets() {
  console.log('Atualizando componentes');
  router.visit(window.location.href, {
    preserveScroll: true, // Mantém a posição do scroll
    preserveState: false, // Atualiza o estado do componente
  });
}
</script>

<template>
  <AppLayout title="Histórico">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <h1 class="text-2xl font-bold text-sky-500 flex items-center">
                  <IconAnalytcPreto class="h-6 w-6 mr-2" />
                  Histórico de Vendas
                </h1>
                <p class="text-gray-600 mt-2">
                  Visualize o histórico de vendas realizadas na sua empresa.
                </p>
              </div>
            </div>

            <StatusCaixa @updateComponents="updateComponets" />

            <!-- Tabela de vendas -->
            <HistoricoVendas @updateComponents="updateComponets" />
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped></style>
