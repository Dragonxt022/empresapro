<template>
    <div>
      <input
        type="text"
        :value="formattedValue"
        @input="onInput"
        @keydown="allowOnlyNumbers"
        placeholder="R$ 0,00"
        class="mt-1 px-3 py-2 w-full border border-gray-300"
      />
    </div>
  </template>

  <script>
  export default {
    props: {
      // Valor inicial do input (em centavos)
      modelValue: {
        type: Number,
        default: 0,
      },
    },
    computed: {
      // Exibe o valor formatado como moeda
      formattedValue() {
        return this.formatCurrency(this.modelValue);
      },
    },
    methods: {
      // Atualiza o valor ao digitar
      onInput(event) {
        const rawValue = event.target.value.replace(/\D/g, ""); // Remove caracteres não numéricos
        const numericValue = parseInt(rawValue || "0", 10);

        // Emite o valor em centavos para o pai
        this.$emit("update:modelValue", numericValue);
      },

      // Formata o número como moeda (R$ 0,00)
      formatCurrency(value) {
        // Verifica se o valor é nulo ou indefinido e retorna "R$ 0,00" nesse caso
        if (value === null || value === undefined) return 'R$ 0,00';

        // Formata o valor, dividindo por 100 para lidar com centavos
        let formattedValue = (value / 100).toFixed(2);

        // Substitui o ponto por vírgula para separar os centavos
        formattedValue = formattedValue.replace('.', ',');

        // Formata o número com pontos para separação de milhar
        formattedValue = formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return `R$ ${formattedValue}`;
        },

      // Permite apenas números no input
      allowOnlyNumbers(event) {
        const allowedKeys = ["Backspace", "Tab", "ArrowLeft", "ArrowRight"];
        const isNumberKey = event.key >= "0" && event.key <= "9";
        if (!isNumberKey && !allowedKeys.includes(event.key)) {
          event.preventDefault();
        }
      },
    },
  };
  </script>
