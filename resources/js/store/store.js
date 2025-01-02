// store.js
import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
  state: () => ({
    activeComponent: null, // Componente ativo
    selectedPaymentMethod: null, // Método de pagamento selecionado
    isSalesPanelVisible: false, // Exibir ou ocultar o painel de vendas
    salesPanelData: null, // Dados da mesa e do painel de vendas
  }),
  actions: {
    // Ativar componente
    activateComponent(componentName) {
      this.activeComponent = componentName;
    },
    // Desativar componente
    deactivateComponent() {
      this.activeComponent = null;
      this.selectedPaymentMethod = null;
      this.isSalesPanelVisible = false;
      this.salesPanelData = null;
    },
    // Ativar painel de vendas
    openSalesPanel(data) {
      this.isSalesPanelVisible = true;
      this.salesPanelData = data;
    },
    // Fechar painel de vendas
    closeSalesPanel() {
      this.isSalesPanelVisible = false;
      this.salesPanelData = null;
    },
    // Abrir modal de formas de pagamento
    openPaymentMethodsModal() {
      this.activateComponent('PaymentMethods');
    },
    // Definir método de pagamento selecionado
    setSelectedPaymentMethod(paymentMethod) {
      this.selectedPaymentMethod = paymentMethod;
    },
  },
});
