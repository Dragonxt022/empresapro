<!-- pages/Dashboard.vue -->
<script setup>
import IconHomePreto from '@/Components/Icons/IconHomePreto.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
  <AppLayout title="Usuarios">
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
                  Usuarios
                </h1>
                <p class="text-gray-600 mt-4">
                  Bem-vindo ao sistema de usuarios!
                </p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>
