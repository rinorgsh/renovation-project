<template>
  <Layout currentPage="bon-commande">
    <div class="bon-commande-wrapper">
      <div class="header-section">
        <h1 class="page-title">Bons de Commande</h1>
        <div class="header-actions">
          <button class="create-btn">
            <i class="fas fa-plus"></i>
            <span class="btn-text">Nouveau Bon</span>
          </button>
        </div>
      </div>

      <!-- Stats Grid - Visible uniquement sur desktop -->
      <div class="stats-grid desktop-only">
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
          <h2>Liste des Bons</h2>
        </div>

        <!-- Version desktop du tableau -->
        <div class="table-container desktop-only">
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
                      title="Voir"
                    >
                      <i class="fas fa-eye"></i>
                    </button>
                    
                    <button 
                      class="action-btn edit-btn" 
                      @click="editDevis(bon.id)"
                      title="Modifier"
                    >
                      <i class="fas fa-pen"></i>
                    </button>

                    <button 
                      class="action-btn more-btn" 
                      @click="openActionsModal(bon.id)"
                      title="Plus d'actions"
                    >
                      <i class="fas fa-ellipsis-v"></i>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Version mobile - cards au lieu de tableau -->
        <div class="mobile-cards mobile-only">
          <div v-for="bon in bonsCommande" :key="bon.id" class="mobile-card">
            <div class="mobile-card-header">
              <div class="mobile-card-title">
                <span class="mobile-card-id">#{{ bon.numero_devis }}</span>
                <span class="mobile-card-date">{{ formatDate(bon.created_at) }}</span>
              </div>
              <div class="mobile-card-amount">{{ formatPrice(bon.total_ttc) }}€</div>
            </div>
            
            <div class="mobile-card-client">
              <div class="client-initials">
                {{ getClientInitials(bon.client) }}
              </div>
              <div class="client-details">
                {{ bon.client.prenom }} {{ bon.client.nom }}
              </div>
            </div>
            
            <div class="mobile-card-footer">
              <div class="mobile-status-badges">
                <span class="status-badge" :class="bon.signature_url ? 'status-signed' : 'status-not-signed'">
                  {{ bon.signature_url ? 'Signé' : 'Non signé' }}
                </span>
                <span class="status-badge payment-status" :class="getPaymentStatusClass(bon.statut)">
                  {{ getPaymentStatusLabel(bon.statut) }}
                </span>
              </div>
              
              <div class="mobile-actions">
                <button 
                  class="action-btn view-btn" 
                  @click="viewBonCommande(bon.id)"
                >
                  <i class="fas fa-eye"></i>
                </button>
                
                <button 
                  class="action-btn edit-btn" 
                  @click="editDevis(bon.id)"
                >
                  <i class="fas fa-pen"></i>
                </button>

                <button 
                  class="action-btn more-btn" 
                  @click="openActionsModal(bon.id)"
                >
                  <i class="fas fa-ellipsis-v"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination - simplifié pour mobile -->
        <div class="pagination">
          <button class="page-btn prev-btn">
            <i class="fas fa-chevron-left"></i>
          </button>
          <div class="page-numbers">
            <button class="page-btn active">1</button>
            <button class="page-btn desktop-only">2</button>
            <button class="page-btn desktop-only">3</button>
          </div>
          <button class="page-btn next-btn">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Modal d'actions (en dehors du flux normal du document) -->
    <div v-if="activeActionsModal !== null" class="actions-modal-overlay" @click="closeActionsModal">
      <div class="actions-modal" @click.stop>
        <div class="actions-modal-header">
          <h3>Actions</h3>
          <button class="close-modal-btn" @click="closeActionsModal">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="actions-modal-body">
          <!-- Documents FR -->
          <div class="actions-section">
            <h4 class="actions-section-title">Documents FR</h4>
            <div class="actions-buttons">
              <button class="action-modal-btn" @click="downloadPDF(activeActionsModal); closeActionsModal()">
                <i class="fas fa-download"></i> Bon de commande
              </button>
              <button class="action-modal-btn" @click="downloadInvoice(activeActionsModal); closeActionsModal()">
                <i class="fas fa-file-invoice-dollar"></i> Facture
              </button>
            </div>
          </div>
          
          <!-- Documents NL -->
          <div class="actions-section">
            <h4 class="actions-section-title">Documents NL</h4>
            <div class="actions-buttons">
              <button class="action-modal-btn" @click="downloadDutchPDF(activeActionsModal); closeActionsModal()">
                <i class="fas fa-download"></i> Bestelbon
              </button>
              <button class="action-modal-btn" @click="downloadDutchInvoice(activeActionsModal); closeActionsModal()">
                <i class="fas fa-file-invoice-dollar"></i> Factuur
              </button>
            </div>
          </div>
          
          <!-- Communication -->
          <div class="actions-section">
            <h4 class="actions-section-title">Communication</h4>
            <button class="action-modal-btn" @click="sendPDFToClient(activeActionsModal); closeActionsModal()">
              <i class="fas fa-envelope"></i> Envoyer par email
            </button>
          </div>
          
          <!-- Statut de paiement -->
          <div class="actions-section">
            <h4 class="actions-section-title">Statut de paiement</h4>
            <div class="actions-status-buttons">
              <button 
                class="action-status-btn" 
                :class="{ active: getBonById(activeActionsModal)?.statut === 'en_attente' }"
                @click="updateStatus(activeActionsModal, 'en_attente'); closeActionsModal()"
              >
                <i class="fas fa-clock"></i> En attente
              </button>
              <button 
                class="action-status-btn" 
                :class="{ active: getBonById(activeActionsModal)?.statut === 'acompte' }"
                @click="updateStatus(activeActionsModal, 'acompte'); closeActionsModal()"
              >
                <i class="fas fa-money-bill-wave"></i> Acompte versé
              </button>
              <button 
                class="action-status-btn" 
                :class="{ active: getBonById(activeActionsModal)?.statut === 'paye' }"
                @click="updateStatus(activeActionsModal, 'paye'); closeActionsModal()"
              >
                <i class="fas fa-check-circle"></i> Payé
              </button>
            </div>
          </div>
          
          <!-- Suppression -->
          <div class="actions-section danger-section">
            <h4 class="actions-section-title">Danger</h4>
            <button 
              class="action-modal-btn delete-btn" 
              @click="confirmDelete(activeActionsModal); closeActionsModal()"
            >
              <i class="fas fa-trash"></i> Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="showDeleteConfirm" class="delete-modal-overlay">
      <div class="delete-modal">
        <div class="delete-modal-header">
          <h3>Confirmation</h3>
        </div>
        <div class="delete-modal-body">
          <p>Êtes-vous sûr de vouloir supprimer ce bon de commande ? Cette action est irréversible.</p>
        </div>
        <div class="delete-modal-footer">
          <button class="btn-cancel" @click="cancelDelete">Annuler</button>
          <button class="btn-delete" @click="deleteDevis">Supprimer</button>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Layout from '@/Components/TopNav.vue';

