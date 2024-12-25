<script setup>
import { ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Usando a função usePage para acessar os dados do flash
const page = usePage();
const show = ref(false);  // A notificação começa oculta
const message = ref('');
const style = ref('success'); // Estilo inicial como 'success'

// Monitorando os dados do flash
watchEffect(() => {
    if (page.props.flash?.banner) {
        message.value = page.props.flash.banner;  // Mensagem do flash
        style.value = page.props.flash.bannerStyle || 'success'; // Estilo da mensagem
        show.value = true;  // Exibe a notificação

        // Após 3 segundos, a notificação desaparece
        setTimeout(() => {
            show.value = false;
        }, 3000);
    }
});
</script>

<template>
  <!-- Modal com animação de transição -->
  <transition name="slide-up" @after-leave="show = false">
    <div v-if="show" :class="[
          'fixed bottom-0 left-0 w-full p-4 text-white text-center transition-all duration-500 ease-in-out',
          style === 'success' ? 'bg-green-500' : 'bg-red-500',
          'transform translate-y-full',
          'transition-transform duration-500 ease-in-out'
        ]">
      <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between">
          <p>{{ message }}</p>
          <button @click="show = false" class="text-white font-bold text-lg">✕</button>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
/* Animação de transição do slide de baixo para cima */
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.5s ease-in-out;
}
.slide-up-enter, .slide-up-leave-to /* .slide-up-leave-active em versões antigas */ {
  transform: translateY(100%);
}
</style>
