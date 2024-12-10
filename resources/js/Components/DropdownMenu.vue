<template>
    <div class="relative inline-block">
      <!-- Botão de 3 pontinhos -->
      <button @click="toggleDropdown" class="text-gray-500 hover:text-gray-600 text-3xl">
        <i class="fas fa-ellipsis-v"></i> <!-- Ícone maior -->
      </button>

      <!-- Menu Dropdown -->
      <ul v-show="isOpen" class="dropdown-menu absolute bg-white text-gray-700 shadow-md mt-1 rounded-lg z-50">
        <li>
          <button class="block px-4 py-2 text-sm">Editar</button>
        </li>
        <li>
          <button class="block px-4 py-2 text-sm">Adicionar/Remover Favorito</button>
        </li>
        <li>
          <button class="block px-4 py-2 text-sm">Excluir</button>
        </li>
      </ul>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';

  // Controla a visibilidade do menu dropdown
  const isOpen = ref(false);

  // Função para alternar a visibilidade do dropdown
  const toggleDropdown = (event) => {
    event.stopPropagation();  // Impede que o clique no botão feche o dropdown
    isOpen.value = !isOpen.value;
  };

  // Fechar o dropdown se o clique for fora
  const closeOnOutsideClick = (event) => {
    if (!event.target.closest('.dropdown-menu') && !event.target.closest('button')) {
      isOpen.value = false;
    }
  };

  // Adiciona o evento de clique fora do menu
  document.addEventListener('click', closeOnOutsideClick);
  </script>

  <style scoped>
  /* Z-index maior para garantir que o dropdown fique sobre outros elementos */
  .dropdown-menu {
    z-index: 50;
  }
  </style>
