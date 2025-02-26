<template>
    <Layout currentPage="bon-commande">
      <div class="bon-commande-view">
        <!-- En-tête avec les actions -->
        <div class="header-section">
          <div class="back-nav">
            <Link href="/bons-commande" class="back-btn">
              <i class="fas fa-arrow-left"></i>
              Retour à la liste
            </Link>
            <h1 class="page-title">Bon de Commande #{{ devis.numero_devis }}</h1>
          </div>
          <div class="header-actions">
            <button class="action-btn download-btn" @click="downloadPDF">
              <i class="fas fa-download"></i>
              Télécharger
            </button>
            <button class="action-btn email-btn" @click="sendPDF">
              <i class="fas fa-envelope"></i>
              Envoyer par email
            </button>
          </div>
        </div>
  
        <!-- Carte d'information principale -->
        <div class="main-content">
          <!-- Information sur le statut -->
          <div class="status-card">
            <div class="status-header">
              <h2>Statut du Bon</h2>
              <span 
                class="status-badge" 
                :class="devis.signature_path ? 'status-signed' : 'status-pending'"
              >
                {{ devis.signature_path ? 'Signé' : 'En attente' }}
              </span>
            </div>
            <div class="status-grid">
              <div class="status-item">
                <div class="status-label">Date de création</div>
                <div class="status-value">{{ formatDate(devis.created_at) }}</div>
              </div>
              <div class="status-item">
                <div class="status-label">Valide jusqu'au</div>
                <div class="status-value">{{ formatDate(devis.date_validite) }}</div>
              </div>
              <div class="status-item">
                <div class="status-label">Montant total</div>
                <div class="status-value">{{ formatPrice(devis.total_ttc) }}€</div>
              </div>
              <div class="status-item">
                <div class="status-label">Taux TVA</div>
                <div class="status-value">{{ getTauxTVA() }}%</div>
              </div>
            </div>
          </div>
  
          <!-- Informations client -->
          <div class="client-section">
            <h2>Informations Client</h2>
            <div class="client-card">
              <div class="client-header">
                <div class="client-avatar">
                  {{ getClientInitials() }}
                </div>
                <div class="client-name">
                  {{ devis.client.prenom }} {{ devis.client.nom }}
                </div>
              </div>
              <div class="client-details">
                <div class="detail-item">
                  <i class="fas fa-envelope"></i>
                  <span>{{ devis.client.email }}</span>
                </div>
                <div class="detail-item">
                  <i class="fas fa-phone"></i>
                  <span>{{ devis.client.telephone }}</span>
                </div>
                <div class="detail-item">
                  <i class="fas fa-map-marker-alt"></i>
                  <span>
                    {{ devis.client.adresse }} {{ devis.client.numero_domicile }}, 
                    {{ devis.client.code_postal }} {{ devis.client.ville }}
                  </span>
                </div>
                <div v-if="devis.client.numero_tva" class="detail-item">
                  <i class="fas fa-receipt"></i>
                  <span>TVA: {{ devis.client.numero_tva }}</span>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Liste des produits -->
          <div class="products-section">
            <h2>Produits</h2>
            <div class="products-table-container">
              <table class="products-table">
                <thead>
                  <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-for="(produit, index) in devis.produits" :key="index">
                    <tr>
                      <td>
                        <div class="product-cell">
                          <div v-if="produit.image" class="product-image">
                            <img :src="getProductImageUrl(produit.image)" :alt="produit.nom">
                          </div>
                          <div class="product-info">
                            <div class="product-name">{{ produit.nom }}</div>
                            <div class="product-description">{{ produit.description }}</div>
                          </div>
                        </div>
                      </td>
                      <td>{{ produit.pivot.quantite }} {{ getUnitLabel(produit.type) }}</td>
                      <td>{{ formatPrice(produit.pivot.prix_unitaire) }}€</td>
                      <td>{{ formatPrice(produit.pivot.total_ligne) }}€</td>
                    </tr>
                    <tr v-if="produit.pivot.commentaire" class="comment-row">
                      <td colspan="4" class="product-comment">
                        <div class="comment-content">
                          <i class="fas fa-comment-alt"></i>
                          <span>{{ produit.pivot.commentaire }}</span>
                        </div>
                      </td>
                    </tr>
                  </template>
                </tbody>
                <tfoot>
                  <tr class="subtotal-row">
                    <td colspan="3" class="total-label">Total HT:</td>
                    <td class="total-value">{{ formatPrice(devis.total_ht) }}€</td>
                  </tr>
                  <tr class="tax-row">
                    <td colspan="3" class="total-label">TVA ({{ getTauxTVA() }}%):</td>
                    <td class="total-value">{{ formatPrice(devis.total_tva) }}€</td>
                  </tr>
                  <tr class="total-row">
                    <td colspan="3" class="total-label">Total TTC:</td>
                    <td class="total-value">{{ formatPrice(devis.total_ttc) }}€</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
  
          <!-- Signature -->
          <div v-if="devis.signature_path" class="signature-section">
            <h2>Signature</h2>
            <div class="signature-container">
              <div class="signature-image">
                <img :src="signature_url" alt="Signature du client">
              </div>
              <div class="signature-date">
                Signé le {{ formatDate(devis.signature_date) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { Link, router } from '@inertiajs/vue3';
  import Layout from '@/Components/TopNav.vue';
  
  const props = defineProps({
    devis: {
      type: Object,
      required: true
    },
    signature_url: {
      type: String,
      default: null
    }
  });
  
  // Fonctions utilitaires
  const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: '2-digit',
      year: 'numeric'
    });
  };
  
  const formatPrice = (price) => {
    return Number(price).toFixed(2);
  };
  
  const getClientInitials = () => {
    if (!props.devis.client) return 'NC';
    return `${props.devis.client.prenom[0]}${props.devis.client.nom[0]}`.toUpperCase();
  };
  
  const getUnitLabel = (type) => {
    switch (type) {
      case 'carre': return 'm²';
      case 'metre': return 'm';
      default: return 'unité(s)';
    }
  };
  
  const getTauxTVA = () => {
    // Si vous stockez le taux TVA dans le modèle devis, utilisez-le
    if (props.devis.taux_tva) return props.devis.taux_tva;
    
    // Sinon, calculez-le à partir du total HT et TVA
    if (props.devis.total_ht > 0) {
      return Math.round((props.devis.total_tva / props.devis.total_ht) * 100);
    }
    
    // Valeur par défaut
    return 21;
  };
  
  const getProductImageUrl = (imagePath) => {
    if (!imagePath) return '/images/default-product.jpg';
    
    // Si l'image est une URL complète
    if (imagePath.startsWith('http')) {
      return imagePath;
    }
    
    // Sinon construire le chemin
    return `/image/${imagePath}`;
  };
  
  // Actions
  const downloadPDF = () => {
    window.location.href = `/devis/${props.devis.id}/download`;
  };
  
  const sendPDF = () => {
    router.post(`/devis/${props.devis.id}/send-pdf`, {}, {
      onSuccess: () => {
        alert('Le PDF a été envoyé au client avec succès');
      }
    });
  };
  </script>
  
  <style scoped>
  .bon-commande-view {
    padding: 2rem;
    background-color: #f4f6f9;
    border-radius: 12px;
  }
  
  .header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
  }
  
  .back-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .back-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: #4a5568;
    padding: 0.5rem 1rem;
    background-color: #e2e8f0;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .back-btn:hover {
    background-color: #cbd5e0;
    transform: translateX(-3px);
  }
  
  .page-title {
    font-size: 1.8rem;
    color: #2c3e50;
    font-weight: 600;
  }
  
  .header-actions {
    display: flex;
    gap: 1rem;
  }
  
  .action-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  }
  
  .download-btn {
    background-color: #3498db;
  }
  
  .email-btn {
    background-color: #8e44ad;
  }
  
  .action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
  }
  
  /* Main content styles */
  .main-content {
    display: grid;
    gap: 2rem;
  }
  
  /* Status card */
  .status-card {
    background-color: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }
  
  .status-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
  }
  
  .status-header h2 {
    font-size: 1.3rem;
    color: #2c3e50;
    margin: 0;
  }
  
  .status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
  }
  
  .status-signed {
    background-color: rgba(46, 204, 113, 0.1);
    color: #2ecc71;
  }
  
  .status-pending {
    background-color: rgba(241, 196, 15, 0.1);
    color: #f1c40f;
  }
  
  .status-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
  }
  
  .status-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .status-label {
    font-size: 0.9rem;
    color: #718096;
  }
  
  .status-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
  }
  
  /* Client section */
  .client-section, .products-section, .signature-section {
    background-color: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  }
  
  .client-section h2, .products-section h2, .signature-section h2 {
    font-size: 1.3rem;
    color: #2c3e50;
    margin-top: 0;
    margin-bottom: 1.5rem;
  }
  
  .client-card {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }
  
  .client-header {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .client-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4a90e2, #50c878);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 600;
  }
  
  .client-name {
    font-size: 1.3rem;
    font-weight: 600;
    color: #2c3e50;
  }
  
  .client-details {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
  }
  
  .detail-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #4a5568;
  }
  
  .detail-item i {
    color: #718096;
    min-width: 16px;
  }
  
  /* Products table */
  .products-table-container {
    overflow-x: auto;
  }
  
  .products-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .products-table th {
    background-color: #f4f6f9;
    padding: 1rem;
    text-align: left;
    font-weight: 500;
    color: #718096;
  }
  
  .products-table td {
    padding: 1rem;
    border-top: 1px solid #e2e8f0;
  }
  
  .product-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
  }
  
  .product-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
  }
  
  .product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .product-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .product-name {
    font-weight: 500;
    color: #2c3e50;
  }
  
  .product-description {
    font-size: 0.9rem;
    color: #718096;
  }
  
  .comment-row {
    background-color: #f8fafc;
  }
  
  .product-comment {
    padding: 0.75rem 1rem;
    font-style: italic;
    color: #718096;
  }
  
  .comment-content {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
  }
  
  .comment-content i {
    color: #a0aec0;
    margin-top: 3px;
  }
  
  .subtotal-row, .tax-row {
    font-weight: 500;
  }
  
  .total-row {
    font-weight: 700;
    font-size: 1.1rem;
  }
  
  .total-label {
    text-align: right;
  }
  
  .total-value {
    text-align: right;
  }
  
  /* Signature section */
  .signature-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }
  
  .signature-image {
    max-width: 300px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    background-color: #f8fafc;
  }
  
  .signature-image img {
    width: 100%;
    height: auto;
  }
  
  .signature-date {
    font-style: italic;
    color: #718096;
  }
  
  /* Responsive design */
  @media (max-width: 768px) {
    .bon-commande-view {
      padding: 1rem;
    }
  
    .header-section {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
  
    .back-nav {
      width: 100%;
      justify-content: space-between;
    }
  
    .header-actions {
      width: 100%;
    }
  
    .action-btn {
      flex: 1;
      justify-content: center;
    }
  
    .status-grid {
      grid-template-columns: 1fr 1fr;
    }
  
    .client-details {
      grid-template-columns: 1fr;
    }
  }
  </style>