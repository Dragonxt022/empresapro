<template>
  <!-- Categorias -->
  <div
    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-8 gap-1 mb-2 shadow-md"
  >
    <div
      v-for="category in categories"
      :key="category.id"
      class="flex flex-col items-center cursor-pointer"
      @click="filterProducts(category.id)"
    >
      <div
        class="w-16 h-16 rounded-full flex items-center justify-center shadow-xl"
        :class="{ 'bg-blue-900': selectedCategory === category.id }"
      >
        <img
          :src="`/storage/${
            category.image_path || 'default-product-image.jpg'
          }`"
          alt="Categoria"
          class="w-full h-full rounded-full object-cover"
        />
      </div>
      <p class="text-sm mt-2 text-center font-semibold text-gray-500">
        {{ category.name }}
      </p>
    </div>
  </div>

  <!-- Barra de pesquisa -->
  <SearchBar class="p-1" @search="handleSearch" />

  <!-- Produtos -->
  <div
    class="h-[500px] overflow-y-scroll mt-3 flex-grow p-2"
    @scroll="handleScroll"
  >
    <transition-group
      name="product-fade"
      tag="div"
      class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2"
    >
      <div
        v-for="product in allProducts"
        :key="product.id"
        class="bg-white shadow-xl rounded-lg p-2 flex flex-col items-center cursor-pointer card"
        @click="addProduct(product)"
      >
        <!-- Círculo com a quantidade -->
        <div
          v-if="getProductQuantity(product.id) > 0"
          class="absolute top-0 right-0 flex items-center justify-center w-5 h-5 bg-blue-500 text-white text-xs rounded-full"
        >
          {{ getProductQuantity(product.id) }}
        </div>

        <img
          :src="`/storage/${product.image_path || 'default-product-image.jpg'}`"
          alt="Produto"
          class="object-cover w-32 rounded-md mb-1"
        />
        <p class="text-sm font-semibold text-gray-800 text-center mb-1">
          {{ product.name }}
        </p>
        <p class="text-sm font-semibold text-gray-600">
          R$ {{ product.price }}
        </p>
      </div>
    </transition-group>

    <div v-if="loading" class="text-center mt-4">Carregando...</div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import SearchBar from './SearchBar.vue';

const props = defineProps({
  categories: { type: Array, required: true },
  cartItems: { type: Array, required: true },
});

const emit = defineEmits(['add-to-cart']);

const addProduct = (product) => emit('add-to-cart', product);

const allProducts = ref([]); // Lista de produtos
const nextPageUrl = ref(null); // URL da próxima página
const selectedCategory = ref(null); // Categoria selecionada
const loading = ref(false);
const searchTerm = ref(''); // Termo de pesquisa
const typingTimeout = ref(null); // Para controlar o tempo de espera (debounce)

// Função separada para filtragem de produtos
const filterProductsBySearchAndCategory = async () => {
  loading.value = true;

  try {
    const params = {
      category: selectedCategory.value,
      search: searchTerm.value,
      per_page: 30,
    };

    const response = await axios.get('/api/products', { params });

    const products = response.data.data;

    // Filtragem local
    allProducts.value = products.filter((product) => {
      const matchesCategory =
        !selectedCategory.value ||
        product.category_id === selectedCategory.value;

      const matchesSearch =
        !searchTerm.value ||
        product.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        (product.barcode &&
          product.barcode
            .toLowerCase()
            .includes(searchTerm.value.toLowerCase())); // Filtro de código de barras

      // Se encontrou um produto com o código de barras, adiciona automaticamente ao carrinho
      if (
        matchesSearch &&
        product.barcode &&
        product.barcode.toLowerCase() === searchTerm.value.toLowerCase()
      ) {
        addProduct(product); // Adiciona ao carrinho

        // Limpa o campo de pesquisa apenas quando for um código de barras
        searchTerm.value = ''; // Limpa o campo de pesquisa
      }

      return matchesCategory && matchesSearch;
    });

    nextPageUrl.value = response.data.next_page_url; // Atualiza a URL da próxima página
  } catch (error) {
    console.error('Erro ao filtrar produtos:', error);
  } finally {
    loading.value = false;
  }
};

// Função para tratar a pesquisa com delay de 2 segundos
const handleSearch = (term) => {
  searchTerm.value = term;

  // Limpa o timeout anterior
  if (typingTimeout.value) {
    clearTimeout(typingTimeout.value);
  }

  // Cria um novo timeout para esperar 2 segundos após o usuário terminar de digitar
  typingTimeout.value = setTimeout(async () => {
    await filterProductsBySearchAndCategory();
  }, 200); // Delay de 2 segundos
};

// Filtra produtos por categoria
const filterProducts = async (categoryId) => {
  selectedCategory.value =
    selectedCategory.value === categoryId ? null : categoryId;
  await filterProductsBySearchAndCategory();
};

// Função para verificar a quantidade do produto no carrinho
const getProductQuantity = (productId) => {
  const product = props.cartItems.find((item) => item.id === productId);
  return product ? product.quantity : 0;
};

const loadMoreProducts = async () => {
  if (loading.value || !nextPageUrl.value) return;

  loading.value = true;

  try {
    const response = await axios.get(nextPageUrl.value);

    if (response && response.data && response.data.data) {
      allProducts.value.push(...response.data.data);
      nextPageUrl.value = response.data.next_page_url;
    }
  } catch (error) {
    console.error('Erro ao carregar mais produtos:', error);
  } finally {
    loading.value = false;
  }
};

// Lida com o evento de rolagem
const handleScroll = (event) => {
  const { scrollTop, scrollHeight, clientHeight } = event.target;
  if (scrollTop + clientHeight >= scrollHeight - 50) {
    loadMoreProducts();
  }
};

// Carrega os produtos da API ao montar o componente
onMounted(async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/products', {
      params: { per_page: 30 },
    });
    allProducts.value = response.data.data;
    nextPageUrl.value = response.data.next_page_url;
  } catch (error) {
    console.error('Erro ao carregar produtos:', error);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
/* Estilo de transição para os produtos */
.product-fade-enter-active,
.product-fade-leave-active {
  transition: opacity 500ms ease, transform 500ms ease;
}

.product-fade-enter,
.product-fade-leave-to {
  opacity: 0;
  transform: translateY(10px); /* Animação de "subir" */
}

.product-fade-enter-to {
  opacity: 1;
  transform: translateY(0);
}
</style>
