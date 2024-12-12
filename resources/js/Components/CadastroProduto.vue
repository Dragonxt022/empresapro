<template>
    <div>
      <!-- Botão para abrir a modal -->
      <button @click="openModal" class="px-4 py-2 bg-blue-500 text-white rounded">
        <i class="fa-solid fa-plus"></i>
        Novo Produto
      </button>

      <!-- Modal -->
      <div v-if="isModalOpen" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end">
        <!-- Modal Content -->
        <div class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out"
          :class="{ 'slide-from-right': isModalOpen, 'slide-to-right': !isModalOpen }">

          <button
            @click="closeModal"
            class="close-button absolute top-4 right-4 text-gray-500"
            >
            <i class="fas fa-times"></i>
          </button>



          <h2 class="text-2xl mb-4">
            <i class="fa-solid fa-box-open"></i>
            Cadastro de Produto
          </h2>

          <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Primeira coluna (dados do produto) -->
            <div class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" id="name" v-model="form.name" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
              </div>

              <div v-if="props.categories && props.categories.length > 0">
                <label for="category" class="block text-sm font-medium text-gray-700">
                Categoria <i class="fa-solid fa-circle-plus" style="color: #0079d6;"></i>
                </label>
                <select id="category" v-model="form.category_id" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded">
                    <option value="">Selecione a Categoria</option>
                    <option v-for="category in props.categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
              </div>

               <div v-else>
                <p>Carregando categorias...</p>
              </div>


              <div class="flex justify-end gap-4 mt-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Valor de Venda (R$)</label>
                    <InputNumber v-model.number="form.price" id="price" />
                </div>

                <div>
                    <label for="cost_price" class="block text-sm font-medium text-gray-700">Valor de Custo (R$)</label>
                    <InputNumber v-model.number="form.cost_price" id="cost_price" />
                </div>

              </div>

              <div>
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Estoque</label>
                <input type="number" id="stock_quantity" v-model.number="form.stock_quantity" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
              </div>
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
            </div>

            <!-- Segunda coluna (foto do produto) -->
            <div class="space-y-4">
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto do Produto</label>

                    <!-- Área para upload da imagem ou preview -->
                    <div
                        class="flex  flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 hover:border-gray-400 transition cursor-pointer"
                        @click="triggerImageUpload"

                        >
                        <!-- Se houver preview da imagem, exibe a imagem clicável -->
                        <template v-if="imagePreview">
                            <img
                                :src="imagePreview"
                                alt="Preview da Imagem"
                                class="object-contain w-[90%] h-[90%] rounded-md transition-transform duration-300 ease-in-out hover:scale-105"
                            />
                        </template>

                        <!-- Caso contrário, exibe o layout original com o ícone e texto -->
                        <template v-else>
                            <i class="fas fa-box-open text-gray-400 text-4xl mb-4"></i>
                            <p class="text-gray-600 text-sm font-medium">Enviar imagem</p>
                            <p class="text-gray-400 text-xs">Formatos permitidos: JPEG, JPG, PNG <br> Resolução ideal: 400x400 ou 800x800</p>
                        </template>
                    </div>

                    <!-- Input oculto para selecionar imagem -->
                    <input type="file" id="image" ref="imageInput" @change="handleImageChange" class="hidden" />
                </div>

                <!-- Informações adicionais -->
                <div class="mt-4 text-sm text-gray-600">
                    <p class="font-semibold text-purple-600">Sugestões de foto</p>
                    <span>Nada no momento...</span>
                </div>

                <div>
                    <label for="barcode" class="block text-sm font-medium text-gray-700">Código de Barras (EAN)</label>
                    <input type="text" id="barcode" v-model="form.barcode" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
                </div>
            </div>

            <!-- Loader centralizado -->
            <div v-if="isSubmitting" class="absolute inset-0 flex justify-center items-center bg-gray-50 bg-opacity-50">
                <div class="loader border-4 border-t-blue-500 border-gray-200 rounded-full w-12 h-12 animate-spin"></div>
            </div>
          </form>

        </div>
      </div>
    </div>
</template>

<script setup>
    import { ref } from 'vue';
    import { Inertia } from '@inertiajs/inertia';
    import InputNumber from './InputNumber.vue';

    const isSubmitting = ref(false);
    const isModalOpen = ref(false);
    const imageInput = ref(null);
    const imagePreview = ref(null);

    const form = ref({
        name: '',
        category_id: '',
        price: null,
        cost_price: null,
        barcode: '',
        stock_quantity: 0,
        image: null,
    });

    const props = defineProps({
        categories: {
            type: Array,
            required: true,
        },
    });

    // Abre a modal
    const openModal = () => {
        isModalOpen.value = true;
    };

    // Fecha a modal
    const closeModal = () => {
        isModalOpen.value = false;
        form.value = {
            name: '',
            category_id: '',
            price: null,
            cost_price: null,
            barcode: '',
            stock_quantity: 0,
            image: null,
        };
        imagePreview.value = null; // Reseta o preview ao fechar a modal
    };

    const triggerImageUpload = () => {
        if (imageInput.value) {
            imageInput.value.click(); // Simula o clique no input de arquivo
        }
    };

    // Função para lidar com a imagem do produto
    const handleImageChange = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const validFormats = ['image/jpeg', 'image/png'];
        if (!validFormats.includes(file.type)) {
            alert('Formato inválido! Apenas arquivos JPEG e PNG são permitidos.');
            return;
        }

        form.value.image = file;
        const reader = new FileReader();
        reader.onload = () => {
            imagePreview.value = reader.result; // Atualiza o preview da imagem
        };
        reader.readAsDataURL(file);
    };

    const submitForm = async () => {
        isSubmitting.value = true;
        const formData = new FormData();

        formData.append('name', form.value.name);
        formData.append('category_id', form.value.category_id); // Enviando o ID da categoria

        // Divide o preço e custo por 100 para enviar em reais, ao invés de centavos
        formData.append('price', (form.value.price / 100).toFixed(2));
        formData.append('cost_price', (form.value.cost_price / 100).toFixed(2));
        formData.append('barcode', form.value.barcode);
        formData.append('stock_quantity', form.value.stock_quantity);
        if (form.value.image) {
            formData.append('image', form.value.image);
        }

        // Envia os dados para a API usando Inertia.js
        Inertia.post('/produtos/adicionar', formData, {
            preserveState: true,
            preserveScroll: true,
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
