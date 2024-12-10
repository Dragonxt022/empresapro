<script>
import { useToast } from 'vue-toastification';
import { onMounted } from 'vue';

export default {
    props: {
        toastMessage: String, // Recebendo a mensagem de sucesso ou erro via props
    },

    setup(props) {
        const toast = useToast(); // Hook do Toast

        onMounted(() => {
            // Dispara a notificação automaticamente quando o componente for montado
            if (props.toastMessage) {
                toast.success(props.toastMessage, {
                    position: "top-center", // Posição centralizada no topo
                    timeout: 2000, // Duração do toast
                    transition: "fade", // Tipo de animação
                });
            }
        });

        return {};
    },
};
</script>

<style>
    /* Animação personalizada para as notificações */
    .toast-enter-active, .toast-leave-active {
        transition: transform 0.3s ease;
    }

    .toast-enter, .toast-leave-to {
        transform: translateY(-100%);
    }
</style>
