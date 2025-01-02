<template>
  <div>
    <!-- Sidebar -->
    <aside
      class="bg-blue-900 shadow-lg h-screen w-16 md:w-[75px] flex flex-col"
    >
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
              @click.prevent="navigateTo('dashboard')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-blue-600 hover:text-white transition"
            >
              <IconHomeBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Início</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="navigateTo('historico')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-blue-600 hover:text-white transition"
            >
              <IconAnalytcBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Histórico</span>
            </a>
          </li>
          <li>
            <a
              href="#"
              @click.prevent="navigateTo('ajustes')"
              class="flex flex-col items-center px-4 py-2 text-white hover:bg-blue-600 hover:text-white transition"
            >
              <IconCofingBranco class="h-6 w-6 mb-1" />
              <span class="text-xs md:text-sm">Ajustes</span>
            </a>
          </li>
        </ul>
      </nav>
    </aside>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'; // Importa o router diretamente
import IconAnalytcBranco from './Icons/IconAnalytcBranco.vue';
import IconHomeBranco from './Icons/IconHomeBranco.vue';
import IconCofingBranco from './Icons/IconCofingBranco.vue';

const navigateTo = (routeName) => {
  router.visit(route(routeName), {
    preserveScroll: true, // Mantém o scroll atual
    preserveState: false, // Reseta o estado
  });
};
</script>

<style scoped>
/* Personalizações adicionais, se necessário */
</style>
