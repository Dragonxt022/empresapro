<script setup>
import CardComponent from '../Cards/CardComponent.vue';

</script>

<style>

</style>

<template>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header py-4">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ajustes</h1>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Cadastros Section -->
                <div class="row mb-2 mt-5">
                    <div class="col-sm-6">
                        <h4>Cadastros</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">


                            <!-- Card 1 -->
                            <a :href="route('produtos')" class="nav-link" data-inertia-prefetch>
                                <CardComponent
                                    icon="fas fa-box"
                                    title="Produtos"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"

                                />
                            </a>
                            <!-- Card 2 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-tags"
                                    title="Categorias"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                            <!-- Card 3 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-user-friends"
                                    title="Clientes"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                            <!-- Card 4 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-users-cog"
                                    title="Usuários"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Relatórios Section -->
                <div class="row mb-2 mt-5">
                    <div class="col-sm-6">
                        <h4>Relatórios</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                            <!-- Card 1 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-cash-register"
                                    title="Caixas"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                            <!-- Card 2 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-chart-pie"
                                    title="Painel"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Configurações Section -->
                <div class="row mb-2 mt-5">
                    <div class="col-sm-6">
                        <h4>Configurações</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                            <!-- Card 1 -->
                            <a :href="route('ajustes')" class="nav-link">
                                <CardComponent
                                    icon="fas fa-tools"
                                    title="Geral"
                                    bgColor="bg-white"
                                    textColor="text-blue-500"
                                />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

</template>


