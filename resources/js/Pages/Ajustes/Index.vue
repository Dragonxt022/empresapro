<!-- pages/Dashboard.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CardComponent from '@/Components/Cards/CardComponent.vue';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
import IconCofingPrimary from '@/Components/Icons/IconCofingPrimary.vue';

const navigateTo = (routeName) => {
  router.visit(route(routeName), {
    preserveScroll: true, // Mantém o scroll atual
    preserveState: false, // Reseta o estado
  });
};
</script>

<template>
  <AppLayout title="Ajustes">
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
                <h1 class="text-2xl font-bold text-gray-600 flex items-center">
                  <!-- Ícone -->
                  <IconCofingPrimary class="h-6 w-6 mr-2" />
                  Ajustes
                </h1>
                <section class="mt-4">
                  <div class="row">
                    <div class="col-12">
                      <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3"
                      >
                        <!-- Card 1 -->
                        <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                          data-inertia-prefetch
                        >
                          <CardComponent
                            icon="fas fa-box"
                            title="Produtos"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>
                        <!-- Card 2 -->
                        <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-tags"
                            title="Categorias"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>
                        <!-- Card 3 -->
                        <!-- <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-user-friends"
                            title="Clientes"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a> -->
                        <!-- Card 4 -->
                        <a
                          @click.prevent="navigateTo('formas_pagamento')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fa-solid fa-wallet"
                            title="Fomrmas de Pagamento"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>
                      </div>
                    </div>
                  </div>

                  <!-- Relatórios Section -->
                  <div class="row mb-2 mt-[50px]">
                    <div class="col-sm-6">
                      <h4 class="text-2xl font-bold text-gray-600">
                        Relatórios
                      </h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6"
                      >
                        <!-- Card 1 -->
                        <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-cash-register"
                            title="Caixas"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>
                        <!-- Card 2 -->
                        <!-- <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-chart-pie"
                            title="Painel"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a> -->
                      </div>
                    </div>
                  </div>

                  <!-- Configurações Section -->
                  <!-- <div class="row mb-2 mt-[50px]">
                    <div class="col-sm-6">
                      <h4 class="text-2xl font-bold text-gray-800">
                        Configurações
                      </h4>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                      <div
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6"
                      >

                        <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-tools"
                            title="Geral"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>


                        <a
                          @click.prevent="navigateTo('produtos')"
                          class="nav-link"
                        >
                          <CardComponent
                            icon="fas fa-users-cog"
                            title="Usuários"
                            bgColor="bg-white"
                            textColor="text-blue-500"
                          />
                        </a>
                      </div>
                    </div>
                  </div> -->
                </section>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
