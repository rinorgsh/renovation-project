<template>
    <div class="product-selection-container">
      <!-- Vue de sélection des produits -->
      <div v-if="!showSummaryOnly" class="selection-view">
        <h2 class="section-title">Sélection des produits</h2>
        
        <!-- Liste des produits en cours de sélection -->
        <div class="products-form">
          <div v-for="(line, index) in productLines" :key="index" class="product-card">
            <div class="card-header">
              <h3>Produit {{ index + 1 }}</h3>
              <button 
                @click="removeProductLine(index)" 
                type="button" 
                class="remove-product-btn" 
                :disabled="productLines.length <= 1" 
                title="Supprimer ce produit"
              >
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
            
            <!-- Sélection du produit avec autocomplétion -->
            <div class="form-group">
              <label :for="'produit-search-' + index" class="form-label">Sélectionner un produit <span class="required">*</span></label>
              <div class="autocomplete-container">
                <input 
                  type="text" 
                  :id="'produit-search-' + index"
                  v-model="line.searchQuery" 
                  @input="filterProducts(index)"
                  @focus="line.showDropdown = true"
                  @blur="handleBlur(index)"
                  class="autocomplete-input" 
                  placeholder="Rechercher ou sélectionner un produit..." 
                />
                <i class="search-icon fas fa-search"></i>
                
                <!-- Menu déroulant de suggestions -->
                <div 
                  v-if="line.showDropdown && line.filteredProducts.length > 0" 
                  class="autocomplete-dropdown"
                >
                  <div 
                    v-for="product in line.filteredProducts" 
                    :key="product.id"
                    @mousedown="selectProduct(index, product)"
                    class="autocomplete-item"
                  >
                    <div class="product-suggestion">
                      <div class="product-suggestion-image" v-if="product.image">
                        <img :src="product.image" :alt="product.nom" />
                      </div>
                      <div class="product-suggestion-info">
                        <div class="product-suggestion-name">{{ product.nom }}</div>
                        <div class="product-suggestion-price">{{ product.prix }}€</div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <!-- Message si aucun résultat -->
                <div 
                  v-if="line.showDropdown && line.searchQuery && line.filteredProducts.length === 0" 
                  class="no-results"
                >
                  Aucun produit ne correspond à votre recherche
                </div>
              </div>
            </div>
    
            <!-- Détails du produit sélectionné -->
            <div v-if="line.selectedProduct" class="product-details">
              <div class="details-grid">
                <div class="product-image-container">
                  <img 
                    :src="line.selectedProduct.image"
                    :alt="line.selectedProduct.nom"
                    class="product-image"
                  />
                  <div class="product-name">{{ line.selectedProduct.nom }}</div>
                </div>
                
                <div class="product-specs">
                  <!-- Prix -->
                  <div class="form-group">
                    <label :for="'prix-' + index" class="form-label">Prix unitaire (€) <span class="required">*</span></label>
                    <input 
                      type="number" 
                      :id="'prix-' + index" 
                      v-model="line.prix"
                      class="form-input"
                      step="0.01"
                      min="0"
                      required
                    />
                  </div>
    
                  <!-- Mesure selon le type -->
                  <div class="form-group">
                    <label :for="'mesure-' + index" class="form-label">{{ getMesureLabel(line.selectedProduct.type) }} <span class="required">*</span></label>
                    <input 
                      type="number" 
                      :id="'mesure-' + index" 
                      v-model="line.mesure"
                      class="form-input"
                      step="0.01"
                      min="0"
                      required
                    />
                  </div>
    
                  <!-- Total de la ligne -->
                  <div class="form-group">
                    <label class="form-label">Total</label>
                    <div class="line-total">
                      {{ calculateLineTotal(line).toFixed(2) }}€
                    </div>
                  </div>
                  <div class="form-group">
                    <label :for="'commentaire-' + index" class="form-label">Commentaire</label>
                    <textarea 
                      :id="'commentaire-' + index" 
                      v-model="line.commentaire"
                      class="form-textarea"
                      placeholder="Ajouter un commentaire pour ce produit"
                      rows="2"
                    ></textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Bouton pour ajouter un produit -->
          <div class="add-product-container">
            <button 
              type="button"
              class="add-product-btn"
              @click="addProductLine"
            >
              <i class="fas fa-plus"></i>
              <span>Ajouter un produit</span>
            </button>
          </div>
          
          <!-- Bouton pour valider la sélection -->
          <div class="form-actions">
            <button 
              class="validate-btn"
              @click="validateAndShowSummary"
              :disabled="!isFormValid"
            >
              <i class="fas fa-check"></i>
              <span>Valider les produits</span>
            </button>
          </div>
        </div>
      </div>
  
      <!-- Vue récapitulative reste la même -->
      <div v-if="showSummaryOnly" class="summary-view">
        <h2 class="section-title">Récapitulatif des produits</h2>
        
        <div class="products-table">
          <div class="table-header">
            <div class="col">Produit</div>
            <div class="col">{{ selectedProducts[0]?.produit?.type ? getMesureLabel(selectedProducts[0].produit.type) : 'Quantité' }}</div>
            <div class="col">Prix unitaire</div>
            <div class="col">Total</div>
            <div class="col actions">Actions</div>
          </div>
          
          <template v-for="(item, index) in selectedProducts" :key="index">
            <div class="table-row">
              <div class="col product-name" data-label="Produit">{{ item.produit.nom }}</div>
              <div class="col" :data-label="getMesureLabel(item.produit.type)">{{ item.mesure }}</div>
              <div class="col" data-label="Prix unitaire">{{ item.prix }}€</div>
              <div class="col" data-label="Total">{{ calculateLineTotal(item).toFixed(2) }}€</div>
              <div class="col actions">
                <button 
                  @click="removeProduct(index)"
                  class="remove-btn"
                  :disabled="selectedProducts.length === 1"
                  title="Supprimer ce produit"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <div v-if="item.commentaire" class="table-row-comment">
              <div class="comment-label">Commentaire:</div>
              <div class="comment-text">{{ item.commentaire }}</div>
            </div>
          </template>
        </div>
    
        <!-- Total général -->
        <div class="totals-container">
          <div class="totals-card">
            <div class="total-row">
              <span>Total HT:</span>
              <span class="amount">{{ totalHT.toFixed(2) }}€</span>
            </div>
            <div class="total-row">
              <span>TVA ({{ tvaRate }}%):</span>
              <span class="amount">{{ totalTVA.toFixed(2) }}€</span>
            </div>
            <div class="total-row grand-total">
              <span>Total TTC:</span>
              <span class="amount">{{ totalTTC.toFixed(2) }}€</span>
            </div>
          </div>
        </div>
    
        <!-- Boutons d'action -->
        <div class="action-buttons">
          <button 
            @click="backToSelection"
            class="modify-btn"
          >
            <i class="fas fa-pen"></i>
            <span>Modifier les produits</span>
          </button>
          
          <button 
            @click="submitProductSelection"
            class="next-btn"
          >
            <span>Continuer</span>
            <i class="fas fa-arrow-right"></i>
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue';
  
  const props = defineProps({
    selectedProducts: {
      type: Array,
      required: true
    },
    produits: {
      type: Array,
      required: true 
    },
    tvaRate: {
      type: Number,
      default: 21
    }
  });
  
  const emit = defineEmits(['update-products', 'next-step']);
  
  // État pour gérer l'affichage
  const showSummaryOnly = ref(props.selectedProducts.length > 0);
  const selectedProducts = ref(props.selectedProducts || []);
  
  // Initialisation des lignes de produit avec recherche
  const initProductLine = () => ({
    selectedProduct: '',
    prix: 0,
    mesure: 0,
    commentaire: '',
    searchQuery: '',
    filteredProducts: [...props.produits],
    showDropdown: false
  });
  
  const productLines = ref([initProductLine()]);
  
  // Si des produits sont déjà sélectionnés, préparer les lignes
  if (props.selectedProducts.length > 0 && !showSummaryOnly.value) {
    productLines.value = props.selectedProducts.map(item => ({
      selectedProduct: item.produit,
      prix: item.prix,
      mesure: item.mesure,
      commentaire: item.commentaire || '',
      searchQuery: item.produit.nom,
      filteredProducts: [...props.produits],
      showDropdown: false
    }));
  }
  
  // Fonction pour filtrer les produits selon la recherche
  const filterProducts = (index) => {
    const line = productLines.value[index];
    
    if (!line.searchQuery.trim()) {
      line.filteredProducts = [...props.produits];
      return;
    }
    
    const query = line.searchQuery.toLowerCase().trim();
    line.filteredProducts = props.produits.filter(produit => 
      produit.nom.toLowerCase().includes(query)
    );
    
    // Limiter le nombre de résultats pour la performance
    if (line.filteredProducts.length > 10) {
      line.filteredProducts = line.filteredProducts.slice(0, 10);
    }
  };
  
  // Gérer la sélection d'un produit dans la liste déroulante
  const selectProduct = (index, product) => {
    const line = productLines.value[index];
    line.selectedProduct = product;
    line.prix = product.prix;
    line.mesure = 1;
    line.searchQuery = product.nom;
    line.showDropdown = false;
  };
  
  // Gérer la perte de focus sur le champ de recherche
  const handleBlur = (index) => {
    // Utiliser setTimeout pour permettre le clic sur un élément du dropdown avant qu'il disparaisse
    setTimeout(() => {
      productLines.value[index].showDropdown = false;
    }, 200);
  };
  
  // Obtenir le label de mesure selon le type
  const getMesureLabel = (type) => {
    switch(type) {
      case 'carre': return 'Mètres carrés';
      case 'metre': return 'Mètres';
      default: return 'Quantité';
    }
  };
  
  // Calculer le total d'une ligne
  const calculateLineTotal = (line) => {
    return line.prix * line.mesure;
  };
  
  // Calculer les totaux
  const totalHT = computed(() => {
    return selectedProducts.value.reduce((total, item) => {
      return total + calculateLineTotal(item);
    }, 0);
  });
  
  const totalTVA = computed(() => {
    return totalHT.value * (props.tvaRate / 100);
  });
  
  const totalTTC = computed(() => {
    return totalHT.value + totalTVA.value;
  });
  
  // Validation du formulaire
  const isFormValid = computed(() => {
    return productLines.value.length > 0 && productLines.value.every(line => 
      line.selectedProduct && line.prix >= 0 && line.mesure > 0
    );
  });
  
  // Ajouter une ligne
  const addProductLine = () => {
    productLines.value.push(initProductLine());
  };
  
  // Supprimer une ligne
  const removeProductLine = (index) => {
    // Ne pas supprimer s'il n'y a qu'une seule ligne
    if (productLines.value.length <= 1) return;
    
    productLines.value.splice(index, 1);
  };
  
  // Supprimer un produit dans la vue récapitulative
  const removeProduct = (index) => {
    if (selectedProducts.value.length <= 1) return;
    selectedProducts.value.splice(index, 1);
    emit('update-products', selectedProducts.value);
  };
  
  // Valider la sélection et passer à la vue récapitulative
  const validateAndShowSummary = () => {
    if (!isFormValid.value) return;
  
    const products = productLines.value.map(line => ({
        produit: line.selectedProduct,
        prix: line.prix,
        mesure: line.mesure,
        commentaire: line.commentaire
    }));
  
    selectedProducts.value = products;
    emit('update-products', selectedProducts.value);
    showSummaryOnly.value = true;
  };
  
  // Revenir à la sélection des produits
  const backToSelection = () => {
    // Transférer les produits sélectionnés dans les lignes du formulaire
    productLines.value = selectedProducts.value.map(item => ({
      selectedProduct: item.produit,
      prix: item.prix,
      mesure: item.mesure,
      commentaire: item.commentaire || '',
      searchQuery: item.produit.nom,
      filteredProducts: [...props.produits],
      showDropdown: false
    }));
    
    showSummaryOnly.value = false;
  };
  
  // Passer à l'étape suivante
  const submitProductSelection = () => {
    if (selectedProducts.value.length > 0) {
      emit('next-step');
    }
  };
  </script>
  
  <style scoped>
  .product-selection-container {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.section-title {
  font-size: 1.4rem;
  color: #2d3748;
  margin-bottom: 1.6rem;
  padding-bottom: 0.8rem;
  border-bottom: 2px solid rgba(45, 101, 255, 0.1);
}

.products-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.product-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.product-card:hover {
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
  padding-bottom: 0.8rem;
  border-bottom: 1px solid #edf2f7;
}

.card-header h3 {
  font-size: 1.1rem;
  color: #2d3748;
  font-weight: 600;
  margin: 0;
}

.remove-product-btn {
  background-color: #f56565;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  transition: all 0.2s ease;
}

.remove-product-btn:hover:not(:disabled) {
  background-color: #e53e3e;
  transform: scale(1.1);
}

.remove-product-btn:disabled {
  background-color: #cbd5e0;
  color: white;
  cursor: not-allowed;
}

.form-group {
  margin-bottom: 1.2rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #4a5568;
}

.required {
  color: #e53e3e;
  margin-left: 2px;
}

/* Styles pour l'autocomplétion */
.autocomplete-container {
  position: relative;
}

.autocomplete-input {
  width: 100%;
  padding: 0.9rem 1rem 0.9rem 2.5rem;
  font-size: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background-color: white;
  transition: all 0.2s ease;
  color: #4a5568;
}

.autocomplete-input:focus {
  outline: none;
  border-color: #3182ce;
  box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
}

.search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #a0aec0;
  pointer-events: none;
}

