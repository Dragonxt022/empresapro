<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: Number,
    required: true,
  },
  // Permite personalizar o valor do campo, como o placeholder, se necessário
  placeholder: {
    type: String,
    default: 'Digite um valor'
  }
});

const internalValue = ref(props.modelValue);

// Função para formatar o número como moeda brasileira
const formatCurrency = (value) => {
  if (!value) return '';
  // Remove tudo que não é número
  value = value.replace(/[^\d]/g, '');
  // Adiciona a vírgula para os centavos
  value = value.replace(/(\d)(\d{2})$/, '$1,$2');
  // Adiciona os separadores de milhar
  return value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
};

// Função para limpar a formatação antes de enviar ao banco
const unformatCurrency = (value) => {
  if (!value) return 0;
  // Remove tudo que não é número
  return value.replace(/[^\d]/g, '');
};

// Função para atualizar o valor ao digitar
const handleInput = (event) => {
  internalValue.value = event.target.value;
  // Atualiza o valor formatado
  const formatted = formatCurrency(event.target.value);
  internalValue.value = formatted;
  // Emite o valor não formatado para o modelo de dados
  emit('update:modelValue', unformatCurrency(event.target.value));
};

watch(() => props.modelValue, (newValue) => {
  internalValue.value = formatCurrency(newValue.toString());
});
</script>

<template>
  <div>
    <input
      type="text"
      :value="internalValue"
      @input="handleInput"
      :placeholder="placeholder"
      class="mt-1 px-3 py-2 w-full border border-gray-300 rounded"
    />
  </div>
</template>

<style scoped>
input {
  text-align: right;
}
</style>
