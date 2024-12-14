<template>
    <div v-if="mostrarAlerta" class="relative">
      <!-- Ícone no canto superior direito -->
      <i class="fa-solid fa-circle-xmark fa-xs absolute top-0 right-0 text-red-500" title="Estoque Baixo"></i>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';  // Importando o 'computed' do Vue

  const props = defineProps({
    estoque: {
      type: Number,
      required: true,
    },
    limiteMinimo: {
      type: Number,
      required: true,
    }
  });

  // Verifica se o estoque está abaixo ou igual ao limite mínimo
  const mostrarAlerta = computed(() => {
    return props.estoque <= props.limiteMinimo;
  });
  </script>

  <style scoped>
  /* Estilos para o ícone de alerta */
  .text-red-500 {
    color: #ff0000;
  }

  .absolute {
    position: absolute;
  }

  .top-0 {
    top: -25px;
  }

  .right-0 {
    right: 35px;
  }
  </style>
