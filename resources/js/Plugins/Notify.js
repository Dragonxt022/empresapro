import { useToast } from 'vue-toastification';

export const notify = {
    success(message, options = {}) {
        useToast().success(message, options);
    },
    error(message, options = {}) {
        useToast().error(message, options);
    },
    info(message, options = {}) {
        useToast().info(message, options);
    },
    warning(message, options = {}) {
        useToast().warning(message, options);
    },
};

export default {
    install(app) {
        app.config.globalProperties.$notify = notify; // Adiciona o `notify` ao Vue globalmente
    },
};
