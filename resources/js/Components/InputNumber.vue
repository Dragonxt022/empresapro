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
        const integer = (value / 100).toFixed(2).replace(".", ","); // Ex.: 1 -> "0,01"
        return `R$ ${integer}`;
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
