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

	          <button @click="closeModal" class="absolute top-4 right-4 text-gray-500">
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
	                    <input type="number" id="price" v-model="form.price" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
	                </div>

	                <div>
	                    <label for="cost_price" class="block text-sm font-medium text-gray-700">Valor de Custo (R$)</label>
	                    <input type="number" id="cost_price" v-model="form.cost_price" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" />
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
	                <!-- Botão para enviar a imagem com ícone -->
	                <label for="image" class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-blue-600">
	                  <i class="fas fa-image mr-2"></i>Enviar imagem
	                </label>
	                <input type="file" id="image" @change="handleImageChange" class="hidden" />
	              </div>

	              <div v-if="imagePreview" class="mt-4">
	                <img :src="imagePreview" alt="Preview da Imagem" class="w-full h-auto rounded-lg" />
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
	            <div class="flex justify-end gap-4 mt-6">
	                <button
	                    type="button"
	                    @click="closeModal"
	                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-100"
	                    :disabled="isSubmitting"
	                >
	                    Cancelar
	                </button>
	                <button
	                    type="submit"
	                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50"
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

<script setup lang="ts">
defineProps<{
	openModal: () => void;
	isModalOpen: boolean;
	closeModal: () => void;
	submitForm: () => Promise<void>;
	form: { name: string; category: string; price: any; cost_price: any; stock_quantity: number; image: any; };
	handleImageChange: (event: any) => void;
	imagePreview: any;
	isSubmitting: boolean;
}>()
</script>
