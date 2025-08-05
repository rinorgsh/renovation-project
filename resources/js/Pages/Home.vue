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
            <td>{{ devis.client.prenom }} {{ devis.client.nom }}</td>
            <td>{{ formatDate(devis.created_at) }}</td>
            <td>{{ formatPrice(devis.total_ttc) }}€</td>
            <td>
              <div class="status" :class="getStatusClass(devis)">
                {{ getStatusLabel(devis) }}
              </div>
            </td>
            <td>
              <button @click="viewDevis(devis.id)" class="action-btn"><i class="fas fa-eye"></i></button>
              <button @click="editDevis(devis.id)" class="action-btn"><i class="fas fa-edit"></i></button>
              <button @click="downloadPDF(devis.id)" class="action-btn"><i class="fas fa-download"></i></button>
              <button @click="sendPDFToClient(devis.id)" class="action-btn"><i class="fas fa-envelope"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        <div class="page-btn"><i class="fas fa-chevron-left"></i></div>
        <div class="page-btn active">1</div>
        <div class="page-btn">2</div>
        <div class="page-btn">3</div>
        <div class="page-btn"><i class="fas fa-chevron-right"></i></div>
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

const props = defineProps({
  devis: {
    type: Array,
    default: () => []
  },
  stats: {
    type: Object,
    default: () => ({})
  }
});

// Route pour la liste des devis
const devisListRoute = '/devis';

// Formater les statistiques pour l'affichage
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

// Calculs statistiques
const calculateTotalAmount = () => {
  return props.devis
    .reduce((total, devis) => total + Number(devis.total_ttc), 0);
};

const countSignedDocuments = () => {
  return props.devis.filter(devis => devis.signature_path).length;
};

const countPendingDocuments = () => {
  return props.devis.filter(devis => !devis.signature_path).length;
};

const calculateSignatureRate = () => {
  const signedCount = countSignedDocuments();
  return props.devis.length > 0 
    ? ((signedCount / props.devis.length) * 100).toFixed(1)
    : 0;
};

// PUIS utilisez ces fonctions pour calculer les stats
const stats = computed(() => [
  { 
    title: 'Total Devis', 
    value: props.devis.length, 
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

// Récupérer les 6 derniers devis pour l'affichage dans le tableau
const recentDevis = computed(() => {
  return [...props.devis]
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .slice(0, 6);
});

// Données pour le graphique linéaire (évolution des devis)
const revenueData = ref({
  labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
  datasets: [
    {
      label: 'Montant total (€)',
      data: generateMonthlyData(),
      borderColor: '#4a90e2',
      backgroundColor: 'rgba(74, 144, 226, 0.2)',
      tension: 0.4,
      fill: true
    }
  ]
});

// Données pour le graphique en anneau (répartition des statuts)
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

// Générer des données mensuelles pour le graphique
function generateMonthlyData() {
  // Grouper les devis par mois
  const monthlyData = Array(12).fill(0);
  
  props.devis.forEach(devis => {
    const date = new Date(devis.created_at);
    const month = date.getMonth();
    monthlyData[month] += Number(devis.total_ttc);
  });
  
  return monthlyData;
}
</script>


<style scoped>
.page-title {
  margin-bottom: 20px;
  font-size: 24px;
  font-weight: 500;
}

.stats-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.stat-card {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
}

.stat-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.stat-title {
  color: var(--light-text);
  font-size: 14px;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.bg-primary {
  background-color: var(--primary-color);
}

.bg-success {
  background-color: var(--accent-color);
}

.bg-warning {
  background-color: var(--warning-color);
}

.bg-danger {
  background-color: var(--danger-color);
}

.stat-value {
  font-size: 24px;
  font-weight: bold;
  margin: 5px 0;
}

.stat-change {
  font-size: 12px;
  display: flex;
  align-items: center;
}

.positive-change {
  color: var(--accent-color);
}

.negative-change {
  color: var(--danger-color);
}

.chart-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

@media (max-width: 1200px) {
  .chart-container {
    grid-template-columns: 1fr;
  }
}

.chart-card {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
  font-size: 16px;
  font-weight: 500;
}

.chart-actions {
  display: flex;
  gap: 10px;
}

.chart-btn {
  background: none;
  border: none;
  color: var(--light-text);
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
}

.chart-btn.active {
  color: var(--primary-color);
  font-weight: 500;
}

.chart-btn:hover {
  color: var(--primary-color);
}

.table-container {
  background-color: var(--card-bg);
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.table-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 15px;
}

.data-table {
  width: 100%;
  border-collapse: collapse;
}

.data-table th,
.data-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}

.data-table th {
  font-weight: 500;
  color: var(--light-text);
}

.data-table tbody tr:hover {
  background-color: rgba(0, 0, 0, 0.01);
}

.status {
  padding: 5px 10px;
  border-radius: 15px;
  font-size: 12px;
  font-weight: 500;
  display: inline-block;
}

.status-completed {
  background-color: rgba(66, 185, 131, 0.1);
  color: var(--accent-color);
}

.status-pending {
  background-color: rgba(255, 152, 0, 0.1);
  color: var(--warning-color);
}

.status-cancelled {
  background-color: rgba(244, 67, 54, 0.1);
  color: var(--danger-color);
}

.action-btn {
  background: none;
  border: none;
  color: var(--light-text);
  cursor: pointer;
  margin-right: 5px;
}

.action-btn:hover {
  color: var(--primary-color);
}

.pagination {
  display: flex;
  justify-content: flex-end;
  margin-top: 15px;
  gap: 5px;
}

.page-btn {
  width: 30px;
  height: 30px;
  border: 1px solid var(--border-color);
  background: var(--card-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 4px;
}

.page-btn.active {
  background-color: var(--primary-color);
  color: white;
  border-color: var(--primary-color);
}
</style>