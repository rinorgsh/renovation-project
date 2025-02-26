<template>
    <Layout currentPage="clients">
      <div class="clients-container">
        <div class="page-header">
          <h1>Clients</h1>
          <button @click="openNewClientModal" class="btn-primary">
            Nouveau Client
          </button>
        </div>
  
        <div class="search-filter">
          <input 
            type="text" 
            v-model="searchTerm" 
            placeholder="Rechercher un client"
            class="search-input"
          />
        </div>
  
        <div class="clients-table-container">
          <table class="clients-table">
            <thead>
              <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Ville</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in filteredClients" :key="client.id">
                <td>
                  <div class="client-name">
                    {{ client.prenom }} {{ client.nom }}
                  </div>
                </td>
                <td>{{ client.email }}</td>
                <td>{{ client.telephone }}</td>
                <td>{{ client.ville }}</td>
                <td>
                  <div class="action-buttons">
                    <button 
                      @click="viewClientDetails(client.id)" 
                      class="btn-view"
                    >
                      Voir
                    </button>
                    <button 
                      @click="editClient(client.id)" 
                      class="btn-edit"
                    >
                      Modifier
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  
        <div v-if="filteredClients.length === 0" class="no-results">
          Aucun client trouvé
        </div>
  
        <div class="pagination">
          <button 
            @click="prevPage" 
            :disabled="currentPage === 1"
          >
            Précédent
          </button>
          <span>Page {{ currentPage }} sur {{ totalPages }}</span>
          <button 
            @click="nextPage" 
            :disabled="currentPage === totalPages"
          >
            Suivant
          </button>
        </div>
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  import Layout from '@/Components/TopNav.vue';
  
  const props = defineProps({
    clients: {
      type: Array,
      required: true
    }
  });
  
  // Search functionality
  const searchTerm = ref('');
  const filteredClients = computed(() => {
    if (!searchTerm.value) return props.clients;
    
    return props.clients.filter(client => 
      client.nom.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      client.prenom.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      client.email.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
  });
  
  // Pagination
  const currentPage = ref(1);
  const itemsPerPage = 10;
  
  const totalPages = computed(() => {
    return Math.ceil(filteredClients.value.length / itemsPerPage);
  });
  
  const paginatedClients = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredClients.value.slice(start, end);
  });
  
  const nextPage = () => {
    if (currentPage.value < totalPages.value) {
      currentPage.value++;
    }
  };
  
  const prevPage = () => {
    if (currentPage.value > 1) {
      currentPage.value--;
    }
  };
  
  // Actions
  const viewClientDetails = (clientId) => {
    router.get(`/clients/${clientId}`);
  };
  
  const editClient = (clientId) => {
    router.get(`/clients/${clientId}/edit`);
  };
  
  const openNewClientModal = () => {
    router.get('/clients/create');
  };
  </script>
  
  <style scoped>
  .clients-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .page-header h1 {
    font-size: 24px;
    color: #333;
  }
  
  .search-filter {
    margin-bottom: 20px;
  }
  
  .search-input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .clients-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .clients-table th,
  .clients-table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
  }
  
  .clients-table th {
    background-color: #f4f4f4;
    font-weight: bold;
  }
  
  .action-buttons {
    display: flex;
    gap: 10px;
  }
  
  .btn-primary,
  .btn-view,
  .btn-edit {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  
  .btn-primary {
    background-color: #4CAF50;
    color: white;
  }
  
  .btn-view {
    background-color: #2196F3;
    color: white;
  }
  
  .btn-edit {
    background-color: #FFC107;
    color: white;
  }
  
  .no-results {
    text-align: center;
    padding: 20px;
    color: #888;
  }
  
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 20px;
  }
  
  .pagination button {
    padding: 8px 16px;
    border: 1px solid #ddd;
    background-color: #f4f4f4;
    cursor: pointer;
  }
  
  .pagination button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }
  </style>