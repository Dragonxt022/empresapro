<template>
    <div>

        <!-- Modal -->
        <div v-if="modalStore.activeComponent === 'categoria'" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end">
            <!-- Modal Content -->
            <div class="modal-content w-50 sm:w-4/5 md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white p-6 h-full overflow-auto transform transition-all duration-300 ease-in-out">

            <button @click="desativarComponentes" class="absolute top-4 right-4 text-gray-500">
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
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        @input="onSearchImage"
                        required
                        class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
                    />
                    <!-- <input type="text" id="name" v-model="form.name" required class="mt-1 px-3 py-2 w-full border border-gray-300 rounded" /> -->

                </div>

                <!-- Botões -->
                    <div class="flex gap-4 mt-6">
                        <button
                            type="button"
                            @click="desativarComponentes"
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
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Foto da Categoria</label>

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
                    <div v-if="suggestedImages.length > 0" class="mt-4">
                        <p class="font-semibold text-purple-600">Sugestões de foto</p>

                        <!-- Animação de entrada das imagens com 3 segundos de duração -->
                        <transition-group
                            name="fade"
                            tag="div"
                            class="grid grid-cols-3 gap-4"
                            :duration="2000"
                        >
                            <div
                                v-for="(image, index) in suggestedImages"
                                :key="index"
                                class="cursor-pointer p-2"
                                @click="setImage(image.link)"
                            >
                                <img
                                    :src="image.link"
                                    :alt="image.title"
                                    class="w-full h-24 object-cover rounded-md"
                                />
                            </div>
                        </transition-group>
                    </div>

                    <!-- Caso não haja imagens sugeridas -->
                    <div v-else class="mt-4 text-gray-500">
                        <p class="font-semibold text-purple-600">Sugestões de foto</p>

                        <p class="mt-3">Nada no momento...</p>
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
    import { router } from '@inertiajs/vue3';
    import { notify } from '@/Plugins/notify';
    import { useModalStore } from '@/store/store';  // Importando a store
    import _debounce from 'lodash.debounce';

    const modalStore = useModalStore();
    const isSubmitting = ref(false);
    const imageInput = ref(null);
    const imagePreview = ref(null);

    const suggestedImages = ref([]);

    const apiKey = 'AIzaSyBQcLZJqbHPUq9XM6bjOpopQ5fJaMkmq1U'; // Substitua com sua chave da API
    const cx = 'a7c7f9a8761d54e79'; // Substitua com seu ID do mecanismo de pesquisa

    const form = ref({
        name: '',

        image: null,
    });

    const lastSearch = ref('');
    const lastImages = ref([]);
    const apiLimitReached = ref(false); // Flag que controla o estado de limite atingido

    const dailyQueryCount = ref(0); // Contador de consultas
    const dailyQueryLimit = 100; // Limite diário (igual ao fornecido pela API)

    // Variáveis para controle de qual API utilizar
    const useInternalApi = ref(true); // Controle para usar a API interna (true) ou a API do Google (false)
    const useGoogleApi = ref(true);   // Controle para usar a API do Google

    const onSearchImage = _debounce(async () => {
        const searchQuery = form.value.name.trim();

        // Validações iniciais para evitar requisições desnecessárias
        if (searchQuery.length < 4 || searchQuery === lastSearch.value) {
            suggestedImages.value = lastImages.value; // Mostra as últimas imagens válidas
            return;
        }

        try {
            let images = [];

            if (useInternalApi.value) {
                // Tenta buscar na API interna
                const response = await axios.get('/products/images', {
                    params: { term: searchQuery }
                });

                if (response.data.length > 0) {
                    // Se encontrar resultados na API interna
                    images = response.data;
                    suggestedImages.value = images;
                    lastSearch.value = searchQuery;
                    lastImages.value = images; // Atualiza as últimas imagens válidas
                }
            }

            // Se a API interna não encontrar resultados, e se quisermos usar a API do Google
            if (useGoogleApi.value && images.length === 0 && dailyQueryCount.value < dailyQueryLimit) {
                // Realiza a busca na API do Google
                const googleResponse = await axios.get('https://www.googleapis.com/customsearch/v1', {
                    params: {
                        key: apiKey,
                        cx: cx,
                        searchType: 'image',
                        q: searchQuery,
                        num: 10, // Retorna até 10 imagens
                    },
                });

                // Incrementa o contador local apenas em buscas bem-sucedidas
                dailyQueryCount.value++;

                // Filtra os resultados para incluir apenas imagens válidas
                const validFormats = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                const filteredImages = (googleResponse.data.items || []).filter(item => {
                    return validFormats.includes(item.mime);
                });

                // Atualiza as imagens sugeridas com as imagens filtradas do Google
                if (filteredImages.length > 0) {
                    images = filteredImages;
                }

                suggestedImages.value = images;
                lastSearch.value = searchQuery;
                lastImages.value = images; // Atualiza as últimas imagens válidas
            } else if (dailyQueryCount.value >= dailyQueryLimit) {
                apiLimitReached.value = true;
                // notify.error('Limite de consultas atingido para a busca de imagens do Google.');
            }

            // Caso não haja imagens de nenhuma das fontes, adicione uma mensagem de erro ou alerta
            if (images.length === 0) {
                // notify.error('Nenhuma imagem encontrada.');
                console.log('Nenhuma imagem encontrada para:', searchQuery);
            }

        } catch (error) {
            console.error('Erro na busca de imagens:', error);
            // notify.error('Erro ao buscar imagens.');

            // Tenta a outra API se a primeira falhar
            if (useInternalApi.value && useGoogleApi.value) {
                try {
                    const googleResponse = await axios.get('https://www.googleapis.com/customsearch/v1', {
                        params: {
                            key: apiKey,
                            cx: cx,
                            searchType: 'image',
                            q: searchQuery,
                            num: 10, // Retorna até 10 imagens
                        },
                    });

                    // Incrementa o contador local apenas em buscas bem-sucedidas
                    dailyQueryCount.value++;

                    // Filtra os resultados para incluir apenas imagens válidas
                    const validFormats = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    const filteredImages = (googleResponse.data.items || []).filter(item => {
                        return validFormats.includes(item.mime);
                    });

                    // Atualiza as imagens sugeridas com as imagens filtradas do Google
                    if (filteredImages.length > 0) {
                        suggestedImages.value = filteredImages;
                        lastSearch.value = searchQuery;
                        lastImages.value = filteredImages; // Atualiza as últimas imagens válidas
                    }

                } catch (googleError) {
                    console.error('Erro na busca de imagens na API do Google:', googleError);
                    // notify.error('Erro ao buscar imagens na API do Google.');
                }
            }
        }
    }, 2000); // Aguarda 2 segundos antes de executar novamente

    function resetDailyQueryCount() {
        dailyQueryCount.value = 0;
        apiLimitReached.value = false; // Permite novas buscas
    }

    // Agende a reinicialização para o início do próximo dia
    setTimeout(resetDailyQueryCount, 24 * 60 * 60 * 1000); // 24 horas em milissegundos


    const setImage = async (imageUrl) => {
        try {
            // Realiza o download da imagem
            const response = await fetch(imageUrl);
            const blob = await response.blob();

            // Extraí o nome da imagem da URL (por exemplo, 'image.jpg' de 'http://empresapro.test/storage/images/image.jpg')
            const imageName = imageUrl.split('/').pop();

            // Cria um objeto File a partir do blob, mantendo o nome original da imagem
            const file = new File([blob], imageName, { type: blob.type });

            // Atualiza o preview da imagem
            const reader = new FileReader();
            reader.onloadend = () => {
                imagePreview.value = reader.result;
            };
            reader.readAsDataURL(file);

            // Atribui a imagem ao campo do formulário (para envio)
            form.value.image = file;

        } catch (error) {
            console.error('Erro ao baixar a imagem:', error);
            notify.error('Erro ao baixar a imagem. Tente novamente.');
        }
    };



    // Função para abrir o seletor de imagens
    const triggerImageUpload = () => {
        if (imageInput.value) {
            imageInput.value.click(); // Simula o clique no input de arquivo
        }
    };

    // Função para lidar com o upload da imagem
    const handleImageChange = (event) => {
        const file = event.target.files[0];
        if (!file) return;

        const validFormats = ['image/jpeg', 'image/png', 'image/webp'];
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
    // Abre a modal
    const desativarComponentes = () => {
            form.value = {
                name: '',
                image: null,
            };
            imagePreview.value = null; // Reseta o preview ao fechar a modal
            modalStore.deactivateComponent();
        };

    const submitForm = async () => {
        isSubmitting.value = true;
        const formData = new FormData();

        formData.append('name', form.value.name);
        if (form.value.image) {
            formData.append('image', form.value.image);
        }

        // Envia os dados para a API usando router.visit
        router.visit(route('categoria.adicionar'), {
                method: 'post', // Define o método como POST
                data: formData, // Dados do formulário
                preserveState: true, // Preserva o estado atual
                preserveScroll: true, // Mantém a posição do scroll
                onSuccess: () => {
                    desativarComponentes(); // Fecha a modal em caso de sucesso
                    isSubmitting.value = false; // Reabilita o botão
                    notify.success('Categoria cadastrado com sucesso!');
                },
                onError: (errors) => {
                    console.error(errors); // Exibe os erros no console
                    isSubmitting.value = false; // Reabilita o botão para novas tentativas
                    notify.error('Erro ao cadastrar a Categoria.' + errors);

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
        .slide-to-right {
            animation: slideFromRight 0.2s ease-out;
        }

        .fixed {
            z-index: 9999;
        }

        .bg-gray-900 {
            background-color: rgba(0, 0, 0, 0.5); /* Fundo semitransparente */
        }
    </style>
