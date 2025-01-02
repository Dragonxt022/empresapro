<template>
  <div class="modal-overlay">
    <div class="modal-content">
      <p class="text-lg font-bold">Tem certeza que deseja Salvar a venda?</p>
      <div class="mt-4 flex justify-between">
        <button
          @click="confirmAction"
          class="bg-green-500 text-white p-2 w-full mr-2 rounded"
        >
          Sim
        </button>
        <button
          @click="cancelAction"
          class="bg-red-500 text-white w-full p-2 rounded"
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
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  width: 500px;
  height: 200px;
}
</style>
