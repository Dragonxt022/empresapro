<template>
    <div>

       <button @click="openModalCategoria" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-300 hidden md:inline-block">
            <i class="fa-solid fa-plus"></i>
            Nova categoria
        </button>

      <!-- Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end">
        <!-- Modal Content -->
        <div class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out"
          :class="{ 'slide-from-right': isModalOpen, 'slide-to-right': !isModalOpen }">

          <button @click="closeModal" class="absolute top-4 right-4 text-gray-500">
            <i class="fas fa-times"></i>
          </button>

          <h2 class="text-2xl mb-4">
            <i class="fa-solid fa-box-open"></i>
            Cadastrar Categoria
          </h2>

          <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Primeira coluna (dados do produto) -->
            <div class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome da Categoria</label>
                <input type="text" id="name" v-model="form.name" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />

              </div>

            </div>

            <!-- Segunda coluna (foto do produto) -->
            <div class="space-y-4">
              <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Foto do Produto</label>
                <!-- Botão para enviar a imagem com ícone -->
                <label for="image" class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
                  <i class="fas fa-image mr-2"></i>Enviar imagem
                </label>
                <input type="file" id="image" @change="handleImageChange" class="hidden" />
              </div>

              <div v-if="imagePreview" class="mt-4 flex items-center justify-center">
                <img :src="imagePreview" alt="Preview da Imagem" class="w-64 h-auto rounded-lg" />
              </div>

              <div class="mt-4 text-sm text-gray-600">
                <p>Formatos: JPEG, JPG e PNG</p>
                <p>Resolução ideal: 400x400 ou 800x800</p>
                <p class="font-semibold text-purple-600">Sugestões de foto</p>
                <p class="text-gray-400">Nenhum resultado encontrado...</p>
              </div>
            </div>
            <!-- Loader centralizado -->
            <div v-if="isSubmitting" class="absolute inset-0 flex justify-center items-center bg-gray-50 bg-opacity-50">
                <div class="loader border-4 border-t-blue-500 border-gray-200 rounded-full w-12 h-12 animate-spin"></div>
            </div>
          <!-- Botões -->
            <div class="flex gap-4 mt-6">
                <button
                    type="button"
                    @click="closeModal"
                    class="w-1/2 px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50"
                    :disabled="isSubmitting"
                >
                    Cadastrar
                </button>
            </div>


          </form>

        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Inertia } from '@inertiajs/inertia';

  const isSubmitting = ref(false);
  const isModalOpen = ref(false);
  const form = ref({
    name: '',

    image: null,
  });

  const imagePreview = ref(null);

  // Abre a modal
  const openModalCategoria = () => {
    isModalOpen.value = true;
  };

  // Fecha a modal
  const closeModal = () => {
    isModalOpen.value = false;
    form.value = {
      name: '',
      image: null,
    };
    imagePreview.value = null;
  };

  // Função para lidar com a imagem do produto
  const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      form.value.image = file;
      const reader = new FileReader();
      reader.onload = () => {
        imagePreview.value = reader.result;
      };
      reader.readAsDataURL(file);
    }
  };

  const submitForm = async () => {
    isSubmitting.value = true;
    const formData = new FormData();

    formData.append('name', form.value.name);
    if (form.value.image) {
        formData.append('image', form.value.image);
    }

    // Envia os dados para a API usando Inertia.js
    Inertia.post('/produtos/adicionar', formData, {
        onSuccess: () => {
            closeModal(); // Fecha a modal em caso de sucesso
            isSubmitting.value = false; // Reabilita o botão
        },
        onError: (errors) => {
            console.error(errors);
            isSubmitting.value = false; // Reabilita o botão para novas tentativas
        },
    });
    };

  </script>

  <style scoped>
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
  /* Animação de deslizar da direita para a entrada */
  @keyframes slideFromRight {
    0% {
      transform: translateX(100%);
    }
    100% {
      transform: translateX(0);
    }
  }

  /* Animação de deslizar da direita para a saída */
  @keyframes slideToRight {
    0% {
      transform: translateX(0);
    }
    100% {
      transform: translateX(100%);
    }
  }

  /* Estilos de transição */
  .slide-from-right {
    animation: slideFromRight 0.3s ease-out;
  }

  .slide-to-right {
    animation: slideToRight 0.3s ease-in;
  }

  .fixed {
    z-index: 9999;
  }

  .bg-gray-900 {
    background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
  }
  </style>
