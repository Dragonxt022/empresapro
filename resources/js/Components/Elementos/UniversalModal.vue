<template>
  <div
    v-if="visible"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div
      :class="{
        'animate-slide-up': isEntering,
        'animate-slide-down': isExiting,
      }"
      class="bg-white rounded-lg shadow-lg p-6 w-96"
    >
      <h2 class="text-lg font-bold text-gray-800 mb-4 text-center">
        {{ title || 'Motivo da Ação' }}
      </h2>
      <p class="text-gray-600 text-center mb-6">
        {{ description || 'Deseja confirmar ou cancelar esta ação?' }}
      </p>
      <div class="flex justify-end space-x-4">
        <button
          @click="handleCancel"
          class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium"
        >
          {{ cancelText || 'Cancelar' }}
        </button>
        <button
          @click="handleConfirm"
          class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium"
        >
          {{ confirmText || 'Confirmar' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

// Props
defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    default: '',
  },
  description: {
    type: String,
    default: '',
  },
  cancelText: {
    type: String,
    default: '',
  },
  confirmText: {
    type: String,
    default: '',
  },
});

// Emits
const emit = defineEmits(['confirm', 'cancel', 'close']);

// Internal states for animation control
const isEntering = ref(false);
const isExiting = ref(false);

// Handle modal opening animation
watch(
  () => visible,
  (newVal) => {
    if (newVal) {
      isEntering.value = true;
      isExiting.value = false;
    } else {
      isExiting.value = true;
      setTimeout(() => {
        isEntering.value = false;
        emit('close');
      }, 300);
    }
  }
);

// Cancel and confirm handlers
const handleCancel = () => {
  emit('cancel');
  isExiting.value = true;
  setTimeout(() => emit('close'), 300);
};

const handleConfirm = () => {
  emit('confirm');
  isExiting.value = true;
  setTimeout(() => emit('close'), 300);
};
</script>

<style scoped>
/* Animations */
@keyframes slideUp {
  from {
    transform: translateY(100%);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

@keyframes slideDown {
  from {
    transform: translateY(0);
    opacity: 1;
  }
  to {
    transform: translateY(100%);
    opacity: 0;
  }
}

.animate-slide-up {
  animation: slideUp 0.3s ease-out forwards;
}

.animate-slide-down {
  animation: slideDown 0.3s ease-in forwards;
}
</style>
