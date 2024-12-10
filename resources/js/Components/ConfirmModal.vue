<template>
    <transition name="fade">
        <div
            v-if="isOpen"
            class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50"
        >
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    {{ isWarning ? 'Aviso' : 'Confirmação' }}
                </h3>
                <p class="text-gray-600 mb-6">{{ message }}</p>
                <div class="flex justify-end gap-2">
                    <button
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        @click="$emit('cancelar')"
                    >
                        Fechar
                    </button>
                    <button
                        v-if="!isWarning"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600"
                        @click="$emit('confirmar')"
                    >
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>

<script setup>
defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    isWarning: {
        type: Boolean,
        default: false,
    },
    message: {
        type: String,
        required: true,
    },
});

defineEmits(['cancelar', 'confirmar']);
</script>
