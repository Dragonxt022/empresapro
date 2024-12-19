<!-- pages -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const activeTab = ref('mesas'); // Define a aba ativa
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Inicio</h1>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Abas -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-4 px-4">
                    <button
                        :class="activeTab === 'mesas' ? 'text-purple-600 border-b-4 border-purple-600' : 'text-gray-500'"
                        class="px-4 py-2 text-sm font-medium focus:outline-none"
                        @click="activeTab = 'mesas'">
                        Mesas
                    </button>
                    <button
                        :class="activeTab === 'balcao' ? 'text-purple-600 border-b-4 border-purple-600' : 'text-gray-500'"
                        class="px-4 py-2 text-sm font-medium focus:outline-none"
                        @click="activeTab = 'balcao'">
                        Balcão
                    </button>
                </nav>
            </div>

            <!-- Conteúdo das Abas -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div v-if="activeTab === 'mesas'" class="col-12">
                            <!-- Conteúdo da aba Mesas -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-5 gap-4">
                                        <!-- Card para mesas ocupadas -->
                                        <div class="flex bg-gray-100 shadow-md rounded-lg overflow-hidden">
                                            <div class="w-2 bg-green-500"></div>
                                            <div class="flex-1 p-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-sm font-bold text-green-500">Ocupado</div>
                                                </div>
                                                <div class="mt-4">
                                                    <span class="mt-2 font-medium text-gray-800 text-lg font-semibold">Mesa 1</span>
                                                </div>
                                                <div class="mt-1 text-gray-800 flex justify-between">
                                                    <p class="font-semibold">R$ 50,00</p>
                                                    <p><i class="fa-regular fa-clock"></i> <span class="font-semibold text-gray-800">1h 30min</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex bg-gray-100 shadow-md rounded-lg overflow-hidden">
                                            <!-- Borda maior no lado esquerdo -->
                                            <div class="w-2 bg-gray-400"></div>

                                            <!-- Conteúdo do card -->
                                            <div class="flex-1 p-4">
                                                <div class="flex items-center justify-between">
                                                    <!-- Nome da Mesa e Status -->
                                                    <div class="flex items-center">
                                                        <!-- Ícone para mesa aberta -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="text-sm font-bold text-gray-400">Livre</div>
                                                </div>
                                                <div class="mt-4">
                                                    <span class="mt-2 font-medium text-gray-800 text-lg font-semibold">Mesa 1</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeTab === 'balcao'" class="col-12">
                            <!-- Conteúdo da aba Balcão -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="grid grid-cols-5 gap-4">
                                        <!-- Card para mesas ocupadas -->
                                        <div class="flex bg-gray-100 shadow-md rounded-lg overflow-hidden">
                                            <div class="w-2 bg-green-500"></div>
                                            <div class="flex-1 p-4">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="text-sm font-bold text-green-500">Ocupado</div>
                                                </div>
                                                <div class="mt-4">
                                                    <span class="mt-2 font-medium text-gray-800 text-lg font-semibold">Balcão 1</span>
                                                </div>
                                                <div class="mt-1 text-gray-800 flex justify-between">
                                                    <p class="font-semibold">R$ 50,00</p>
                                                    <p><i class="fa-regular fa-clock"></i> <span class="font-semibold text-gray-800">1h 30min</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex bg-gray-100 shadow-md rounded-lg overflow-hidden">
                                            <!-- Borda maior no lado esquerdo -->
                                            <div class="w-2 bg-gray-400"></div>

                                            <!-- Conteúdo do card -->
                                            <div class="flex-1 p-4">
                                                <div class="flex items-center justify-between">
                                                    <!-- Nome da Mesa e Status -->
                                                    <div class="flex items-center">
                                                        <!-- Ícone para mesa aberta -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="text-sm font-bold text-gray-400">Livre</div>
                                                </div>
                                                <div class="mt-4">
                                                    <span class="mt-2 font-medium text-gray-800 text-lg font-semibold">Mesa 1</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
