<template>
  <div>
    <!-- Modal -->
    <transition name="slide">
      <div
        v-if="isModalOpen"
        class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-end items-start"
      >
        <!-- Modal Content -->
        <div
          class="modal-content w-1/2 bg-white h-full p-6 overflow-y-auto transform transition-transform rounded-lg shadow-lg"
          :class="{
            'slide-from-right': isModalOpen,
            'slide-to-right': !isModalOpen,
          }"
        >
          <!-- Header -->
          <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h2 class="text-2xl font-semibold flex items-center space-x-2">
              <span class="material-icons text-blue-500">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  style="fill: rgba(8, 118, 178, 1); transform: 0"
                >
                  <path
                    d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"
                  ></path>
                  <path d="M11 11h2v6h-2zm0-4h2v2h-2z"></path>
                </svg>
              </span>
              <span class="text-gray-800">Detalhes da Venda</span>
            </h2>
            <button
              @click="closeModal"
              class="close-button absolute top-4 right-4 text-gray-500"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Venda Detalhes -->
          <div v-if="detalhesVenda">
            <div
              class="grid grid-cols-8 gap-4 mb-6 flex justify-between text-center"
            >
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Status do Caixa</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesVenda.status_caixa || 'Não informado' }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Data de Abertura</p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesVenda.data_hora_abertura }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">
                  Data de Fechamento
                </p>
                <p class="font-semibold text-gray-800">
                  {{ detalhesVenda.data_hora_fechamento }}
                </p>
              </div>
              <div class="col-span-2">
                <p class="text-gray-600 uppercase text-xs">Valor Final</p>
                <p class="font-semibold text-gray-800">
                  R$ {{ detalhesVenda.valor_total_final }}
                </p>
              </div>
            </div>

            <!-- Resumo de Valores -->
            <div class="border-t pt-4 mb-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span class="text-gray-800">Resumo de Valores</span>
              </h3>
              <div class="grid grid-cols-2 gap-4">
                <div class="text-gray-600">Valor Abertura</div>
                <div class="font-semibold text-right">
                  R$ {{ detalhesVenda.valor_abertura }}
                </div>

                <div class="text-gray-600">Total Entradas</div>
                <div class="font-semibold text-right text-gray-700">
                  R$ {{ detalhesVenda.total_entradas }}
                </div>

                <div class="text-gray-600">Total Saídas</div>
                <div class="font-semibold text-right text-gray-700">
                  R$ {{ detalhesVenda.total_saidas }}
                </div>

                <div class="text-gray-600">Diferença</div>
                <div
                  class="font-semibold text-right"
                  :class="
                    detalhesVenda.valor_Diferenca >= 0
                      ? 'text-green-500'
                      : 'text-red-500'
                  "
                >
                  R$ {{ detalhesVenda.valor_Diferenca }}
                </div>

                <div class="text-gray-700 font-semibold">Valor Final</div>
                <div class="font-semibold text-right text-gray-900">
                  R$ {{ detalhesVenda.valor_total_final }}
                </div>
              </div>
            </div>

            <!-- Resumo de Pagamentos -->
            <div class="border-t pt-4 mt-6">
              <h3 class="font-semibold text-lg mb-4">Totais por Método</h3>
              <ul>
                <li
                  v-for="(metodo, key) in detalhesVenda.totais_por_metodo"
                  :key="key"
                  class="flex justify-between"
                >
                  <span>{{ metodo.nome }}</span>
                  <span class="text-gray-900 font-semibold">
                    R$ {{ metodo.total }}
                  </span>
                </li>
              </ul>
            </div>

            <div class="border-t pt-4 mt-6">
              <h3 class="font-semibold text-lg mb-4">
                Operações de caixa
                <span class="text-gray-500 text-sm">
                  ({{ detalhesVenda.operacoes.length }} operação)
                </span>
              </h3>

              <table
                class="table-auto w-full border-collapse border border-gray-300"
              >
                <thead>
                  <tr class="text-center">
                    <th class="px-4 py-2 text-left border-b">Descrição</th>
                    <th class="px-4 py-2 border-b">Tipo</th>
                    <th class="px-4 py-2 border-b">Valor</th>
                    <th class="px-4 py-2 border-b">Data e Hora</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Iterando sobre as operações -->
                  <tr
                    v-for="(operacao, index) in detalhesVenda.operacoes"
                    :key="index"
                    class="text-center"
                  >
                    <td class="px-4 py-2 text-left border-b">
                      {{ operacao.descricao }}
                    </td>
                    <td class="px-4 py-2 border-b">
                      <span
                        v-if="operacao.tipo === 'entrada'"
                        class="text-green-500"
                      >
                        <i class="fas fa-plus-circle"></i>
                        <!-- Ícone de entrada -->
                      </span>
                      <span
                        v-if="operacao.tipo === 'saida'"
                        class="text-red-500"
                      >
                        <i class="fas fa-minus-circle"></i>
                        <!-- Ícone de saída -->
                      </span>
                    </td>
                    <td class="px-4 py-2 border-b text-gray-700 font-semibold">
                      R$ {{ operacao.valor }}
                    </td>
                    <td class="px-4 py-2 border-b text-gray-500 font-semibold">
                      {{ formatDataHora(operacao.data_hora) }}
                      <!-- Função para formatar a data -->
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Vendas com Gavetas (Accordion) -->
            <div class="border-t pt-4 mt-6">
              <h3
                class="font-semibold text-lg mb-4 flex items-center space-x-2"
              >
                <span class="text-gray-800">Resumo de Vendas</span>
                <span class="text-gray-500 text-sm">
                  ({{ detalhesVenda.vendas.length }} vendas)
                </span>
              </h3>

              <!-- Mostra apenas as primeiras 50 vendas -->
              <div
                v-for="(venda, index) in detalhesVenda.vendas.slice(0, 25)"
                :key="venda.id"
                class="mb-4"
              >
                <div
                  @click="toggleVenda(index)"
                  class="cursor-pointer bg-gray-100 p-4 rounded-lg border border-gray-300"
                >
                  <div class="flex justify-between">
                    <div class="text-gray-800 font-semibold">
                      Venda {{ index + 1 }} - Mesa: {{ venda.mesa }}
                    </div>
                    <div
                      :class="{
                        'text-green-500': venda.valor_total >= 0,
                        'text-red-500': venda.valor_total < 0,
                      }"
                    >
                      R$ {{ venda.valor_total }}
                    </div>
                  </div>
                </div>
                <div
                  v-show="isVendaOpen(index)"
                  class="mt-4 p-4 bg-gray-50 border-t border-gray-300 rounded-b-lg"
                >
                  <p>
                    <strong>Cliente:</strong>
                    {{ venda.cliente }}
                  </p>
                  <p>
                    <strong>Status:</strong>
                    {{ venda.status }}
                  </p>

                  <h4 class="mt-4 font-semibold">Produtos:</h4>
                  <ul>
                    <li
                      v-for="produto in venda.produtos"
                      :key="produto.id"
                      class="flex justify-between"
                    >
                      <span>
                        {{ produto.nome }} (x{{ produto.quantidade }})
                      </span>
                      <span>R$ {{ produto.valor_total }}</span>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- Mensagem para visualizar todas as vendas -->
              <div
                v-if="detalhesVenda.vendas.length > 50"
                class="mt-6 text-center"
              >
                <p class="text-gray-600">Exibindo as 50 primeiras vendas.</p>
                <p class="text-gray-800 font-semibold">
                  Para visualizar todas as vendas, imprima o relatório completo.
                </p>
              </div>
            </div>

            <!-- Botão para excluir venda -->
            <div class="mt-6 flex justify-center">
              <button
                class="text-red-500 px-4 py-2 rounded-md flex items-center font-bold"
                @click="openDeleteConfirmationModal"
              >
                <i class="fa fa-trash text-red-500"></i>
                <span class="ms-2">Excluir Venda</span>
              </button>

              <button
                @click="gerarPDF"
                class="px-4 py-2 text-blue-500 rounded-md"
              >
                <i class="fa-solid fa-print text-blue-500"></i>
                <span class="ms-2">Imprimir Relatório</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
