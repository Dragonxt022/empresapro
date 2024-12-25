<script>
import { useToast } from 'vue-toastification';

export default {
    setup() {
        const toast = useToast(); // Aqui usamos o hook useToast para acessar o Toast

        const showToast = () => {
            toast.success("Mensagem de sucesso!", {
                position: "top-center", // Posição centralizada no topo
                timeout: 3000, // Duração do toast
                transition: "fade", // Defina o tipo de animação
            });
        };

        return { showToast };
    }
};
</script>

<style>
    /* Adiciona animação para as notificações */
    .toast-enter-active, .toast-leave-active {
        transition: transform 0.3s ease;
    }
    .toast-enter, .toast-leave-to {
        transform: translateY(-100%);
    }

</style>

<template>
  <button @click="showToast">Mostrar Notificação</button>
</template>
