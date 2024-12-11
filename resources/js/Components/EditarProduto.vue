<template>
    <div>
      <!-- Modal -->

        <div v-if="isModalOpenEdit" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end">
        <!-- Modal Content -->
        <div class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out"
          :class="{ 'slide-from-right': isModalOpenEdit, 'slide-to-right': !isModalOpenEdit }">

          <button @click="closeModal" class="absolute top-4 right-4 text-gray-500">
            <i class="fas fa-times"></i>
          </button>

          <h2 class="text-2xl mb-4">
            <i class="fa-solid fa-box-open"></i>
            Editar Produto
          </h2>

          <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Primeira coluna (dados do produto) -->
            <div class="space-y-4">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome do Produto</label>
                <input type="text" id="name" v-model="form.name" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
              </div>

              <div v-if="categories && categories.length > 0">
                <label for="category" class="block text-sm font-medium text-gray-700">
                    Categoria <i class="fa-solid fa-circle-plus" style="color: #0079d6;"></i>
                </label>
                <select id="category" v-model="form.category_id" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded">
                    <option value="">Selecione a Categoria</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
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
                  <input type="number" id="price" v-model="form.price" required step="0.01" class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" placeholder="R$ 0,00" />
                </div>

                <div>
                  <label for="cost_price" class="block text-sm font-medium text-gray-700">Valor de Custo (R$)</label>
                  <input type="number" id="cost_price" v-model="form.cost_price" required step="0.01" class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" placeholder="R$ 0,00"/>
                </div>
              </div>

              <div>
                <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Estoque</label>
                <input type="number" id="stock_quantity" v-model="form.stock_quantity" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
              </div>
            </div>

            <!-- Segunda coluna (foto do produto) -->
            <div class="space-y-4">
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Foto do Produto</label>
                    <label for="image" class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
                    <i class="fas fa-image mr-2"></i>Enviar imagem
                    </label>
                    <input type="file" id="image" @change="handleImageChange" class="hidden" />
                </div>

                <!-- Exibe a imagem carregada ou a imagem padrão caso não tenha sido carregada nenhuma -->
                <div v-if="imagePreview || form.image_path" class="mt-4 flex items-center justify-center">
                    <img :src="imagePreview || '/storage/' + (form.image_path || 'default-product-image.jpg')" alt="Preview da Imagem" class="w-64 h-auto rounded-lg" />
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

  <script>
  export default {
    props: {
      produto: Object,
      categories: {
        type: Array,
        default: () => [] // Define um array vazio como valor padrão
      }

    },
    data() {
    return {
        form: {
            ...this.produto,
            price: this.produto.price ? this.produto.price.replace(',', '.') : '',  // Substitui a vírgula por ponto
            cost_price: this.produto.cost_price ? this.produto.cost_price.replace(',', '.') : ''  // O mesmo para o custo
            },
            imagePreview: null,
            isSubmitting: false,
            isModalOpenEdit: true
        };

    },

    methods: {
        closeModal() {
            // Inicia a animação de saída
            this.isModalOpenEdit = false;

            // Atraso para permitir que a animação aconteça antes de ocultar o modal
            setTimeout(() => {
            this.$emit('close'); // Emite o evento para fechar o modal
            }, 300); // Ajuste o tempo para coincidir com a duração da animação
        },
      submitForm() {
        this.isSubmitting = true;
        // Lógica para salvar o produto (via API ou outro processo)
        console.log('Produto atualizado', this.form);
        this.isSubmitting = false;
        this.closeModal(); // Fecha o modal após salvar
      },
      handleImageChange(event) {
        const file = event.target.files[0];
        if (file) {
          this.imagePreview = URL.createObjectURL(file);
        }
      },
    },
    watch: {
      // Caso o produto seja alterado, o formulário será atualizado automaticamente
      produto(newProduct) {
        this.form = { ...newProduct };
      }
    }
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
