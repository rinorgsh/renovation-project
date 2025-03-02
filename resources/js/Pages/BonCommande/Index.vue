<template>
    <Layout currentPage="bon-commande">
      <div class="bon-commande-wrapper">
        <div class="header-section">
          <h1 class="page-title">Bons de Commande</h1>
          <div class="header-actions">
            <button class="create-btn">
              <i class="fas fa-plus"></i>
              Nouveau Bon
            </button>
          </div>
        </div>
  
        <!-- Stats Grid -->
        <div class="stats-grid">
          <div class="stat-card stat-total">
            <div class="stat-icon">
              <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-content">
              <h3>Total Bons</h3>
              <div class="stat-value">{{ bonsCommande.length }}</div>
              <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                +12.5% ce mois
              </div>
            </div>
          </div>
  
          <div class="stat-card stat-revenue">
            <div class="stat-icon">
              <i class="fas fa-euro-sign"></i>
            </div>
            <div class="stat-content">
              <h3>Montant Total</h3>
              <div class="stat-value">{{ calculateTotalAmount() }}€</div>
              <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i>
                +8.2% ce mois
              </div>
            </div>
          </div>
  
          <div class="stat-card stat-signed">
            <div class="stat-icon">
              <i class="fas fa-signature"></i>
            </div>
            <div class="stat-content">
              <h3>Bons Signés</h3>
              <div class="stat-value">{{ countSignedDocuments() }}</div>
              <div class="stat-trend positive">
                <i class="fas fa-check"></i>
                {{ calculateSignatureRate() }}% signés
              </div>
            </div>
          </div>
  
          <div class="stat-card stat-pending">
            <div class="stat-icon">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
              <h3>En Attente</h3>
              <div class="stat-value">{{ countPendingDocuments() }}</div>
              <div class="stat-trend negative">
                <i class="fas fa-arrow-down"></i>
                {{ calculatePendingRate() }}% en attente
              </div>
            </div>
          </div>
        </div>
  
        <!-- Table Section -->
        <div class="table-section">
          <div class="table-header">
            <h2>Liste des Bons de Commande</h2>
            
          </div>
  
          <div class="table-container">
            <table class="bon-commande-table">
              <thead>
                <tr>
                  <th>N° Devis</th>
                  <th>Client</th>
                  <th>Date</th>
                  <th>Montant</th>
                  <th>Statut</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="bon in bonsCommande" :key="bon.id">
                  <td>#{{ bon.numero_devis }}</td>
                  <td>
                    <div class="client-cell">
                      <div class="client-initials">
                        {{ getClientInitials(bon.client) }}
                      </div>
                      <div class="client-details">
                        {{ bon.client.prenom }} {{ bon.client.nom }}
                      </div>
                    </div>
                  </td>
                  <td>{{ formatDate(bon.created_at) }}</td>
                  <td class="amount-cell">{{ formatPrice(bon.total_ttc) }}€</td>
                  <td>
                    <div class="status-container">
                        <!-- Badge pour l'état de signature -->
                        <span class="status-badge" :class="bon.signature_url ? 'status-signed' : 'status-not-signed'">
                        {{ bon.signature_url ? 'Signé' : 'Non signé' }}
                        </span>
                        
                        <!-- Badge pour le statut de paiement -->
                        <span class="status-badge payment-status" :class="getPaymentStatusClass(bon.statut)">
                        {{ getPaymentStatusLabel(bon.statut) }}
                        </span>
                    </div>
                    </td>
                  <td>
                    <div class="action-cell">
                        
                      <button 
                        class="action-btn view-btn" 
                        @click="viewBonCommande(bon.id)"
                      >
                        <i class="fas fa-eye"></i>
                      </button>
                      <button 
                        class="action-btn download-btn" 
                        @click="downloadPDF(bon.id)"
                      >
                        <i class="fas fa-download"></i>
                      </button>
                      <button 
                        class="action-btn invoice-btn" 
                        @click="downloadInvoice(bon.id)"
                        title="Télécharger la facture"
                        >
                        <i class="fas fa-file-invoice-dollar"></i>
                        </button>
                      <button 
                        class="action-btn email-btn" 
                        @click="sendPDFToClient(bon.id)"
                        >
                        <i class="fas fa-envelope"></i>
                    </button>
                    <button class="action-btn edit-btn" @click="editDevis(bon.id)">
                    <i class="fas fa-pen"></i>
                    </button>
                    <div class="status-dropdown">
                    <button class="action-btn status-btn" @click="toggleStatusMenu(bon.id)">
                    <i class="fas fa-dollar-sign"></i>
                    </button>
                    <div v-if="activeStatusMenu === bon.id" class="status-menu">
                    <button 
                        class="status-option" 
                        :class="{ active: bon.statut === 'en_attente' }"
                        @click="updateStatus(bon.id, 'en_attente')"
                    >
                        <i class="fas fa-clock"></i> En attente
                    </button>
                    <button 
                        class="status-option"
                        :class="{ active: bon.statut === 'acompte' }"
                        @click="updateStatus(bon.id, 'acompte')"
                    >
                        <i class="fas fa-money-bill-wave"></i> Acompte versé
                    </button>
                    <button 
                        class="status-option"
                        :class="{ active: bon.statut === 'paye' }"
                        @click="updateStatus(bon.id, 'paye')"
                    >
                        <i class="fas fa-check-circle"></i> Payé
                    </button>
                    </div>
                </div>

                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
  
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
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue';
  import { router } from '@inertiajs/vue3';
  import Layout from '@/Components/TopNav.vue';


  const props = defineProps({
    bonsCommande: {
      type: Array,
      required: true
    }
  });
  const activeStatusMenu = ref(null);
  const toggleStatusMenu = (id) => {
  if (activeStatusMenu.value === id) {
    activeStatusMenu.value = null;
  } else {
    activeStatusMenu.value = id;
  }
};