const props = defineProps({
  bonsCommande: {
    type: Array,
    required: true
  }
});

const activeStatusMenu = ref(null);
const activeActionsModal = ref(null);
const showDeleteConfirm = ref(false);
const devisToDelete = ref(null);

// Fonction pour obtenir un bon par son ID
const getBonById = (id) => {
  return props.bonsCommande.find(bon => bon.id === id) || null;
};

// Ouvrir la modal d'actions
const openActionsModal = (id) => {
  activeActionsModal.value = id;
};

// Fermer la modal d'actions
const closeActionsModal = () => {
  activeActionsModal.value = null;
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

// Afficher la confirmation avant suppression
const confirmDelete = (id) => {
  devisToDelete.value = id;
  showDeleteConfirm.value = true;
};

// Exécuter la suppression
const deleteDevis = () => {
  if (!devisToDelete.value) return;
  
  router.delete(`/devis/${devisToDelete.value}`, {
    onSuccess: () => {
      showDeleteConfirm.value = false;
      devisToDelete.value = null;
    }
  });
};

// Annuler la suppression
const cancelDelete = () => {
  showDeleteConfirm.value = false;
  devisToDelete.value = null;
};

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

const downloadDutchPDF = (id) => {
  window.location.href = `/devis/${id}/download-dutch`;
};

const downloadDutchInvoice = (id) => {
  window.location.href = `/devis/${id}/download-dutch-invoice`;
};

// Détection de la taille d'écran
const isMobile = ref(false);

const checkScreenSize = () => {
  isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkScreenSize);
});
</script>