.autocomplete-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  max-height: 300px;
  overflow-y: auto;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0 0 8px 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 10;
}

.autocomplete-item {
  padding: 0.8rem 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
}

.autocomplete-item:hover {
  background-color: #f7fafc;
}

.autocomplete-item:not(:last-child) {
  border-bottom: 1px solid #f1f5f9;
}

.product-suggestion {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.product-suggestion-image {
  width: 40px;
  height: 40px;
  border-radius: 4px;
  overflow: hidden;
  flex-shrink: 0;
  background-color: #f1f5f9;
}

.product-suggestion-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-suggestion-info {
  flex: 1;
}

.product-suggestion-name {
  font-weight: 500;
  color: #2d3748;
}

.product-suggestion-price {
  font-size: 0.9rem;
  color: #4a5568;
}

.no-results {
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  padding: 1rem;
  background-color: white;
  border: 1px solid #e2e8f0;
  border-radius: 0 0 8px 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  color: #718096;
  font-style: italic;
  z-index: 10;
  text-align: center;
}

.form-input {
  width: 100%;
  padding: 0.9rem 1rem;
  font-size: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background-color: white;
  transition: all 0.2s ease;
  color: #4a5568;
}

.form-input:focus {
  outline: none;
  border-color: #3182ce;
  box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
}

.product-details {
  margin-top: 1.5rem;
  background-color: #f8fafc;
  border-radius: 10px;
  padding: 1.5rem;
}

.details-grid {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 1.5rem;
}

.product-image-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.8rem;
}

