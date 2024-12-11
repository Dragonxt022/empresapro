<template>
    <div>
        <aside class="main-sidebar elevation-2 sidebar-light-primary">
            <!-- Brand Logo -->
            <a href="#" class="brand-link" @click.prevent="navigateTo('dashboard')">
                <span class="brand-text font-weight-light">PRO Vendas</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <!-- <img src="" class="img-circle elevation-2" alt="User Image"> -->
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"> Olá, Bem vindo</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                <div class="form-inline">
                    <div class="input-group" data-widget="sidebar-search">
                        <input class="form-control form-control-sidebar" type="search" placeholder="buscar..." aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-sidebar">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="#" class="nav-link" @click.prevent="navigateTo('dashboard')">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Inicio</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" @click.prevent="navigateTo('historico')">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Histórico</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link" @click.prevent="navigateTo('ajustes')">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>Ajustes</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    </div>
</template>


<script setup>
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'; // Importa o router diretamente

const navigateTo = (routeName) => {
    router.visit(route(routeName), {
        preserveScroll: true, // Mantém o scroll atual
        preserveState: false // Reseta o estado
    });
};
</script>

