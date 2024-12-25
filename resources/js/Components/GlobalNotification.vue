<script setup>
import { computed, watch, nextTick } from 'vue';
import { useToast } from 'vue-toastification';

// Recebendo a prop flash
const props = defineProps({
  flash: {
    type: Object,
    default: () => ({}), // Valor default vazio
  },
});

// Inicializando o toast
const toast = useToast();

// Computando o flash para garantir reatividade
const flash = computed(() => props.flash);

// Observando mudanças na prop flash e exibindo o toast quando uma mensagem for encontrada
watch(flash, async (newFlash) => {
  if (newFlash?.message) {
    await nextTick(); // Garante que a UI foi atualizada antes de disparar o toast
    toast[newFlash.type || 'info'](newFlash.message, {
      position: 'top-center',
      timeout: 5000,
    });
  }
}, { immediate: true }); // O immediate: true faz o watch ser acionado imediatamente
</script>

<template>
  <!-- Renderiza a mensagem flash diretamente na página -->
  <div v-if="flash.value?.message" class="flash-message">
    <p :class="flash.value.type || 'info'">{{ flash.value.message }}</p>
  </div>
</template>

<style scoped>
.flash-message {
  padding: 10px;
  margin: 10px 0;
  border-radius: 5px;
  font-size: 14px;
}

.flash-message.info {
  background-color: #e0f7fa;
  color: #00796b;
}

.flash-message.success {
  background-color: #c8e6c9;
  color: #388e3c;
}

.flash-message.error {
  background-color: #ffcdd2;
  color: #d32f2f;
}

.flash-message.warning {
  background-color: #fff3e0;
  color: #f57c00;
}
</style>