// Fermer le menu de statut lors d'un clic ailleurs
const closeStatusMenus = (e) => {
  if (!e.target.closest('.status-dropdown')) {
    activeStatusMenu.value = null;
  }
};

// Mettre à jour le statut
const updateStatus = (id, status) => {
  router.post(`/devis/${id}/update-status`, {
    status: status
  }, {
    onSuccess: () => {
      activeStatusMenu.value = null;
    }
  });
};
// Ajouter l'écouteur d'événement pour fermer le menu
onMounted(() => {
  document.addEventListener('click', closeStatusMenus);
});

onUnmounted(() => {
  document.removeEventListener('click', closeStatusMenus);
});
  // Utilitaires
  const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };
  
  const editDevis = (id) => {
  router.get(`/devis/${id}/edit`);
};
  const formatPrice = (price) => {
    return Number(price).toFixed(2);
  };
  
  const getClientInitials = (client) => {
    return `${client.prenom[0]}${client.nom[0]}`.toUpperCase();
  };
  
  // Calculs statistiques
  const calculateTotalAmount = () => {
    return props.bonsCommande
      .reduce((total, bon) => total + Number(bon.total_ttc), 0)
      .toFixed(2);
  };
  
  const countSignedDocuments = () => {
    return props.bonsCommande.filter(bon => bon.signature_url).length;
  };
  
  const countPendingDocuments = () => {
    return props.bonsCommande.filter(bon => !bon.signature_url).length;
  };
  
  const calculateSignatureRate = () => {
    const signedCount = countSignedDocuments();
    return props.bonsCommande.length > 0 
      ? ((signedCount / props.bonsCommande.length) * 100).toFixed(1)
      : 0;
  };
  
  const calculatePendingRate = () => {
    const pendingCount = countPendingDocuments();
    return props.bonsCommande.length > 0 
      ? ((pendingCount / props.bonsCommande.length) * 100).toFixed(1)
      : 0;
  };
  const getPaymentStatusClass = (statut) => {
  switch(statut) {
    case 'en_attente': return 'status-waiting';
    case 'acompte': return 'status-advance';
    case 'paye': return 'status-paid';
    default: return 'status-waiting';
  }
};

// Fonction pour obtenir le libellé selon le statut de paiement
const getPaymentStatusLabel = (statut) => {
  switch(statut) {
    case 'en_attente': return 'En attente';
    case 'acompte': return 'Acompte versé';
    case 'paye': return 'Payé';
    default: return 'En attente';
  }
};
  // Helpers pour le statut
  const getStatusClass = (bon) => {
  // Classes pour le statut de la DB
  const dbStatusClass = bon.statut === 'en_attente' ? 'status-waiting' : 
                        bon.statut === 'annule' ? 'status-cancelled' :
                        'status-pending';
  
  // Classes pour la signature
  const signatureClass = bon.signature_url ? 'status-signed' : 'status-not-signed';
  
  // Retourner les deux classes
  return `${signatureClass} ${dbStatusClass}`;
};
  
