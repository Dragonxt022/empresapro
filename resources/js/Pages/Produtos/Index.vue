<script setup>
import { ref, watch } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import Paginetion from '@/Components/Paginetion.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import DropdownMenu from '@/Components/DropdownMenu.vue';
import CadastroProduto from '@/Components/CadastroProduto.vue';
import Notification2 from '@/Components/Notification2.vue';

// Recebendo os props do Laravel
const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    filters: {
        type: Object,
        required: true,
    },
});

const tab = ref('produtos');

const paginaAtual = ref(props.products.meta.current_page);
const links = ref(props.products.meta.links); // Links da paginação

// Estados reativos baseados nos props recebidos
const searchTerm = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const selectedProducts = ref([]);
// Estados da modal
const isModalOpen = ref(false);
const modalMessage = ref('');
const isWarning = ref(false);

const navegarPagina = async (url) => {
    // Lógica para navegar para a página selecionada
    if (url) {
        Inertia.get(url);  // Faz a requisição sem recarregar a página
    }
};

// Atualizar URL ao aplicar filtros ou paginação
const atualizarUrl = async () => {
    Inertia.get('/produtos', {
        search: searchTerm.value,
        category: selectedCategory.value,
        page: paginaAtual.value,
    }, { preserveState: true });
};

// Assista mudanças nos filtros
watch([searchTerm, selectedCategory], () => {
    paginaAtual.value = 1; // Reseta para a página 1 ao mudar filtros
    atualizarUrl();
});

// Selecionar todos os produtos
const selecionarTodos = (checked) => {
    if (checked) {
        selectedProducts.value = props.products.data.map((product) => product.id);
    } else {
        selectedProducts.value = [];
    }
};

// Selecionar produto individual
const selecionarProduto = (productId) => {
    if (selectedProducts.value.includes(productId)) {
        selectedProducts.value = selectedProducts.value.filter((id) => id !== productId);
    } else {
        selectedProducts.value.push(productId);
    }
};


const excluirProdutos = () => {
    if (selectedProducts.value.length === 0) {
        modalMessage.value = 'Você precisa selecionar ao menos um produto para excluir.';
        isWarning.value = true;
        isModalOpen.value = true;
        return;
    }

    // Define a mensagem da modal e abre-a
    modalMessage.value = `Você tem certeza que deseja excluir ${selectedProducts.value.length} item(ns)?`;
    isWarning.value = false;
    isModalOpen.value = true;
};

const confirmarExclusao = async () => {
    if (!isWarning.value) {
        Inertia.post('/produtos/excluir', { ids: selectedProducts.value }, {
            preserveState: true,
            onSuccess: () => {
                props.products.data = props.products.data.filter(
                    product => !selectedProducts.value.includes(product.id)
                );
                selectedProducts.value = [];
            },
        });
    }
    isModalOpen.value = false;
};

</script>


<style>
    .img-50x50 {
        width: 50px;
        height: 50px;
        object-fit: cover;
    }
    .dropdown-menu {
        display: none;
    }
    .dropdown:hover .dropdown-menu {
        display: block;
    }

    /* Efeito de brilho suave */
    tr:hover {
        box-shadow: 0 0 0 2px rgba(0, 115, 255, 0.8); /* Brilho suave ao redor da linha */
        border-radius: 3px; /* Bordas arredondadas */
        transition: box-shadow 0.3s ease-in-out; /* Transição suave */
    }
</style>

<template>
    <AppLayout title="Produtos">
        <div class="content-wrapper">

            <section class="content-header py-4">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Produtos</h1>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">

                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <button
                                class="px-4 py-3 rounded-t-lg focus:outline-none"
                                :class="{
                                    'bg-white text-blue-600 font-semibold': tab === 'produtos',
                                    'bg-gray-100 text-gray-600': tab !== 'produtos'
                                }"
                                @click="tab = 'produtos'"
                            >
                                Produtos
                            </button>
                            <button
                                class="px-4 py-3 rounded-t-lg focus:outline-none"
                                :class="{
                                    'bg-white text-blue-600 font-semibold': tab === 'complementos',
                                    'bg-gray-100 text-gray-600': tab !== 'complementos'
                                }"
                                @click="tab = 'complementos'"
                            >
                                Complementos
                            </button>
                        </div>


                        <div class="flex gap-2">

                            <!-- Botão "Nova categoria" apenas visível em telas grandes -->
                            <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-300 hidden md:inline-block">
                                <i class="fa-solid fa-plus"></i>
                                Nova categoria
                            </button>

                            <!-- Botão "Novo produto" responsivo -->
                            <CadastroProduto
                                :categories="categories"

                            />
                        </div>
                    </div>

                    <div class="bg-white shadow-md rounded-lg p-4">
                        <!-- Tabela -->
                        <div class="bg-white">
                            <div class="flex justify-between items-center gap-2 ">
                                <input
                                    type="text"
                                    v-model="searchTerm"
                                    placeholder="Pesquisar"
                                    class="w-full py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring"
                                />
                                <select
                                    v-model="selectedCategory"
                                    class="px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring"
                                >
                                    <option value="">Filtrar por Categoria</option>
                                    <option v-for="category in props.categories" :key="category.id" :value="category.name">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <button
                                    class="px-4 py-2 rounded-lg"
                                    :class="{
                                        'bg-red-500 text-white': selectedProducts.length > 0,  // Cor de destaque quando itens estão selecionados
                                        'bg-gray-300 text-gray-600': selectedProducts.length === 0,  // Cor apagada quando não há itens selecionados
                                        'cursor-not-allowed': selectedProducts.length === 0  // Desabilita o botão
                                    }"
                                    :disabled="selectedProducts.length === 0"
                                    @click="excluirProdutos"
                                >
                                    Ação
                                </button>

                                <ConfirmModal
                                    :isOpen="isModalOpen"
                                    :isWarning="isWarning"
                                    :message="modalMessage"
                                    @cancelar="isModalOpen = false"
                                    @confirmar="confirmarExclusao"
                                />
                            </div>
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
                                    <tr v-for="product in props.products.data" :key="product.id" class="border-b">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" :checked="selectedProducts.includes(product.id)" @change="selecionarProduto(product.id)" />
                                        </td>
                                        <td class="px-4 py-2 flex items-center gap-2">
                                            <img :src="'/storage/' + (product.image_path || 'default-product-image.jpg')" alt="Produto" class="img-circle img-50x50">
                                            {{ product.name }}
                                        </td>


                                        <td class="px-4 py-2 text-center hidden sm:table-cell" :style="{ color: (product.barcode === null || product.barcode === '') ? 'cadetblue' : '' }">
                                            {{ product.barcode || 'Não informado' }}
                                        </td>

                                        <td class="px-3 py-2 text-center hidden md:table-cell">{{ product.category }}</td>
                                        <td class="px-4 py-2 text-center hidden lg:table-cell">{{ product.stock_quantity}}</td>
                                        <td class="px-4 py-2 text-center hidden xl:table-cell">R$ {{ product.cost_price }}</td>
                                        <td class="px-4 py-2 text-center hidden xl:table-cell">R$ {{ product.price }}</td>
                                        <td class="px-4 py-2 flex items-center gap-2">

                                            <DropdownMenu />

                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginador -->
                        <div class="flex justify-center gap-2 mt-4">
                            <Paginetion :links="links" @navegar="navegarPagina" />
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </AppLayout>
</template>
