<template>
  <div
    v-if="modalStore.activeComponent === 'editar_metodo_pagamento'"
    class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end"
  >
    <div
      class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out"
    >
      <button
        @click="resetarFormulario"
        class="close-button absolute top-4 right-4 text-gray-500"
      >
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-2xl mb-2">
        <i class="fa-solid fa-credit-card mr-3"></i>
        <span>Editar Forma de Pagamento</span>
        <button
          @click="deletePaymentMethod"
          class="hover:text-red-800 transition-colors p-3"
          title="Excluir Forma de Pagamento"
        >
          <i class="fa-solid fa-trash-can fa-xs"></i>
        </button>
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
              value="true"
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

        <!-- Loader centralizado -->
        <div
          v-if="isSubmitting"
          class="absolute inset-0 flex justify-center items-center bg-gray-50 bg-opacity-50"
        >
          <div
            class="loader border-4 border-t-blue-500 border-gray-200 rounded-full w-12 h-12 animate-spin"
          ></div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useModalStore } from '@/store/store';
import { notify } from '@/Plugins/notify';
import { router } from '@inertiajs/vue3';

const modalStore = useModalStore();
const isSubmitting = ref(false);

const props = defineProps({
  paymentMethods: Object,
});

const form = ref({
  id: null,
  name: '',
  type: '',
  fee_percentage: 0,
  bank_account: '',
  is_active: '',
  empresa_id: '',
});

const typeDescriptions = {
  D: 'Dinheiro',
  C: 'Crédito',
  T: 'Débito',
  P: 'Pix',
};

watch(
  () => modalStore.selectedPaymentMethod,
  (selectedPaymentMethod) => {
    if (selectedPaymentMethod) {
      form.value = { ...selectedPaymentMethod };
    }
  },
  { immediate: true }
);

const resetarFormulario = () => {
  modalStore.deactivateComponent();
  form.value = {
    id: null,
    name: '',
    type: '',
    fee_percentage: 0,
    bank_account: '',
    empresa_id: '',
    is_active: true,
  };
};

const submitForm = async () => {
  isSubmitting.value = true;

  try {
    const {
      id,
      name,
      type,
      fee_percentage,
      bank_account,
      is_active,
      empresa_id,
    } = form.value;

    if (!id) {
      notify.error('ID da forma de pagamento não encontrado.');
      return;
    }

    // Atualiza a forma de pagamento
    await router.post(route('formas_pagamento.update', { id }), {
      name,
      type,
      fee_percentage,
      bank_account,
      is_active,
      empresa_id,
    });

    notify.success('Forma de pagamento atualizada com sucesso.');
    resetarFormulario();
  } catch (error) {
    notify.error('Erro ao atualizar a forma de pagamento.');
    console.error(error);
  } finally {
    isSubmitting.value = false;
  }
};

const deletePaymentMethod = async () => {
  if (!form.value.id) {
    notify.error('ID da forma de pagamento não encontrado.');
    return;
  }

  const confirmed = confirm(
    `Tem certeza que deseja excluir a forma de pagamento "${form.value.name}"?`
  );
  if (!confirmed) return;

  try {
    await router.delete(
      route('paymentMethod.destroy', { paymentMethod: form.value.id })
    );

    notify.success('Forma de pagamento excluída com sucesso.');
    resetarFormulario();
  } catch (error) {
    notify.error('Erro ao excluir a forma de pagamento.');
    console.error(error);
  }
};
</script>

<style scoped>
/* Animação de fade */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 3s ease;
}

.fade-enter, .fade-leave-to /* .fade-leave-active em <2.1.8 */ {
  opacity: 0;
}
.close-button {
  transition: all 0.2s ease-in-out;
  border-radius: 50%; /* Deixa o botão arredondado */
  padding: 5px; /* Adiciona espaço interno suficiente para o ícone */
  background-color: transparent; /* Fundo transparente por padrão */
  border: 2px solid #cccccc00; /* Adiciona uma borda para o botão */
  display: flex;
  justify-content: center;
  align-items: center;
}

.close-button:hover {
  color: #ffffff; /* Cor do ícone ao passar o mouse */
  background-color: red; /* Fundo vermelho */
  transform: scale(1.1); /* Aumenta o tamanho ao passar o mouse */
  box-shadow: 0px 4px 10px rgba(16, 16, 16, 0.005); /* Adiciona sombra com tom de vermelho */
}

.close-button i {
  font-size: 32px; /* Aumenta o tamanho do ícone "X" */
}

.loader {
  border-width: 4px;
  border-style: solid;
  border-color: transparent;
  border-top-color: blue;
  border-radius: 50%;
  width: 3rem;
  height: 3rem;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.fixed {
  z-index: 9999;
}

.bg-gray-900 {
  background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
}
</style>