const getStatusLabel = (bon) => {
  // Label de signature
  const signatureStatus = bon.signature_url ? 'Signé' : 'Non signé';
  
  // Label du statut depuis la DB
  let dbStatus = '';
  if (bon.statut === 'en_attente') dbStatus = 'En attente';
  else if (bon.statut === 'annule') dbStatus = 'Annulé';
  else if (bon.statut === 'confirme') dbStatus = 'Confirmé';
  // Ajouter d'autres cas selon vos statuts dans la DB
  
  // Retourner les deux informations, si un statut DB est disponible
  return dbStatus ? `${signatureStatus} - ${dbStatus}` : signatureStatus;
};
  
  // Actions
  const viewBonCommande = (id) => {
    router.get(`/devis/${id}`);
  };
  
  const downloadPDF = (id) => {
    window.location.href = `/devis/${id}/download`;

  };
  const downloadInvoice = (id) => {
  window.location.href = `/devis/${id}/download-invoice`;
};

  const sendPDFToClient = (id) => {
    router.post(`/devis/${id}/send-pdf`);
    };

  </script>
  
  <style scoped>
  .bon-commande-wrapper {
    background-color: #f4f6f9;
    padding: 2rem;
    border-radius: 12px;
  }
  
  .header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .page-title {
    font-size: 2rem;
    color: #2c3e50;
    font-weight: 600;
  }
  
  .create-btn {
    background: linear-gradient(to right, #4a90e2, #50c878);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
  }
  
  .create-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 8px rgba(0,0,0,0.15);
  }
  
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
  }
  
  .stat-card:hover {
    transform: translateY(-5px);
  }
  
  .stat-icon {
    background: linear-gradient(135deg, #4a90e2, #50c878);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
  }
  
  .stat-content {
    flex-grow: 1;
  }
  
  .stat-content h3 {
    font-size: 0.9rem;
    color: #7a8ba0;
    margin-bottom: 0.5rem;
  }
  
  .stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
  }
  
  .stat-trend {
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.3rem;
  }
  
  .stat-trend.positive {
    color: #2ecc71;
  }
  
  .stat-trend.negative {
    color: #e74c3c;
  }
  
  .table-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }
  
  .table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .table-header h2 {
    font-size: 1.3rem;
    color: #2c3e50;
  }
  
  .table-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  .invoice-btn:hover {
  background-color: #e74c3c; /* Rouge pour la facture */
  border-color: #e74c3c;
}
  .search-input {
    padding: 0.5rem 1rem;
    border: 1px solid #e0e6ed;
    border-radius: 8px;
    font-size: 0.9rem;
  }
  
  .filter-btn, .export-btn {
    background: none;
    border: 1px solid #e0e6ed;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4a5568;
    transition: all 0.3s ease;
  }
  
  .filter-btn:hover, .export-btn:hover {
    background-color: #f4f6f9;
    border-color: #4a90e2;
    color: #4a90e2;
  }
  
  .bon-commande-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 0.5rem;
  }
  
  .bon-commande-table thead th {
    background-color: #f4f6f9;
    color: #7a8ba0;
    padding: 1rem;
    text-align: left;
    font-weight: 500;
  }
  
  .bon-commande-table tbody tr {
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
  }
  
  .bon-commande-table tbody tr:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  
  .bon-commande-table td {
    padding: 1rem;
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
  
  .status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
  }
  
  /* Styles existants */
  .status-container {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

/* Styles pour les badges de signature (existants) */
.status-signed {
  background-color: rgba(46, 204, 113, 0.1);
  color: #2ecc71;
}

.status-not-signed {
  background-color: rgba(241, 196, 15, 0.1);
  color: #ffe063;
}

/* Styles pour les badges de statut de paiement */
.payment-status {
  font-size: 0.75rem;  /* Légèrement plus petit que le badge de signature */
}

.status-waiting {
  background-color: rgba(255, 179, 37, 0.823);
  color: #ffffff;
}

.status-advance {
  background-color: rgba(52, 152, 219, 0.1);
  color: #3498db;
}

.status-paid {
  background-color: rgba(46, 204, 113, 0.1);
  color: #2ecc71;
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
  width: 40px;
  height: 40px;
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

.download-btn:hover {
  background-color: #3498db;
  border-color: #3498db;
}
.email-btn:hover {
  background-color: #8e44ad;
  border-color: #8e44ad;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;
  margin-top: 1.5rem;
}

.page-btn {
  width: 40px;
  height: 40px;
  border: 1px solid #e0e6ed;
  background-color: white;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #4a5568;
  transition: all 0.3s ease;
}
.status-dropdown {
  position: relative;
  display: inline-block;
}

.status-btn {
  background-color: #f1c40f;
}

.status-btn:hover {
  background-color: #f39c12;
  color: white;
  border-color: #f39c12;
}

status-dropdown {
  position: relative;
  display: inline-block;
}

.status-menu {
  position: absolute;
  bottom: 100%; /* Placer le menu au-dessus du bouton */
  right: 0;
  margin-bottom: 5px; /* Petit espace entre le menu et le bouton */
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
  z-index: 1000;
  min-width: 180px;
  overflow: hidden;
  border: 1px solid #e2e8f0;
  opacity: 1;
  transform-origin: bottom right; /* Point d'origine de l'animation */
  animation: menuAppear 0.2s ease-out;
}

@keyframes menuAppear {
  from {
    opacity: 0;
    transform: translateY(10px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
.status-option {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  width: 100%;
  text-align: left;
  background-color: white; /* Préciser la couleur d'arrière-plan */
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
  color: #4a5568;
}

.status-option:hover {
  background-color: #f8fafc;
}

.status-option.active {
  font-weight: 600;
  background-color: #f1f5f9;
}

.status-option i {
  width: 16px;
  text-align: center;
}
.page-btn:hover {
  background-color: #4a90e2;
  color: white;
  border-color: #4a90e2;
}

.page-btn.active {
    background-color: #f39c12;
  color: white;
}
.edit-btn {
  background-color: #f39c12;
}

.edit-btn:hover {
  background-color: #e67e22;
}
/* Responsive Design */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .bon-commande-wrapper {
    padding: 1rem;
  }

  .header-section {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .table-actions {
    flex-direction: column;
    width: 100%;
  }

  .search-input {
    width: 100%;
  }

  .bon-commande-table {
    font-size: 0.9rem;
  }

  .bon-commande-table td {
    padding: 0.5rem;
  }
}
</style>