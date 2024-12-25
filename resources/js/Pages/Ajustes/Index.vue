<script setup>
import CardComponent from '@/Components/Cards/CardComponent.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente

const navigateTo = (routeName) => {
  router.visit(route(routeName), {
    preserveScroll: true, // Mantém o scroll atual
    preserveState: false, // Reseta o estado
  });
};
</script>

<template>
  <AppLayout title="Ajustes">
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header py-4">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Ajustes</h1>
            </div>
          </div>
        </div>
        <!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <!-- Cadastros Section -->
          <div class="row mb-2 mt-5">
            <div class="col-sm-6">
              <h4>Cadastros</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
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
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-tags"
                    title="Categorias"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>
                <!-- Card 3 -->
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-user-friends"
                    title="Clientes"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>
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
          <div class="row mb-2 mt-5">
            <div class="col-sm-6">
              <h4>Relatórios</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Card 1 -->
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-cash-register"
                    title="Caixas"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>
                <!-- Card 2 -->
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-chart-pie"
                    title="Painel"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>
              </div>
            </div>
          </div>

          <!-- Configurações Section -->
          <div class="row mb-2 mt-5">
            <div class="col-sm-6">
              <h4>Configurações</h4>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Card 1 -->
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-tools"
                    title="Geral"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>

                <!-- Card 4 -->
                <a @click.prevent="navigateTo('produtos')" class="nav-link">
                  <CardComponent
                    icon="fas fa-users-cog"
                    title="Usuários"
                    bgColor="bg-white"
                    textColor="text-blue-500"
                  />
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </AppLayout>
</template>