import { jsPDF } from 'jspdf';
import { notify } from '@/Plugins/Notify';

export default {
  props: {
    isModalOpen: Boolean,
    caixaId: Number,
  },
  data() {
    return {
      detalhesVenda: null,
      isDeleteModalOpen: false,
      vendaAberta: null,
    };
  },
  watch: {
    caixaId(newId) {
      if (newId) {
        this.fetchDetalhesVenda(newId);
      }
    },
  },
  methods: {
    async fetchDetalhesVenda(caixaId) {
      try {
        const response = await axios.get(`/api/detalhes-caixa/${caixaId}`);
        if (response.data.success) {
          this.detalhesVenda = response.data.data;
          console.log('Dados recebidos:', this.detalhesVenda); // Verificar os dados
        }
      } catch (error) {
        console.error('Erro ao buscar os detalhes da venda:', error);
      }
    },
    closeModal() {
      this.$emit('update:isModalOpen', false);
    },
    openDeleteConfirmationModal() {
      this.isDeleteModalOpen = true;
    },
    closeDeleteConfirmationModal() {
      this.isDeleteModalOpen = false;
    },
    toggleVenda(index) {
      if (this.vendaAberta === index) {
        this.vendaAberta = null;
      } else {
        this.vendaAberta = index;
      }
    },
    // Verifica se a venda está aberta
    isVendaOpen(index) {
      return this.vendaAberta === index;
    },

    capitalize(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    },

    formatDataHora(dataHora) {
      const options = {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
      };

      const data = new Date(dataHora);
      return data.toLocaleString('pt-BR', options);
    },

    gerarPDF() {
      const doc = new jsPDF();
      let y = 20; // Posição inicial do texto

      // Título do relatório
      doc.setFont('helvetica', 'bold');
      doc.setFontSize(16);
      doc.text('Relatório de Detalhes de Venda', 20, y);
      y += 20;

      // Status do Caixa
      doc.setFontSize(12);
      doc.text(
        `Status do Caixa: ${
          this.detalhesVenda.status_caixa || 'Não informado'
        }`,
        20,
        y
      );
      y += 10;

      // Data de Abertura
      doc.text(
        `Data de Abertura: ${this.detalhesVenda.data_hora_abertura}`,
        20,
        y
      );
      y += 10;

      // Data de Fechamento
      doc.text(
        `Data de Fechamento: ${this.detalhesVenda.data_hora_fechamento}`,
        20,
        y
      );
      y += 10;

      // Valor Final
      doc.text(
        `Valor Final: R$ ${this.detalhesVenda.valor_total_final}`,
        20,
        y
      );
      y += 15;

      // Resumo de Valores
      doc.setFont('helvetica', 'bold');
      doc.text('Resumo de Valores', 20, y);
      y += 10;

      // Resumo de Valores
      doc.setFont('helvetica', 'normal');
      doc.text(
        `Valor Abertura: R$ ${this.detalhesVenda.valor_abertura}`,
        20,
        y
      );
      y += 10;
      doc.text(
        `Total Entradas: R$ ${this.detalhesVenda.total_entradas}`,
        20,
        y
      );
      y += 10;
      doc.text(`Total Saídas: R$ ${this.detalhesVenda.total_saidas}`, 20, y);
      y += 10;
      doc.text(`Diferença: R$ ${this.detalhesVenda.valor_Diferenca}`, 20, y);
      y += 10;
      doc.text(
        `Valor Final: R$ ${this.detalhesVenda.valor_total_final}`,
        20,
        y
      );
      y += 15;

      // Resumo de Pagamentos
      doc.setFont('helvetica', 'bold');
      doc.text('Totais por Método de Pagamento', 20, y);
      y += 10;
      for (const metodo of Object.values(
        this.detalhesVenda.totais_por_metodo
      )) {
        doc.text(`${metodo.nome}: R$ ${metodo.total}`, 20, y);
        y += 10;
      }

      y += 15;

      // Vendas e Produtos
      doc.setFont('helvetica', 'bold');
      doc.text('Vendas Realizadas', 20, y);
      y += 10;

      for (const venda of this.detalhesVenda.vendas) {
        doc.setFont('helvetica', 'bold');
        doc.text(`Mesa: ${venda.mesa}`, 20, y);
        y += 5;
        doc.text(`Cliente: ${venda.cliente}`, 20, y);
        y += 5;
        doc.text(`Valor Total: R$ ${venda.valor_total}`, 20, y);
        y += 10;

        doc.setFont('helvetica', 'normal');
        doc.text('Produtos:', 20, y);
        y += 5;

        // Tabela de Produtos da Venda
        for (const produto of venda.produtos) {
          doc.text(
            `${produto.nome} x${produto.quantidade}: R$ ${produto.valor_total}`,
            20,
            y
          );
          y += 5;
        }

        y += 10; // Espaçamento entre vendas
      }

      y += 15;

      // Operações de Caixa
      doc.setFont('helvetica', 'bold');
      doc.text('Operações de Caixa', 20, y);
      y += 10;

      // Tabela de Operações
      doc.setFont('helvetica', 'normal');
      for (const operacao of this.detalhesVenda.operacoes) {
        doc.text(`Descrição: ${operacao.descricao}`, 20, y);
        y += 5;
        doc.text(
          `Tipo: ${operacao.tipo === 'entrada' ? 'Entrada' : 'Saída'}`,
          20,
          y
        );
        y += 5;
        doc.text(`Valor: R$ ${operacao.valor}`, 20, y);
        y += 5;
        doc.text(
          `Data e Hora: ${this.formatDataHora(operacao.data_hora)}`,
          20,
          y
        );
        y += 10;
      }

      // Salvando o PDF
      doc.save('relatorio_venda.pdf');
    },
  },
};
</script>

<style scoped>
/* Animação de entrada para a modal */
@keyframes modalFadeIn {
  0% {
    opacity: 0;
    transform: scale(0.95);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

/* Aplicar a animação de fade-in e zoom-in */
.animate-modal {
  animation: modalFadeIn 0.4s ease-out;
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

.slide-from-right {
  animation: slide-in 0.3s ease-out;
}
.slide-to-right {
  animation: slide-out 0.3s ease-in;
}

@keyframes slide-in {
  0% {
    transform: translateX(100%);
  }
  100% {
    transform: translateX(0);
  }
}
@keyframes slide-out {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(100%);
  }
}

.modal-content {
  position: relative;
}
</style>
