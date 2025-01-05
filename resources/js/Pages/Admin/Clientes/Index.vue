<template>
  <AppLayoutAdmin title="Clientes">
    <!-- Conteúdo principal -->
    <div class="flex flex-1">
      <div class="content-wrapper flex-1 bg-gray-100 p-4">
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <h1 class="text-2xl font-bold text-red-900 flex items-center">
                  Clientes
                </h1>
                <p class="text-gray-600 mt-2">Bem-vindo, Administrador!</p>
              </div>
            </div>

            <ListaEmpresas />
          </div>
        </section>
      </div>
    </div>
  </AppLayoutAdmin>
</template>

<script setup>
import ListaEmpresas from '@/Components/Elementos/ListaEmpresas.vue';
import AppLayoutAdmin from '@/Layouts/AppLayoutAdmin.vue';

// Este arquivo não precisa de lógica adicional no momento
</script>

<style scoped>
/* Estilos personalizados aqui */
</style>
