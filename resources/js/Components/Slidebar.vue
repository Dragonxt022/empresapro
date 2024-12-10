<template>
    <div>
        <aside class="main-sidebar elevation-2 sidebar-light-primary">
            <!-- Brand Logo -->
            <a href="" class="brand-link">
            <img src="" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">PRO Vendas</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                <img src="" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                <a href="#" class="d-block"> Olá, Bem vido</a>
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
                        <a :href="route('dashboard')" class="nav-link" data-inertia-prefetch>
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a :href="route('historico')" class="nav-link" data-inertia-prefetch>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Histórico
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a :href="route('ajustes')" class="nav-link" data-inertia-prefetch>
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Ajustes
                            </p>
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

    import { defineProps } from 'vue';

    // Definindo a prop 'user' que será passada para o Slidebar
    const props = defineProps({
    user: Object,  // A prop user será recebida aqui
    });

</script>

