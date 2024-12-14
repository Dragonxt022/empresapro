// store.js
import { defineStore } from 'pinia';

export const useModalStore = defineStore('modal', {
  state: () => ({
    // Estado para controlar qual componente está ativo
    activeComponent: null, // Nenhum componente ativo inicialmente
  }),
  actions: {
    // Função para ativar o componente
    activateComponent(componentName) {
      this.activeComponent = componentName;  // Ativa o componente
    },
    // Função para desativar o componente
    deactivateComponent() {
      this.activeComponent = null;  // Desativa todos os componentes
    },
  },
});
