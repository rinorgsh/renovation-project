<template>
    <div class="app-container">
      <!-- Bouton hamburger visible sur iPad et mobile -->
      <button class="hamburger-menu" @click="toggleMobileSidebar" aria-label="Menu">
        <i class="fas fa-bars"></i>
      </button>
      
      <!-- Overlay sombre quand le menu est ouvert sur mobile -->
      <div 
        v-if="mobileSidebarOpen" 
        class="sidebar-overlay" 
        @click="closeMobileSidebar"
      ></div>
  
      <!-- Sidebar Flottant avec classe mobile quand ouvert sur mobile -->
      <div 
        class="sidebar" 
        :class="{ 
          'sidebar-collapsed': sidebarCollapsed && !mobileSidebarOpen, 
          'sidebar-mobile-open': mobileSidebarOpen 
        }"
      >
        <div class="logo-container">
          <div class="logo" v-if="!sidebarCollapsed || mobileSidebarOpen">Admin Renowall</div>
          <button class="toggle-btn" @click="toggleSidebar">
            <i class="fas" :class="sidebarCollapsed && !mobileSidebarOpen ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
          </button>
          <!-- Bouton pour fermer le menu sur mobile -->
          <button class="close-mobile-menu" @click="closeMobileSidebar">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="sidebar-menu">
          <a href="/home" class="menu-item" :class="{ active: currentPage === 'dashboard' }">
            <span class="menu-icon"><i class="fas fa-home"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Tableau de Bord</span>
          </a>
          <a href="/devis" class="menu-item" :class="{ active: currentPage === 'devis' }">
            <span class="menu-icon"><i class="fas fa-file-invoice"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Faire un devis</span>
          </a>
          <!--
          <a href="/clients" class="menu-item" :class="{ active: currentPage === 'clients' }">
            <span class="menu-icon"><i class="fas fa-users"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Clients</span>
          </a>-->
          <a href="/bon-commande" class="menu-item" :class="{ active: currentPage === 'bon-commande' }">
            <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Bon de commande</span>
          </a>
          <a href="/produits" class="menu-item" :class="{ active: currentPage === 'produits' }">
            <span class="menu-icon"><i class="fas fa-boxes"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Gérer les produits</span>
          </a>
          <a href="/info-prime" class="menu-item" :class="{ active: currentPage === 'info-prime' }">
            <span class="menu-icon"><i class="fas fa-info-circle"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed || mobileSidebarOpen">Info prime</span>
          </a>
        </div>
      </div>
  
      <!-- Avatar flottant en haut à droite -->
      <div class="floating-profile" @click="toggleProfileMenu">
        <div class="avatar">{{ userInitials }}</div>
        
        <!-- Menu déroulant du profil -->
        <div v-show="showProfileMenu" class="profile-menu">
          <div class="profile-info">
            <div class="profile-circle large">
              {{ userInitials }}
            </div>
            <p class="profile-name">{{ userName }}</p>
            <p class="profile-role">{{ userRole }}</p>
          </div>
          <hr>
          <a href="/profil" class="menu-link">
            <i class="fas fa-user"></i>
            <span>Mon profil</span>
          </a>
          <a href="/parametres" class="menu-link">
            <i class="fas fa-cog"></i>
            <span>Paramètres</span>
          </a>
          <button @click="handleLogout" class="menu-link logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Déconnexion</span>
          </button>
        </div>
      </div>
  
      <!-- Contenu Principal sans Header -->
      <div class="content-wrapper" :class="{ 'content-collapsed': sidebarCollapsed && !mobileSidebarOpen }">
        <!-- Contenu principal -->
        <div class="main-content">
          <!-- Slot pour injecter le contenu -->
          <slot></slot>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted, onUnmounted } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { usePage } from '@inertiajs/vue3';
  
  export default {
    name: 'Layout',
    props: {
      currentPage: {
        type: String,
        default: 'dashboard'
      }
    },
    setup() {
      const sidebarCollapsed = ref(false);
      const mobileSidebarOpen = ref(false);
      const userName = computed(() => user.value ? user.value.name : 'Invité');
      const userRole = ref('Vendeur');
      const showProfileMenu = ref(false);
      
      // Calculer les initiales du nom d'utilisateur
      const userInitials = computed(() => {
        const names = userName.value.split(' ');
        if (names.length > 1) {
          return `${names[0][0]}${names[1][0]}`.toUpperCase();
        }
        return names[0][0].toUpperCase();
      });
  
      const page = usePage();
      const user = computed(() => page.props.auth.user);
  
      const toggleSidebar = () => {
        sidebarCollapsed.value = !sidebarCollapsed.value;
      };
      
      const toggleMobileSidebar = () => {
        mobileSidebarOpen.value = !mobileSidebarOpen.value;
        // Empêcher le défilement du body quand le menu est ouvert
        if (mobileSidebarOpen.value) {
          document.body.classList.add('no-scroll');
        } else {
          document.body.classList.remove('no-scroll');
        }
      };
      
      const closeMobileSidebar = () => {
        mobileSidebarOpen.value = false;
        document.body.classList.remove('no-scroll');
      };
      
      const toggleProfileMenu = () => {
        showProfileMenu.value = !showProfileMenu.value;
      };
      
      const closeProfileMenu = (e) => {
        const profileElement = document.querySelector('.floating-profile');
        if (profileElement && !profileElement.contains(e.target)) {
          showProfileMenu.value = false;
        }
      };
      
      const handleLogout = () => {
        router.post('/logout');
      };
      
      // Gérer les changements de taille d'écran
      const handleResize = () => {
        if (window.innerWidth > 1024 && mobileSidebarOpen.value) {
          closeMobileSidebar();
        }
      };
      
      onMounted(() => {
        document.addEventListener('click', closeProfileMenu);
        window.addEventListener('resize', handleResize);
      });
      
      onUnmounted(() => {
        document.removeEventListener('click', closeProfileMenu);
        window.removeEventListener('resize', handleResize);
      });
      
      return {
        sidebarCollapsed,
        mobileSidebarOpen,
        userName,
        userRole,
        userInitials,
        showProfileMenu,
        toggleSidebar,
        toggleMobileSidebar,
        closeMobileSidebar,
        toggleProfileMenu,
        handleLogout
      };
    }
  }
  </script>
  
  <style>
  :root {
    --primary-color: #1976d2;
    --secondary-color: #e0e0e0;
    --accent-color: #42b983;
    --danger-color: #f44336;
    --warning-color: #ff9800;
    --text-color: #333;
    --light-text: #757575;
    --border-color: #e0e0e0;
    --bg-color: #f8fafc;
    --card-bg: #ffffff;
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 80px;
    --header-height: 70px;
    --gradient-sidebar: linear-gradient(135deg, #2d65ff 0%, #2020da 100%);
    --gradient-profile: linear-gradient(135deg, #040083 0%, #6f6f96 100%);
    --main-padding: 1.5rem;
    --border-radius: 16px;
    --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  }
  
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  
  body {
    background-color: var(--bg-color);
    color: var(--text-color);
  }
  
  body.no-scroll {
    overflow: hidden;
  }
  
  .app-container {
    display: flex;
    min-height: 100vh;
    padding: var(--main-padding);
    gap: var(--main-padding);
    position: relative;
  }
  
  /* Hamburger Menu Button */
  .hamburger-menu {
    display: none;
    position: fixed;
    top: 1rem;
    left: 1rem;
    z-index: 1000;
    background: var(--gradient-sidebar);
    color: white;
    border: none;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
  }
  
  .hamburger-menu:hover {
    transform: scale(1.05);
  }
  
  .sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 90;
    animation: fadeIn 0.3s ease;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  /* Close mobile menu button */
  .close-mobile-menu {
    display: none;
    background: none;
    border: none;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 5px;
  }
  
  /* Sidebar flottant */
  .sidebar {
    width: var(--sidebar-width);
    background: var(--gradient-sidebar);
    border-radius: var(--border-radius);
    height: calc(100vh - 3rem);
    position: sticky;
    top: var(--main-padding);
    box-shadow: var(--shadow);
    z-index: 100;
    transition: all 0.3s ease;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  
  .sidebar-collapsed {
    width: var(--sidebar-collapsed-width);
  }
  
  .logo-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    height: var(--header-height);
  }
  
  .logo {
    font-size: 22px;
    font-weight: bold;
    color: white;
  }
  
  .toggle-btn {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 18px;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  
  .toggle-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
  }
  
  .sidebar-menu {
    padding: 20px 15px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-grow: 1;
    overflow-y: auto;
  }
  
  .menu-item {
    padding: 14px 18px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    text-decoration: none;
    border-radius: 12px;
  }
  
  .menu-item:hover {
    background-color: rgba(255, 255, 255, 0.1);
    transform: translateX(5px);
  }
  
  .menu-item.active {
    background-color: rgba(255, 255, 255, 0.2);
    font-weight: 600;
  }
  
  .menu-icon {
    margin-right: 15px;
    width: 22px;
    text-align: center;
    font-size: 18px;
  }
  
  .menu-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 15px;
  }
  
  /* Content wrapper */
  .content-wrapper {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: var(--main-padding);
    margin-left: 0;
    transition: all 0.3s ease;
    margin-top: 20px; /* Ajouter un peu d'espace en haut */
  }
  
  .content-collapsed {
    margin-left: 0;
  }
  
  /* Supprimer le header blanc */
  .header {
    display: none; /* Cache complètement le header */
  }
  
  /* Avatar flottant en haut à droite */
  .floating-profile {
    position: fixed;
    top: 1.5rem;
    right: 1.5rem;
    z-index: 1000;
    cursor: pointer;
  }
  
  .floating-profile .avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--gradient-profile);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease;
  }
  
  .floating-profile .avatar:hover {
    transform: scale(1.05);
  }
  
  /* Main content area */
  .main-content {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    padding: 25px;
    box-shadow: var(--shadow);
    overflow-y: auto;
    flex: 1;
  }
  
  /* Styles pour le menu de profil */
  .profile-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 260px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
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
    padding: 1.8rem;
    text-align: center;
    background: linear-gradient(to bottom, #f8fafc, white);
  }
  
  .profile-circle {
    width: 55px;
    height: 55px;
    background: var(--gradient-profile);
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
    width: 70px;
    height: 70px;
    font-size: 1.6rem;
    margin: 0 auto 1.2rem;
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
    padding: 0.9rem 1.5rem;
    color: #1e293b;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    font-size: 1rem;
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
    width: 22px;
    text-align: center;
    font-size: 1.1rem;
  }
  
  hr {
    border: none;
    border-top: 1px solid #e2e8f0;
    margin: 0;
  }
  
  /* Responsive Design */
  @media (max-width: 1024px) {
    .app-container {
      padding: 1rem;
      gap: 1rem;
      padding-top: 80px;
    }
    
    .sidebar {
      height: calc(100vh - 2rem);
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 80%;
      max-width: 300px;
      border-radius: 0;
      transform: translateX(-100%);
    }
    
    .sidebar-mobile-open {
      transform: translateX(0);
    }
    
    .hamburger-menu {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .sidebar-overlay {
      display: block;
    }
    
    .close-mobile-menu {
      display: block;
      position: absolute;
      top: 20px;
      right: 20px;
    }
    
    .toggle-btn {
      display: none;
    }
    
    .content-wrapper {
      width: 100%;
      margin-left: 0 !important;
    }
    
    .floating-profile {
      top: 1rem;
      right: 1rem;
    }
    
    .floating-profile .avatar {
      width: 40px;
      height: 40px;
    }
  }
  
  @media (max-width: 768px) {
    .main-content {
      padding: 20px;
    }
  }
  
  @media (max-width: 640px) {
    .main-content {
      padding: 15px;
    }
    
    .floating-profile .profile-menu {
      right: -70px;
    }
  }
  
  @media (max-width: 480px) {
    .hamburger-menu {
      width: 40px;
      height: 40px;
      font-size: 1.2rem;
    }
    
    .floating-profile .profile-menu {
      width: 230px;
    }
    
    .sidebar {
      width: 85%;
    }
  }
  </style>