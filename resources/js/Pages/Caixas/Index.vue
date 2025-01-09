<script setup>
import IconAnalytcPreto from '@/Components/Icons/IconAnalytcPreto.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

import HistoricoCaixas from '@/Components/Elementos/HistoricoCaixas.vue';
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
                  Histórico de Caixas
                </h1>
                <p class="text-gray-600 mt-2">
                  Visualize o seu histórico de vendas realizadas por caixa de
                  sua empresa.
                </p>
              </div>
            </div>

            <!-- Tabela de vendas -->
            <HistoricoCaixas />
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped></style>