.product-image {
  width: 100%;
  max-width: 180px;
  height: auto;
  border-radius: 8px;
  object-fit: cover;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.product-name {
  font-weight: 600;
  color: #2d3748;
  text-align: center;
}

.product-specs {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.line-total {
  padding: 0.9rem 1rem;
  background-color: white;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  color: #2d3748;
  font-weight: 600;
  font-size: 1.1rem;
  text-align: right;
}

.add-product-container {
  display: flex;
  justify-content: center;
  margin: 1.5rem 0;
}

.add-product-btn {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  background: var(--gradient-sidebar, linear-gradient(135deg, #4f6cf7, #5a45ff));
  color: white;
  border: none;
  border-radius: 10px;
  padding: 1rem 1.8rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(45, 101, 255, 0.2);
}

.add-product-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(45, 101, 255, 0.3);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 1.5rem;
}

.validate-btn {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  background-color: #38a169;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 0.9rem 1.8rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(56, 161, 105, 0.2);
}

.validate-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(56, 161, 105, 0.3);
}

.validate-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.form-textarea {
  width: 100%;
  padding: 0.9rem 1rem;
  font-size: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background-color: white;
  transition: all 0.2s ease;
  color: #4a5568;
  resize: vertical;
}

.form-textarea:focus {
  outline: none;
  border-color: #3182ce;
  box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
}

/* Vue récapitulative */
.summary-view {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.products-table {
  background-color: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
  margin-bottom: 2rem;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
  padding: 1rem 1.5rem;
  background-color: #f1f5f9;
  font-weight: 600;
  color: #2d3748;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
  padding: 1rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  align-items: center;
}

.table-row:hover {
  background-color: #f8fafc;
}

.col.product-name {
  font-weight: 500;
  color: #2d3748;
}

.col.actions {
  display: flex;
  justify-content: center;
}

.remove-btn {
  background-color: #f56565;
  border: none;
  color: white;
  cursor: pointer;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  transition: all 0.2s ease;
}

.remove-btn:hover:not(:disabled) {
  background-color: #e53e3e;
  transform: scale(1.1);
}

.remove-btn:disabled {
  background-color: #cbd5e0;
  color: white;
  cursor: not-allowed;
}

.totals-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 2rem;
}

.totals-card {
  background-color: white;
  border-radius: 10px;
  padding: 1.5rem;
  width: 300px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
}

.total-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.8rem;
  font-size: 1rem;
  color: #4a5568;
}

.total-row.grand-total {
  margin-top: 0.8rem;
  padding-top: 0.8rem;
  border-top: 1px solid #e2e8f0;
  font-weight: 600;
  font-size: 1.1rem;
  color: #2d3748;
}

.amount {
  font-weight: 500;
}

/* Boutons d'action pour le récapitulatif */
.action-buttons {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
  gap: 1rem;
}

.modify-btn {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  background-color: #4a5568;
  color: white;
  border: none;
  border-radius: 10px;
  padding: 0.9rem 1.8rem;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(74, 85, 104, 0.2);
}

.modify-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(74, 85, 104, 0.3);
}

