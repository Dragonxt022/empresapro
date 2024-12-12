<template>
    <div>
      <!-- Tabela de Produtos -->
      <table class="table-auto w-full text-left border-collapse mt-4 shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100 border-b">
          <tr>
            <th class="px-4 py-3">
              <input type="checkbox" @change="selecionarTodos($event.target.checked)" />
            </th>
            <th class="px-4 py-2">PRODUTO</th>
            <th class="px-4 py-2 text-center hidden sm:table-cell">COD</th>
            <th class="px-4 py-2 text-center hidden md:table-cell">CATEGORIA</th>
            <th class="px-4 py-2 text-center hidden lg:table-cell">ESTOQUE</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">VALOR DE CUSTO</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">VALOR DE VENDA</th>
            <th class="px-4 py-2"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="product in products.data" :key="product.id" class="border-b" @click="editarProduto(product)">
            <td class="px-4 py-2" @click.stop >
              <input type="checkbox" :checked="selectedProducts.includes(product.id)" @change="selecionarProduto(product.id)" />
            </td>
            <td class="px-4 py-2 flex items-center gap-2">
              <img :src="'/storage/' + (product.image_path || 'default-product-image.jpg')" alt="Produto" class="img-circle img-50x50">
              {{ product.name }}
            </td>
            <td class="px-4 py-2 text-center hidden sm:table-cell" :style="{ color: (product.barcode === null || product.barcode === '') ? 'cadetblue' : '' }">
              {{ product.barcode || 'Não informado' }}
            </td>
            <td class="px-3 py-2 text-center hidden md:table-cell">{{ product.category.name }}</td>
            <td class="px-4 py-2 text-center hidden lg:table-cell">{{ product.stock_quantity }}</td>
            <td class="px-4 py-2 text-center hidden xl:table-cell">R$ {{ product.cost_price }}</td>
            <td class="px-4 py-2 text-center hidden xl:table-cell">R$ {{ product.price }}</td>
            <td class="px-4 py-2 flex items-center gap-2" @click.stop >
              <DropdownMenu />
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Paginador -->
      <div class="flex justify-center gap-2 mt-4">
        <Pagination :links="links" @navegar="navegarPagina" />
      </div>
    </div>
  </template>

  <script setup>
  import { ref } from 'vue';
  import axios from 'axios';

  const products = ref([]);
  const links = ref([]);
  const selectedProducts = ref([]);

  const loadProducts = async () => {
    try {
      const response = await axios.get('/api/products', {
        params: {
          per_page: 20, // Paginação
          search: '', // Filtro de pesquisa
          category: '', // Filtro de categoria
        }
      });

      products.value = response.data.data;
      links.value = response.data.links; // Links para a navegação das páginas
    } catch (error) {
      console.error("Erro ao carregar produtos", error);
    }
  };

  const navegarPagina = (page) => {
    loadProducts(page);
  };

  loadProducts(); // Carrega os produtos na inicialização
  </script>

  <style scoped>
  /* Adicione os estilos necessários */
  </style>
