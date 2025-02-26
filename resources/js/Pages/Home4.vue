<template>
    <div class="layout-container">
        <!-- Sidebar Flottant -->
        <nav class="sidebar">
            <img 
                src="/image/logo.png"
                alt="Logo"
                class="logo"
            />
            <div class="nav-links">
                <Link href="/devis" class="nav-link" :class="{ active: $page.url === '/devis' }">
                    <i class="fas fa-file-invoice"></i>
                    <span>Faire un devis</span>
                </Link>
                <Link href="/clients" class="nav-link" :class="{ active: $page.url === '/clients' }">
                    <i class="fas fa-users"></i>
                    <span>Clients</span>
                </Link>
                <Link href="/bon-commande" class="nav-link" :class="{ active: $page.url === '/bon-commande' }">
                    <i class="fas fa-file-alt"></i>
                    <span>Bon de commande</span>
                </Link>
                <Link href="/info-prime" class="nav-link" :class="{ active: $page.url === '/info-prime' }">
                    <i class="fas fa-info-circle"></i>
                    <span>Info prime</span>
                </Link>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-wrapper">
            <!-- Profile Menu Fixed -->
            <div class="profile-section" ref="profileSection">
                <div class="profile-photo-container" @click="toggleMenu">
                    <div class="profile-circle">
                        {{ userInitials }}
                    </div>
                </div>
                
                <!-- Menu déroulant -->
                <div v-show="showMenu" class="profile-menu">
                    <div class="profile-info">
                        <div class="profile-circle large">
                            {{ userInitials }}
                        </div>
                        <p class="profile-name">{{ userName }}</p>
                        <p class="profile-role">{{ userRole }}</p>
                    </div>
                    <hr>
                    <Link href="/profil" class="menu-link">
                        <i class="fas fa-user"></i>
                        <span>Mon profil</span>
                    </Link>
                    <Link href="/parametres" class="menu-link">
                        <i class="fas fa-cog"></i>
                        <span>Paramètres</span>
                    </Link>
                    <button @click="handleLogout" class="menu-link logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </button>
                </div>
            </div>

            <!-- Page Content -->
            <main class="content">
                <slot></slot>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted } from 'vue';

const userName = ref('Dimi');
const userRole = ref('Vendeur');
const showMenu = ref(false);
const profileSection = ref(null);

// Calculer les initiales du nom d'utilisateur
const userInitials = computed(() => {
    const names = userName.value.split(' ');
    if (names.length > 1) {
        return `${names[0][0]}${names[1][0]}`.toUpperCase();
    }
    return names[0][0].toUpperCase();
});

const toggleMenu = () => {
    showMenu.value = !showMenu.value;
};

const closeMenu = (e) => {
    if (profileSection.value && !profileSection.value.contains(e.target)) {
        showMenu.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeMenu);
});

onUnmounted(() => {
    document.removeEventListener('click', closeMenu);
});

const handleLogout = () => {
    router.post('/logout');
};
</script>

<style scoped>


.layout-container {
    display: flex;
    min-height: 100vh;
    padding: 1rem;
    gap: 1rem;
    background-color: #f8fafc;
}

.sidebar {
    width: 280px;
    background: linear-gradient(135deg, #1646c0 0%, #c0c0c3 100%);
    border-radius: 1rem;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 2rem;
    height: calc(100vh - 2rem);
    position: sticky;
    top: 1rem;
    transition: all 0.3s ease;
}

.sidebar-title {
    color: white;
    font-size: 1.25rem;
    text-align: center;
    margin-bottom: 1rem;
    font-weight: 600;
}

.nav-links {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex-grow: 1;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: white;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
}

.nav-link.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
}

.nav-link i {
    width: 1.5rem;
    text-align: center;
}

.main-wrapper {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.top-nav {
    background: white;
    border-radius: 1rem;
    padding: 1rem 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: flex-start;
    align-items: center;
    height: 64px;
}



.profile-photo-container {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.profile-photo-container:hover {
    transform: scale(1.05);
}

.profile-circle {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #040083 0%, #1919b4 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.profile-circle.large {
    width: 60px;
    height: 60px;
    font-size: 1.5rem;
    margin: 0 auto 1rem;
}

.profile-circle:hover {
    border-color: #040083;
    box-shadow: 0 2px 8px rgba(4, 0, 131, 0.2);
}

.profile-section {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1000;
}

.profile-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0; /* Aligné à droite au lieu de gauche */
    left: auto;
    width: 240px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    animation: menuFade 0.2s ease;
    overflow: hidden;
}

@keyframes menuFade {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-info {
    padding: 1.5rem;
    text-align: center;
    background: linear-gradient(to bottom, #f8fafc, white);
}

.profile-name {
    font-weight: 600;
    margin: 0;
    color: #1e293b;
    font-size: 1.1rem;
}

.profile-role {
    color: #040083;
    font-size: 0.9rem;
    margin: 0.25rem 0 0 0;
    font-weight: 500;
}

.menu-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    color: #1e293b;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 0.95rem;
}

.menu-link:hover {
    background: #f8fafc;
    color: #040083;
}

.menu-link.logout {
    color: #dc2626;
}

.menu-link.logout:hover {
    background: #fef2f2;
    color: #dc2626;
}

.menu-link i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

hr {
    border: none;
    border-top: 1px solid #e2e8f0;
    margin: 0;
}

.content {
    margin-top: 1rem;
    padding-top: 3rem; /* Pour éviter que le contenu ne soit caché derrière le menu profil */
}

/* Responsive Design */
@media (max-width: 768px) {
    .layout-container {
        flex-direction: column;
    }

    .sidebar {
        width: 100%;
        height: auto;
        position: static;
    }

    .nav-links {
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }

    .nav-link {
        flex: 1;
        min-width: 200px;
    }

    .profile-menu {
        right: 0;
        left: auto;
    }
}

@media (max-width: 640px) {
    .layout-container {
        padding: 0.5rem;
    }

    .nav-link {
        min-width: 150px;
    }

    .content {
        padding: 1rem;
    }
}
</style>