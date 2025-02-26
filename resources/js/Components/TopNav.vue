<template>
    <div class="app-container">
      <!-- Sidebar Flottant -->
      <div class="sidebar" :class="{ 'sidebar-collapsed': sidebarCollapsed }">
        <div class="logo-container">
          <div class="logo" v-if="!sidebarCollapsed">Admin Renowall</div>
          <button class="toggle-btn" @click="toggleSidebar">
            <i class="fas" :class="sidebarCollapsed ? 'fa-chevron-right' : 'fa-chevron-left'"></i>
          </button>
        </div>
        <div class="sidebar-menu">
          <a href="/home" class="menu-item" :class="{ active: currentPage === 'dashboard' }">
            <span class="menu-icon"><i class="fas fa-home"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed">Tableau de Bord</span>
          </a>
          <a href="/devis" class="menu-item" :class="{ active: currentPage === 'devis' }">
            <span class="menu-icon"><i class="fas fa-file-invoice"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed">Faire un devis</span>
          </a>
          <a href="/clients" class="menu-item" :class="{ active: currentPage === 'clients' }">
            <span class="menu-icon"><i class="fas fa-users"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed">Clients</span>
          </a>
          <a href="/bon-commande" class="menu-item" :class="{ active: currentPage === 'bon-commande' }">
            <span class="menu-icon"><i class="fas fa-file-alt"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed">Bon de commande</span>
          </a>
          <a href="/info-prime" class="menu-item" :class="{ active: currentPage === 'info-prime' }">
            <span class="menu-icon"><i class="fas fa-info-circle"></i></span>
            <span class="menu-text" v-if="!sidebarCollapsed">Info prime</span>
          </a>
        </div>
      </div>
  
      <!-- Contenu Principal avec Header flottant -->
      <div class="content-wrapper" :class="{ 'content-collapsed': sidebarCollapsed }">
        <!-- Header flottant -->
        <div class="header">
          <div class="search-bar">
            <input type="text" class="search-input" placeholder="Rechercher..." />
          </div>
          <div class="header-actions">
            
            <div class="user-profile" @click="toggleProfileMenu">
              <div class="avatar">{{ userInitials }}</div>
              <span v-if="!sidebarCollapsed">{{ userName }}</span>
              
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
          </div>
        </div>
  
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
  import { router } from '@inertiajs/vue3'; // Ajoutez cette ligne
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
      
      const toggleProfileMenu = () => {
        showProfileMenu.value = !showProfileMenu.value;
      };
      
      const closeProfileMenu = (e) => {
        const profileElement = document.querySelector('.user-profile');
        if (profileElement && !profileElement.contains(e.target)) {
          showProfileMenu.value = false;
        }
      };
      
      const handleLogout = () => {
        router.post('/logout');
        };
      
      onMounted(() => {
        document.addEventListener('click', closeProfileMenu);
      });
      
      onUnmounted(() => {
        document.removeEventListener('click', closeProfileMenu);
      });
      
      return {
        sidebarCollapsed,
        userName,
        userRole,
        userInitials,
        showProfileMenu,
        toggleSidebar,
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
  
  .app-container {
    display: flex;
    min-height: 100vh;
    padding: var(--main-padding);
    gap: var(--main-padding);
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
    width: 80px;
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
  }
  
  .content-collapsed {
    margin-left: 0;
  }
  
  /* Header flottant */
  .header {
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    height: var(--header-height);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    z-index: 99;
  }
  
  .search-bar {
    flex: 1;
    max-width: 400px;
  }
  
  .search-input {
    width: 100%;
    padding: 12px 18px;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    outline: none;
    transition: all 0.2s ease;
    background-color: #f5f7fa;
    font-size: 15px;
  }
  
  .search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
    background-color: white;
  }
  
  .header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
  }
  
  .header-icon {
    font-size: 20px;
    color: var(--light-text);
    cursor: pointer;
    position: relative;
    width: 42px;
    height: 42px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    transition: all 0.2s ease;
  }
  
  .header-icon:hover {
    background-color: #f5f7fa;
    color: var(--primary-color);
  }
  
  .notification-badge {
    position: absolute;
    top: 0px;
    right: 0px;
    background-color: var(--danger-color);
    color: white;
    border-radius: 50%;
    padding: 0 5px;
    font-size: 12px;
    min-width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .user-profile {
    display: flex;
    align-items: center;
    cursor: pointer;
    position: relative;
    padding: 5px 12px;
    border-radius: 15px;
    transition: all 0.2s ease;
  }
  
  .user-profile:hover {
    background-color: #f5f7fa;
  }
  
  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gradient-profile);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-right: 10px;
    font-size: 16px;
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
    }
    
    .sidebar {
      height: calc(100vh - 2rem);
    }
  }
  
  @media (max-width: 768px) {
    .app-container {
      flex-direction: column;
    }
    
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
      top: 0;
    }
    
    .sidebar-collapsed {
      width: 100%;
    }
    
    .content-wrapper, .content-collapsed {
      margin-left: 0;
    }
  }
  
  @media (max-width: 640px) {
    .header {
      flex-direction: column;
      height: auto;
      padding: 15px;
      gap: 15px;
    }
    
    .search-bar {
      max-width: 100%;
    }
  }
  </style>