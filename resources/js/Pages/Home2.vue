<template>
    <div class="app-container">
        <!-- Utilisation du composant TopNav -->
        <TopNav 
            :user-name="'Dimi'"
            :user-role="'Vendeur'"
            @logout="handleLogout"
        />

        <!-- Menu latéral -->
        <div class="sidebar">
            <nav class="side-menu">
                <a href="/devis" class="menu-item">
                    <i class="fas fa-file-invoice"></i>
                    <span>Faire un devis</span>
                </a>
                <a href="/clients" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Clients</span>
                </a>
                <a href="/bon-commande" class="menu-item">
                    <i class="fas fa-file-alt"></i>
                    <span>Bon de commande</span>
                </a>
                <a href="/info-prime" class="menu-item">
                    <i class="fas fa-info-circle"></i>
                    <span>Info prime</span>
                </a>
            </nav>
        </div>

        <!-- Contenu principal -->
        <main class="main-content">
            <slot></slot>
        </main>
    </div>
</template>

<script setup>
import TopNav from '@/Components/TopNav.vue';
import { router } from '@inertiajs/vue3';

// Fonction de déconnexion
const handleLogout = () => {
    router.post('/logout');
};
</script>

<style scoped>
.app-container {
    display: grid;
    grid-template-areas:
        "top-nav top-nav"
        "sidebar main";
    grid-template-columns: 250px 1fr;
    grid-template-rows: 80px 1fr;
    min-height: 100vh;
}

/* Menu latéral */
.sidebar {
    grid-area: sidebar;
    background: #2c3e50;
    padding: 20px 0;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    gap: 10px;
}

.menu-item:hover {
    background: #34495e;
}

.main-content {
    grid-area: main;
    padding: 20px;
    background: #f8f9fa;
}

/* Responsive */
@media (max-width: 768px) {
    .app-container {
        grid-template-columns: 200px 1fr;
    }
}

@media (max-width: 576px) {
    .app-container {
        grid-template-areas:
            "top-nav"
            "main";
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        display: none;
    }
}
</style>