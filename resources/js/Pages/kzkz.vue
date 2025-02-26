<template>
    <div class="menu-container">
        <!-- Logo Section -->
        <div class="logo-container">
            <img 
                src="/image/logo.png"
                alt="Logo Renovation"
                class="logo"
            />
        </div>

        <!-- Bouton de déconnexion -->
        <div class="logout-container">
            <button @click="logout" class="logout-button">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </button>
        </div>

        <!-- Section des boutons de menu -->
        <div class="menu-buttons">
            <!-- Bouton Faire un devis -->
            <Link 
                href="/devis"
                class="menu-button devis-button"
            >
                <div class="button-content">
                    <i class="fas fa-file-invoice fa-3x mb-3"></i>
                    <span class="button-text">Faire un devis</span>
                </div>
            </Link>

            <!-- Bouton Clients -->
            <Link 
                href="/clients"
                class="menu-button clients-button"
            >
                <div class="button-content">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <span class="button-text">Clients</span>
                </div>
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

const logout = () => {
    router.post(route('logout'));
};
</script>

<style scoped>
/* Styles existants */
.menu-container {
    min-height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    padding: 20px;
}

/* Nouveau style pour le bouton de déconnexion */
.logout-container {
    position: absolute;
    top: 20px;
    right: 20px;
}

.logout-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.logout-button:hover {
    background-color: #c82333;
    transform: translateY(-2px);
}

.logout-button i {
    font-size: 1.2rem;
}

/* Reste des styles existants... */
.logo-container {
    position: absolute;
    top: 20px;
    left: 20px;
}

.logo {
    max-width: 200px;
    height: auto;
}

.menu-buttons {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    max-width: 1200px;
    width: 100%;
}

/* ... autres styles ... */

/* Ajout de styles responsifs pour le bouton de déconnexion */
@media (max-width: 768px) {
    .logout-button span {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    .logout-container {
        top: 10px;
        right: 10px;
    }

    .logout-button {
        padding: 8px 12px;
    }

    .logout-button span {
        display: none; /* Cache le texte sur mobile */
    }

    .logout-button i {
        font-size: 1.1rem;
        margin: 0;
    }
}
.menu-container {
    min-height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    padding: 20px;
}

.logo-container {
    position: absolute;
    top: 20px;
    left: 20px;
}

.logo {
    max-width: 200px;
    height: auto;
}

.menu-buttons {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    max-width: 1200px;
    width: 100%;
}

.menu-button {
    width: 300px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.menu-button:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.devis-button {
    background: linear-gradient(135deg, #4CAF50, #45a049);
}

.clients-button {
    background: linear-gradient(135deg, #2196F3, #1976D2);
}

.button-content {
    text-align: center;
}

.button-text {
    display: block;
    font-size: 1.5rem;
    font-weight: bold;
    margin-top: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-buttons {
        gap: 1rem;
    }

    .menu-button {
        width: 250px;
        height: 250px;
    }

    .button-text {
        font-size: 1.2rem;
    }

    .logo {
        max-width: 150px;
    }
}

@media (max-width: 480px) {
    .menu-button {
        width: 200px;
        height: 200px;
    }

    .logo {
        max-width: 100px;
    }

    .logo-container {
        top: 10px;
        left: 10px;
    }
}

</style>