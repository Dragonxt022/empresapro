<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <h2 class="text-xl font-bold text-gray-800 mb-4">Confirmação</h2>
      <p class="text-gray-600 text-base">
        Tem certeza que deseja finalizar a venda?
      </p>
      <div class="mt-6 flex justify-center space-x-4">
        <button
          @click="confirmAction"
          class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 rounded shadow-md transition-transform transform hover:scale-105"
        >
          Sim
        </button>
        <button
          @click="cancelAction"
          class="bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-2 rounded shadow-md transition-transform transform hover:scale-105"
        >
          Não
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
const emit = defineEmits(['confirm', 'close']);

const confirmAction = () => {
  emit('confirm');
  emit('close');
};

const cancelAction = () => {
  emit('close');
};
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-content {
  background-color: #ffffff;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
  text-align: center;
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