.next-btn {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 0.9rem 1.8rem;
  background: var(--gradient-sidebar, linear-gradient(135deg, #4f6cf7, #5a45ff));
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(45, 101, 255, 0.2);
}

.next-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(45, 101, 255, 0.3);
}

.table-row-comment {
  display: grid;
  grid-template-columns: 0.5fr 5fr;
  padding: 0.5rem 1.5rem 1rem 1.5rem;
  background-color: #f8fafc;
  border-top: none;
  font-size: 0.9rem;
  color: #4a5568;
}

.comment-label {
  font-weight: 500;
}

.comment-text {
  font-style: italic;
}

/* Responsive styles */
@media (max-width: 768px) {
  .details-grid {
    grid-template-columns: 1fr;
  }
  
  .product-image-container {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  
  .product-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
  }
  
  .product-name {
    text-align: left;
  }
  
  .table-header, .table-row {
    grid-template-columns: 2fr 1fr 1fr 0.5fr;
  }
  
  .col:nth-child(3) {
    display: none;
  }
  
  .action-buttons {
    flex-direction: column;
  }
  
  .modify-btn, .next-btn {
    width: 100%;
    justify-content: center;
  }
  
  .totals-card {
    width: 100%;
  }
}

@media (max-width: 576px) {
  .product-card {
    padding: 1rem;
  }
  
  .table-header {
    display: none;
  }
  
  .table-row {
    display: flex;
    flex-direction: column;
    padding: 1rem;
    gap: 0.5rem;
  }
  
  .col {
    display: flex;
    justify-content: space-between;
    width: 100%;
    padding: 0.25rem 0;
  }
  
  .col::before {
    content: attr(data-label);
    font-weight: 600;
  }
  
  .col.actions {
    justify-content: flex-end;
    margin-top: 0.5rem;
  }
  
  .table-row-comment {
    grid-template-columns: 1fr;
    gap: 0.25rem;
  }
  
  .autocomplete-dropdown {
    max-height: 200px;
  }
  
  .product-suggestion-image {
    width: 30px;
    height: 30px;
  }
}
  </style>