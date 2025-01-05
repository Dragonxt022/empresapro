<template>
  <AppLayoutAdmin title="Dashboard">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <h1 class="text-2xl font-bold text-red-900 flex items-center">
                  Dashboard
                </h1>
                <p class="text-gray-600 mt-2">Bem-vindo, Administrador!</p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayoutAdmin>
</template>

<script setup>
import AppLayoutAdmin from '@/Layouts/AppLayoutAdmin.vue';

// Este arquivo não precisa de lógica adicional no momento
</script>

<style scoped>
/* Estilos personalizados aqui */
</style>
