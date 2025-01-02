<template>
  <div
    v-if="modalStore.activeComponent === 'metodo_pagamento'"
    class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end"
  >
    <div
      class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/2 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out"
    >
      <button
        @click="resetarFormulario"
        class="close-button absolute top-4 right-4 text-gray-500"
      >
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-2xl mb-4">
        <i class="fa-solid fa-credit-card"></i>
        Nova Forma de Pagamento
      </h2>
      <form @submit.prevent="submitForm" class="grid gap-4">
        <div class="flex gap-4 mt-6">
          <div class="w-1/2">
            <label for="type" class="block text-sm font-medium text-gray-700">
              Forma de Pagamento *
            </label>
            <select
              id="type"
              v-model="form.type"
              required
              class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
            >
              <option disabled value="">Selecione</option>
              <option
                v-for="(desc, key) in typeDescriptions"
                :key="key"
                :value="key"
              >
                {{ desc }}
              </option>
            </select>
          </div>

          <div class="w-1/2">
            <label for="name" class="block text-sm font-medium text-gray-700">
              Nome da Forma de Pagamento
            </label>
            <input
              type="text"
              id="name"
              v-model="form.name"
              required
              class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
            />
          </div>
        </div>

        <div class="flex gap-4 mt-6">
          <div class="w-1/2">
            <label
              for="fee_percentage"
              class="block text-sm font-medium text-gray-700"
            >
              Taxa %
            </label>
            <input
              type="number"
              id="fee_percentage"
              v-model="form.fee_percentage"
              required
              class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
            />
          </div>

          <div class="w-1/2">
            <label
              for="bank_account"
              class="block text-sm font-medium text-gray-700"
            >
              Conta Bancária
            </label>
            <input
              type="text"
              id="bank_account"
              v-model="form.bank_account"
              class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
            />
          </div>
        </div>

        <div class="flex items-center mt-6">
          <label
            for="is_active"
            class="relative inline-flex items-center cursor-pointer"
          >
            <input
              type="checkbox"
              id="is_active"
              v-model="form.is_active"
              class="sr-only peer"
            />
            <div
              class="w-11 h-6 bg-gray-200 rounded-full peer-checked:bg-blue-500 peer-focus:ring-2 peer-focus:ring-blue-300 transition-all duration-300"
            ></div>
            <div
              class="absolute left-1 top-1 w-4 h-4 bg-white border border-gray-300 rounded-full peer-checked:translate-x-5 transition-transform duration-300"
            ></div>
            <span class="ml-3 text-sm font-medium text-gray-700">
              Ativar Forma de Pagamento
            </span>
          </label>
        </div>

        <div class="flex gap-4 mt-4">
          <button
            type="button"
            @click="resetarFormulario"
            class="w-1/2 px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50"
            :disabled="isSubmitting"
          >
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useModalStore } from '@/store/store';
import { notify } from '@/Plugins/Notify';
import { router } from '@inertiajs/vue3';

const modalStore = useModalStore();
const isSubmitting = ref(false);

const form = ref({
  name: '',
  type: '',
  fee_percentage: 0,
  bank_account: '',
  is_active: true,
});

const typeDescriptions = {
  D: 'Dinheiro',
  C: 'Crédito',
  T: 'Débito',
  P: 'Pix',
};

// Reseta o formulário e fecha o modal
const resetarFormulario = () => {
  modalStore.deactivateComponent(); // Fecha o modal
  form.value = {
    name: '',
    type: '',
    fee_percentage: null,
    bank_account: '',
    is_active: false,
  };
};

const submitForm = async () => {
  isSubmitting.value = true;

  try {
    // Envia os dados para o servidor usando Inertia
    await router.post(route('formas_pagamento.adicionar'), form.value, {
      onSuccess: () => {
        notify.success('Forma de pagamento cadastrada com sucesso!');
        resetarFormulario();
      },
      onError: (errors) => {
        console.error(errors);
        notify.error(
          'Erro ao cadastrar a forma de pagamento. Verifique os dados e tente novamente.'
        );
      },
    });
  } catch (error) {
    console.error('Erro inesperado:', error);
    notify.error('Ocorreu um erro inesperado. Tente novamente mais tarde.');
  } finally {
    isSubmitting.value = false; // Habilita o botão sempre no fim
  }
};
</script>

<style scoped>
.fixed {
  z-index: 9999;
}

.bg-gray-900 {
  background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
}
</style>
