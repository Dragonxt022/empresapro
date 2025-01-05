<template>
  <div>
    <!-- Sidebar -->
    <aside class="bg-red-700 shadow-lg h-screen w-16 md:w-[75px] flex flex-col">
      <!-- Sidebar User -->
      <div class="flex items-center justify-center p-4 mt-4 cursor-pointer">
        <a href="#" @click.prevent="navigateTo('dashboard')">
          <div class="w-10 h-10 bg-gray-300 rounded-full">
            <img src="/storage/images/iconsistema.png" alt="Logo do Sistema" />
          </div>
        </a>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-6 flex-1">
        <ul class="space-y-4">
          <li>
            <a
              href="#"
              @click.prevent="navigateTo('admin.dashboard')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-red-600 hover:text-white transition"
            >
              <IconHomeBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Início</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="navigateTo('admin.assinaturas')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-red-600 hover:text-white transition"
            >
              <IconAnalytcBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Planos</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="navigateTo('admin.clientes')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-red-600 hover:text-white transition"
            >
              <IconCofingBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Clientes</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="logout"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-red-600 hover:text-white transition"
            >
              <i class="fas fa-sign-out-alt"></i>
              <span class="text-xs md:text-sm">Sair</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
import { Inertia } from '@inertiajs/inertia'; // Importa Inertia para usar post

import IconAnalytcBranco from './Icons/IconAnalytcBranco.vue';
import IconHomeBranco from './Icons/IconHomeBranco.vue';
import IconCofingBranco from './Icons/IconCofingBranco.vue';

const navigateTo = (routeName) => {
  router.visit(route(routeName), {
    preserveScroll: true, // Mantém o scroll atual
    preserveState: false, // Reseta o estado
  });
};

const logout = () => {
  Inertia.post(route('logout')); // Envia um POST para a rota de logout
};
</script>

<style scoped>
/* Personalizações adicionais, se necessário */
</style>
