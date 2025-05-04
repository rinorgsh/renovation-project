<template>
    <Layout currentPage="dashboard">
      <h1 class="page-title">Tableau de Bord</h1>
      
      <!-- Stats Cards -->
      <div class="stats-container">
        <div class="stat-card" v-for="(stat, index) in stats" :key="index">
          <div class="stat-header">
            <div class="stat-title">{{ stat.title }}</div>
            <div class="stat-icon" :class="stat.bgClass">
              <i :class="stat.icon"></i>
            </div>
          </div>
          <div class="stat-value">{{ stat.value }}</div>
          <div class="stat-change" :class="stat.change > 0 ? 'positive-change' : 'negative-change'">
            <i class="fas" :class="stat.change > 0 ? 'fa-arrow-up' : 'fa-arrow-down'"></i>
            {{ Math.abs(stat.change) }}% depuis le mois dernier
          </div>
        </div>
      </div>
  
      <!-- Charts -->
      <div class="chart-container">
        <div class="chart-card">
          <div class="chart-header">
            <div class="chart-title">Évolution des Devis</div>
            <div class="chart-actions">
              <button class="chart-btn">Semaine</button>
              <button class="chart-btn">Mois</button>
              <button class="chart-btn active">Année</button>
            </div>
          </div>
          <div class="chart-content">
            <LineChart :data="revenueData" :options="lineChartOptions" />
          </div>
        </div>
        <div class="chart-card">
          <div class="chart-header">
            <div class="chart-title">Répartition des Devis</div>
            <div class="chart-actions">
              <button class="chart-btn">Exporter</button>
            </div>
          </div>
          <div class="chart-content">
            <DoughnutChart :data="statusData" :options="doughnutChartOptions" />
          </div>
        </div>
      </div>
  
      <!-- Table -->
      <div class="table-container">
        <div class="table-header">
          <div class="chart-title">Devis Récents</div>
          <div class="chart-actions">
            <a :href="devisListRoute" class="chart-btn">Voir tout</a>
          </div>
        </div>
        <table class="data-table">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Client</th>
              <th>Date</th>
              <th>Montant</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(devis, index) in recentDevis" :key="index">
              <td>{{ devis.numero_devis }}</td>
              <td>
                <div class="client-cell">
                  <div class="client-initials">
                    {{ getClientInitials(devis.client) }}
                  </div>
                  <div class="client-details">
                    {{ devis.client.prenom }} {{ devis.client.nom }}
                  </div>
                </div>
              </td>
              <td>{{ formatDate(devis.created_at) }}</td>
              <td class="amount-cell">{{ formatPrice(devis.total_ttc) }}€</td>
              <td>
                <div class="status" :class="getStatusClass(devis)">
                  {{ getStatusLabel(devis) }}
                </div>
              </td>
              <td>
                <div class="action-cell">
                  <button @click="viewDevis(devis.id)" class="action-btn view-btn" title="Voir">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button @click="editDevis(devis.id)" class="action-btn edit-btn" title="Modifier">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="downloadPDF(devis.id)" class="action-btn" title="Télécharger">
                    <i class="fas fa-download"></i>
                  </button>
                  <button @click="sendPDFToClient(devis.id)" class="action-btn" title="Envoyer par email">
                    <i class="fas fa-envelope"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="pagination">
          <button class="page-btn prev-btn">
            <i class="fas fa-chevron-left"></i>
          </button>
          <div class="page-numbers">
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
          </div>
          <button class="page-btn next-btn">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue';
  import { router } from '@inertiajs/vue3';
  import Layout from '@/Components/TopNav.vue';
  import { Line as LineChart, Doughnut as DoughnutChart } from 'vue-chartjs';
  import { Chart as ChartJS, ArcElement, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement, Title } from 'chart.js';
  
  // Enregistrer les composants Chart.js nécessaires
  ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, PointElement, LineElement, Title);
  
  // Route pour la liste des devis
  const devisListRoute = '/devis';
  
  // Données factices des devis pour démonstration
  const mockDevis = [
    {
      id: 1,
      numero_devis: 'BC0085',
      client: { prenom: 'Jean', nom: 'Dupont' },
      created_at: '2025-04-15',
      total_ttc: 2850.00,
      signature_path: 'path/to/signature.png'
    },
    {
      id: 2,
      numero_devis: 'BC0084',
      client: { prenom: 'Marie', nom: 'Lambert' },
      created_at: '2025-04-12',
      total_ttc: 1450.00,
      signature_path: null
    },
    {
      id: 3,
      numero_devis: 'BC0083',
      client: { prenom: 'Antoine', nom: 'Bernard' },
      created_at: '2025-04-10',
      total_ttc: 3975.00,
      signature_path: 'path/to/signature.png'
    },
    {
      id: 4,
      numero_devis: 'BC0082',
      client: { prenom: 'Claire', nom: 'Leroy' },
      created_at: '2025-04-08',
      total_ttc: 1280.00,
      signature_path: 'path/to/signature.png'
    },
    {
      id: 5,
      numero_devis: 'BC0081',
      client: { prenom: 'Thomas', nom: 'Dubois' },
      created_at: '2025-04-05',
      total_ttc: 4750.00,
      signature_path: null
    },
    {
      id: 6,
      numero_devis: 'BC0080',
      client: { prenom: 'Lucie', nom: 'Martin' },
      created_at: '2025-04-02',
      total_ttc: 2325.00,
      signature_path: 'path/to/signature.png'
    }
  ];
  
  // Utilitaires
  const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };
  
  const formatPrice = (price) => {
    return Number(price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
  };
  
  const getClientInitials = (client) => {
    return `${client.prenom[0]}${client.nom[0]}`.toUpperCase();
  };
  
  // Calculs statistiques
  const calculateTotalAmount = () => {
    return mockDevis.reduce((total, devis) => total + Number(devis.total_ttc), 0);
  };
  
  const countSignedDocuments = () => {
    return mockDevis.filter(devis => devis.signature_path).length;
  };
  
  const countPendingDocuments = () => {
    return mockDevis.filter(devis => !devis.signature_path).length;
  };
  
  // Statistiques
  const stats = computed(() => [
    { 
      title: 'Total Devis', 
      value: mockDevis.length, 
      change: 12.5,
      icon: 'fas fa-file-invoice', 
      bgClass: 'bg-primary' 
    },
    { 
      title: 'Montant Total', 
      value: formatPrice(calculateTotalAmount()) + '€', 
      change: 8.2, 
      icon: 'fas fa-euro-sign', 
      bgClass: 'bg-success' 
    },
    { 
      title: 'Devis Signés', 
      value: countSignedDocuments(), 
      change: 5.7,
  
      icon: 'fas fa-signature', 
      bgClass: 'bg-warning' 
    },
    { 
      title: 'En Attente', 
      value: countPendingDocuments(), 
      change: -3.8, 
      icon: 'fas fa-clock', 
      bgClass: 'bg-danger' 
    },
  ]);
  
  // Devis récents
  const recentDevis = computed(() => {
    return [...mockDevis].sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 6);
  });
  
  // Données pour le graphique linéaire
  const revenueData = ref({
    labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
    datasets: [
      {
        label: 'Montant total (€)',
        data: [15000, 12500, 18700, 22400, 19800, 25600, 30100, 28400, 33200, 35600, 34200, 38500],
        borderColor: '#4a90e2',
        backgroundColor: 'rgba(74, 144, 226, 0.2)',
        tension: 0.4,
        fill: true
      }
    ]
  });
  
  // Données pour le graphique en anneau
  const statusData = ref({
    labels: ['Signés', 'En attente'],
    datasets: [
      {
        data: [countSignedDocuments(), countPendingDocuments()],
        backgroundColor: ['#2ecc71', '#f1c40f'],
        borderWidth: 0
      }
    ]
  });
  
  // Options pour les graphiques
  const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      }
    },
    scales: {
      y: {
        beginAtZero: true
      }
    }
  };
  
  const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '70%'
  };
  
  // Helpers pour le statut
  const getStatusClass = (devis) => {
    if (devis.signature_path) return 'status-completed';
    return 'status-pending';
  };
  
  const getStatusLabel = (devis) => {
    return devis.signature_path ? 'Signé' : 'En attente';
  };
  
  // Actions
  const viewDevis = (id) => {
    router.get(`/devis/${id}`);
  };
  
  const editDevis = (id) => {
    router.get(`/devis/${id}/edit`);
  };
  
  const downloadPDF = (id) => {
    window.location.href = `/devis/${id}/download`;
  };
  
  const sendPDFToClient = (id) => {
    router.post(`/devis/${id}/send-pdf`);
  };
  </script>
  
  <style scoped>
  .page-title {
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: 600;
    color: #2c3e50;
  }
  
  .stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
  }
  
  .stat-card {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    transition: transform 0.3s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
  }
  
  .stat-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .stat-title {
    color: #7a8ba0;
    font-size: 14px;
    font-weight: 500;
  }
  
  .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
  }
  
  .bg-primary {
    background: linear-gradient(135deg, #4a90e2, #3498db);
  }
  
  .bg-success {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
  }
  
  .bg-warning {
    background: linear-gradient(135deg, #f1c40f, #f39c12);
  }
  
  .bg-danger {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
  }
  
  .stat-value {
    font-size: 28px;
    font-weight: 700;
    margin: 5px 0;
  }
  
  .stat-change {
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 5px;
  }
  
  .positive-change {
    color: #2ecc71;
  }
  
  .negative-change {
    color: #e74c3c;
  }
  
  .chart-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
  }
  
  @media (max-width: 1200px) {
    .chart-container {
      grid-template-columns: 1fr;
    }
  }
  
  .chart-card {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    height: 350px;
  }
  
  .chart-content {
    height: 280px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .chart-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
  }
  
  .chart-title {
    font-size: 18px;
    font-weight: 600;
  }
  
  .chart-actions {
    display: flex;
    gap: 12px;
  }
  
  .chart-btn {
    background: none;
    border: none;
    color: #7a8ba0;
    cursor: pointer;
    font-size: 14px;
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.2s ease;
    text-decoration: none;
  }
  
  .chart-btn.active {
    color: #4a90e2;
    font-weight: 500;
    background-color: rgba(74, 144, 226, 0.1);
  }
  
  .chart-btn:hover {
    color: #4a90e2;
    background-color: rgba(74, 144, 226, 0.05);
  }
  
  .table-container {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  }
  
  .table-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    align-items: center;
  }
  
  .data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 5px;
  }
  
  .data-table th,
  .data-table td {
    padding: 15px;
    text-align: left;
  }
  
  .data-table th {
    font-weight: 500;
    color: #7a8ba0;
    border-bottom: 1px solid #edf2f7;
  }
  
  .data-table tbody tr {
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  
  .data-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  }
  
  .client-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .client-initials {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #4a90e2, #50c878);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
  }
  
  .client-details {
    font-weight: 500;
    color: #2c3e50;
  }
  
  .amount-cell {
    font-weight: 600;
    color: #4a90e2;
  }
  
  .status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-block;
  }
  
  .status-completed {
    background-color: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
  }
  
  .status-pending {
    background-color: rgba(241, 196, 15, 0.1);
    color: #f1c40f;
  }
  
  .action-cell {
    display: flex;
    gap: 0.5rem;
  }
  
  .action-btn {
    background: none;
    border: 1px solid #e0e6ed;
    color: #4a5568;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
  }
  
  .action-btn:hover {
    background-color: #4a90e2;
    color: white;
    border-color: #4a90e2;
  }
  
  .view-btn:hover {
    background-color: #2ecc71;
    border-color: #2ecc71;
  }
  
  .edit-btn:hover {
    background-color: #f39c12;
    border-color: #f39c12;
  }
  
  .pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
  }
  
  .page-numbers {
    display: flex;
    gap: 0.5rem;
  }
  
  .page-btn {
    width: 36px;
    height: 36px;
    border: 1px solid #e0e6ed;
    background-color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4a5568;
    transition: all 0.3s ease;
    cursor: pointer;
  }
  
  .page-btn:hover {
    border-color: #4a90e2;
    color: #4a90e2;
  }
  
  .page-btn.active {
    background-color: #4a90e2;
    color: white;
    border-color: #4a90e2;
  }
  
  @media (max-width: 768px) {
    .stats-container {
      grid-template-columns: 1fr;
    }
    
    .chart-container {
      grid-template-columns: 1fr;
    }
    
    .data-table {
      display: block;
      overflow-x: auto;
    }
  }
  </style>