<style scoped>
.bon-commande-wrapper {
  background-color: #f4f6f9;
  padding: 2rem;
  border-radius: 12px;
}

/* Utilitaires pour affichage responsive */
.desktop-only {
  display: block;
}

.mobile-only {
  display: none;
}

/* Header */
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

/* Stats Grid */
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

/* Table Section */
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

/* Statut badges */
.status-container {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.status-signed {
  background-color: rgba(46, 204, 113, 0.1);
  color: #2ecc71;
}

.status-not-signed {
  background-color: rgba(241, 196, 15, 0.1);
  color: #ffe063;
}

.payment-status {
  font-size: 0.75rem;
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

/* Action buttons */
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

.edit-btn {
  background-color: #f39c12;
}

.edit-btn:hover {
  background-color: #e67e22;
}

.more-btn:hover {
  background-color: #7f8c8d;
  color: white;
  border-color: #7f8c8d;
}

/* Pagination */
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

.page-btn:hover {
  background-color: #4a90e2;
  color: white;
  border-color: #4a90e2;
}

.page-btn.active {
  background-color: #f39c12;
  color: white;
}

/* Modal d'actions */
.actions-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 2000;
}

.actions-modal {
  background-color: white;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  animation: modalAppear 0.3s ease-out;
}

.actions-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.actions-modal-header h3 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.1rem;
  font-weight: 600;
}

.close-modal-btn {
  background: none;
  border: none;
  color: #a0aec0;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 0.5rem;
  transition: color 0.2s ease;
}

.close-modal-btn:hover {
  color: #4a5568;
}

.actions-modal-body {
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.actions-section {
  margin-bottom: 1.5rem;
}

.actions-section:last-child {
  margin-bottom: 0;
}

.actions-section-title {
  font-size: 0.9rem;
  font-weight: 600;
  color: #4a5568;
  margin: 0 0 0.8rem 0;
}

.actions-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
}

.action-modal-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.7rem 1rem;
  background-color: #f7fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  color: #4a5568;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-modal-btn:hover {
  background-color: #edf2f7;
  border-color: #cbd5e0;
}

.action-modal-btn i {
  color: #4a90e2;
}

.actions-status-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.8rem;
}

.action-status-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.7rem 1rem;
  background-color: #f7fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  color: #4a5568;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-status-btn:hover {
  background-color: #edf2f7;
  border-color: #cbd5e0;
}

.action-status-btn.active {
  background-color: #ebf8ff;
  border-color: #4299e1;
  color: #2b6cb0;
  font-weight: 500;
}

.action-status-btn.active i {
  color: #3182ce;
}

.danger-section .actions-section-title {
  color: #e53e3e;
}

.delete-btn {
  background-color: #fff5f5;
  border-color: #fed7d7;
  color: #e53e3e;
}

.delete-btn:hover {
  background-color: #fed7d7;
  border-color: #fc8181;
}

.delete-btn i {
  color: #e53e3e !important;
}

/* Modal de confirmation de suppression */
.delete-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.delete-modal {
  background-color: white;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  overflow: hidden;
  animation: modalAppear 0.3s ease-out;
}

.delete-modal-header {
  padding: 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.delete-modal-header h3 {
  margin: 0;
  color: #2c3e50;
  font-size: 1.25rem;
}

.delete-modal-body {
  padding: 1.5rem;
}

.delete-modal-footer {
  padding: 1rem 1.5rem;
  background-color: #f8fafc;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.btn-cancel {
  padding: 0.5rem 1rem;
  border-radius: 8px;
  border: 1px solid #e0e6ed;
  background-color: white;
  color: #4a5568;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-cancel:hover {
  background-color: #f1f5f9;
}

.btn-delete {
  padding: 0.5rem 1rem;
  border-radius: 8px;
  border: none;
  background-color: #e74c3c;
  color: white;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-delete:hover {
  background-color: #c0392b;
}

/* Animation for modals */
@keyframes modalAppear {
  from {
    opacity: 0;
    transform: translateY(-50px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Version mobile des statistiques */
.mobile-stats {
  display: flex;
  justify-content: space-between;
  background-color: white;
  border-radius: 12px;
  padding: 1rem;
  margin-bottom: 1.5rem;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.mobile-stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 1rem;
  color: #2c3e50;
}

.mobile-stat-item i {
  font-size: 1.5rem;
  margin-bottom: 0.5rem;
  color: #4a90e2;
}

/* Cards pour mobile */
.mobile-cards {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.mobile-card {
  background-color: white;
  border-radius: 12px;
  padding: 1rem;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  transition: all 0.3s ease;
}

.mobile-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.mobile-card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.mobile-card-title {
  display: flex;
  flex-direction: column;
}

.mobile-card-id {
  font-weight: 600;
  color: #2c3e50;
  font-size: 1rem;
}

.mobile-card-date {
  font-size: 0.8rem;
  color: #7a8ba0;
}

.mobile-card-amount {
  font-weight: 700;
  color: #4a90e2;
  font-size: 1.2rem;
}

.mobile-card-client {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.75rem;
}

.mobile-card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 0.75rem;
}

.mobile-status-badges {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.mobile-actions {
  display: flex;
  gap: 0.5rem;
}

/* Media Queries pour le responsive */
@media (max-width: 1200px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  /* Bascule entre mobile et desktop */
  .desktop-only {
    display: none !important;
  }
  
  .mobile-only {
    display: block;
  }
  
  .mobile-actions {
    display: flex;
  }
  
  /* Ajustements pour mobile */
  .bon-commande-wrapper {
    padding: 1rem;
  }
  
  .header-section {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .page-title {
    font-size: 1.5rem;
  }
  
  .create-btn {
    width: 100%;
    justify-content: center;
  }
  
  .table-header h2 {
    font-size: 1.1rem;
  }
  
  .table-section {
    padding: 1rem;
  }
  
  /* Rendre les boutons d'action plus grands pour mobile */
  .action-btn {
    width: 44px;
    height: 44px;
  }
  
  /* Modal d'actions prend plus d'espace sur mobile */
  .actions-modal {
    max-width: 95%;
    max-height: 90vh;
  }
  
  .actions-modal-body {
    padding: 1rem;
  }
  
  /* Boutons plus grands pour meilleure expérience tactile */
  .action-modal-btn, 
  .action-status-btn {
    padding: 0.85rem 1rem;
    width: 100%;
  }
  
  .actions-buttons {
    flex-direction: column;
    width: 100%;
    gap: 0.5rem;
  }
  
  .actions-status-buttons {
    flex-direction: column;
    width: 100%;
    gap: 0.5rem;
  }
}

/* Très petits écrans */
@media (max-width: 375px) {
  .page-title {
    font-size: 1.2rem;
  }
  
  .mobile-card-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .mobile-card-amount {
    align-self: flex-end;
  }
}
</style>