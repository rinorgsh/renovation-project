<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Layout from '@/Components/TopNav.vue';
import Modal from '@/Components/Modal2.vue';

// Define props for products passed from the controller
const props = defineProps({
  produits: {
    type: Array,
    default: () => [],
  },
  errors: Object,
  success: String,
});

// Component state
const showAddModal = ref(false);
const showEditModal = ref(false);
const isLoading = ref(false);
const currentProduit = ref(null);
const searchQuery = ref('');
const filteredProduits = ref([]);

// New product form data
const newProduit = ref({
  nom: '',
  description: '',
  type: '',
  prix: '',
  image: null,
});

// Map pour l'affichage des types
const typeDisplay = {
  'unit': 'Unité',
  'carre': 'Mètre carré (m²)',
  'metre': 'Mètre (m)'
};

// Preview for image upload
const imagePreview = ref(null);

// Filter products based on search query
const filterProduits = () => {
  if (!searchQuery.value) {
    filteredProduits.value = [...props.produits];
    return;
  }
  
  const query = searchQuery.value.toLowerCase();
  filteredProduits.value = props.produits.filter(produit => 
    produit.nom.toLowerCase().includes(query) || 
    produit.description.toLowerCase().includes(query) ||
    produit.type.toLowerCase().includes(query)
  );
};

// Initialize filtered products
onMounted(() => {
  filteredProduits.value = [...props.produits];
});

// Handle image selection
const handleImageChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    newProduit.value.image = file;
    const reader = new FileReader();
    reader.onload = e => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

// Reset form
const resetForm = () => {
  newProduit.value = {
    nom: '',
    description: '',
    type: '',
    prix: '',
    image: null,
  };
  imagePreview.value = null;
};

// Open edit modal with product data
const openEditModal = (produit) => {
  currentProduit.value = { ...produit };
  if (produit.image && !produit.image.startsWith('blob:')) {
    imagePreview.value = produit.image;
  } else {
    imagePreview.value = null;
  }
  showEditModal.value = true;
};

// Submit form to add new product
const addProduit = () => {
  isLoading.value = true;
  
  // Create FormData for file upload
  const formData = new FormData();
  formData.append('nom', newProduit.value.nom);
  formData.append('description', newProduit.value.description);
  formData.append('type', newProduit.value.type);
  formData.append('prix', newProduit.value.prix);
  
  if (newProduit.value.image) {
    formData.append('image', newProduit.value.image);
  }
  
  router.post('/produits', formData, {
    onSuccess: () => {
      isLoading.value = false;
      showAddModal.value = false;
      resetForm();
    },
    onError: () => {
      isLoading.value = false;
    },
    forceFormData: true
  });
};

// Submit form to update existing product
const updateProduit = () => {
  isLoading.value = true;
  
  // Create FormData for file upload
  const formData = new FormData();
  formData.append('_method', 'PUT'); // Laravel method spoofing
  formData.append('nom', currentProduit.value.nom);
  formData.append('description', currentProduit.value.description);
  formData.append('type', currentProduit.value.type);
  formData.append('prix', currentProduit.value.prix);
  
  if (currentProduit.value.image && currentProduit.value.image instanceof File) {
    formData.append('image', currentProduit.value.image);
  }
  
  router.post(`/produits/${currentProduit.value.id}`, formData, {
    onSuccess: () => {
      isLoading.value = false;
      showEditModal.value = false;
    },
    onError: () => {
      isLoading.value = false;
    },
    forceFormData: true
  });
};

// Delete product with confirmation
const deleteProduit = (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce produit?')) {
    router.delete(`/produits/${id}`);
  }
};
</script>

<template>
  <Layout :currentPage="'produits'">
    <Head title="Gérer les produits" />
    
    <div class="products-container">
      <div class="header-section">
        <h1>Gérer les produits</h1>
        <div class="action-bar">
          <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input 
              type="text" 
              v-model="searchQuery" 
              @input="filterProduits"
              placeholder="Rechercher des produits..." 
              class="search-input"
            />
          </div>
          <button @click="showAddModal = true" class="btn-add">
            <i class="fas fa-plus"></i> Ajouter un produit
          </button>
        </div>
      </div>
      
      <!-- Success message if provided -->
      <div v-if="success" class="alert-success">
        {{ success }}
      </div>
      
      <!-- Products grid -->
      <div class="products-grid">
        <div v-if="filteredProduits.length === 0" class="no-products">
          <i class="fas fa-box-open empty-icon"></i>
          <p>Aucun produit trouvé</p>
          <button @click="showAddModal = true" class="btn-secondary">
            Ajouter votre premier produit
          </button>
        </div>
        
        <div v-else class="product-card" v-for="produit in filteredProduits" :key="produit.id">
          <div class="product-image-container">
            <img 
              :src="produit.image || '/images/placeholder.jpg'" 
              :alt="produit.nom"
              class="product-image" 
              @error="$event.target.src = '/images/placeholder.jpg'"
            />
          </div>
          <div class="product-details">
            <h3>{{ produit.nom }}</h3>
            <p class="product-description">{{ produit.description }}</p>
            <div class="product-meta">
              <span class="product-type">{{ produit.type ? typeDisplay[produit.type] || produit.type : 'Non spécifié' }}</span>
              <span class="product-price">{{ produit.prix ? `${produit.prix}€` : 'Prix non défini' }}</span>
            </div>
          </div>
          <div class="product-actions">
            <button @click="openEditModal(produit)" class="btn-edit">
              <i class="fas fa-edit"></i>
            </button>
            <button @click="deleteProduit(produit.id)" class="btn-delete">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Add Product Modal -->
    <Modal :show="showAddModal" @close="showAddModal = false">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Ajouter un produit</h2>
          <button @click="showAddModal = false" class="close-button">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form @submit.prevent="addProduit" class="product-form">
          <div class="form-group">
            <label for="nom">Nom du produit</label>
            <input 
              id="nom" 
              type="text" 
              v-model="newProduit.nom" 
              required 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.nom }"
            />
            <div v-if="errors && errors.nom" class="invalid-feedback">
              {{ errors.nom }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="description">Description</label>
            <textarea 
              id="description" 
              v-model="newProduit.description" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.description }"
              rows="3"
            ></textarea>
            <div v-if="errors && errors.description" class="invalid-feedback">
              {{ errors.description }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="type">Type de mesure</label>
            <select 
              id="type" 
              v-model="newProduit.type" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.type }"
            >
              <option value="">Sélectionnez un type</option>
              <option value="unit">Unité</option>
              <option value="carre">Mètre carré (m²)</option>
              <option value="metre">Mètre (m)</option>
            </select>
            <div v-if="errors && errors.type" class="invalid-feedback">
              {{ errors.type }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="prix">Prix (€)</label>
            <input 
              id="prix" 
              type="number" 
              step="0.01" 
              v-model="newProduit.prix" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.prix }"
            />
            <div v-if="errors && errors.prix" class="invalid-feedback">
              {{ errors.prix }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="image">Image</label>
            <div class="image-upload-container">
              <div 
                class="image-preview" 
                v-if="imagePreview" 
                :style="{ backgroundImage: `url(${imagePreview})` }"
              ></div>
              <div class="image-upload" v-else>
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Cliquez ou glissez une image ici</p>
              </div>
              <input 
                id="image" 
                type="file" 
                @change="handleImageChange" 
                accept="image/*" 
                class="file-input"
                :class="{ 'is-invalid': errors && errors.image }"
              />
            </div>
            <div v-if="errors && errors.image" class="invalid-feedback">
              {{ errors.image }}
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" @click="showAddModal = false" class="btn-secondary">
              Annuler
            </button>
            <button type="submit" class="btn-primary" :disabled="isLoading">
              <i class="fas fa-spinner fa-spin" v-if="isLoading"></i>
              <span v-else>Ajouter</span>
            </button>
          </div>
        </form>
      </div>
    </Modal>
    
    <!-- Edit Product Modal -->
    <Modal :show="showEditModal" @close="showEditModal = false">
      <div class="modal-content" v-if="currentProduit">
        <div class="modal-header">
          <h2>Modifier le produit</h2>
          <button @click="showEditModal = false" class="close-button">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <form @submit.prevent="updateProduit" class="product-form">
          <div class="form-group">
            <label for="edit-nom">Nom du produit</label>
            <input 
              id="edit-nom" 
              type="text" 
              v-model="currentProduit.nom" 
              required 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.nom }"
            />
            <div v-if="errors && errors.nom" class="invalid-feedback">
              {{ errors.nom }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="edit-description">Description</label>
            <textarea 
              id="edit-description" 
              v-model="currentProduit.description" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.description }"
              rows="3"
            ></textarea>
            <div v-if="errors && errors.description" class="invalid-feedback">
              {{ errors.description }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="edit-type">Type de mesure</label>
            <select 
              id="edit-type" 
              v-model="currentProduit.type" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.type }"
            >
              <option value="">Sélectionnez un type</option>
              <option value="unit">Unité</option>
              <option value="carre">Mètre carré (m²)</option>
              <option value="metre">Mètre (m)</option>
            </select>
            <div v-if="errors && errors.type" class="invalid-feedback">
              {{ errors.type }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="edit-prix">Prix (€)</label>
            <input 
              id="edit-prix" 
              type="number" 
              step="0.01" 
              v-model="currentProduit.prix" 
              class="form-control"
              :class="{ 'is-invalid': errors && errors.prix }"
            />
            <div v-if="errors && errors.prix" class="invalid-feedback">
              {{ errors.prix }}
            </div>
          </div>
          
          <div class="form-group">
            <label for="edit-image">Image</label>
            <div class="image-upload-container">
              <div 
                class="image-preview" 
                v-if="imagePreview" 
                :style="{ backgroundImage: `url(${imagePreview})` }"
              ></div>
              <div class="image-upload" v-else>
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Cliquez ou glissez une image ici</p>
              </div>
              <input 
                id="edit-image" 
                type="file" 
                @change="handleImageChange" 
                accept="image/*" 
                class="file-input"
                :class="{ 'is-invalid': errors && errors.image }"
              />
            </div>
            <div v-if="errors && errors.image" class="invalid-feedback">
              {{ errors.image }}
            </div>
          </div>
          
          <div class="form-actions">
            <button type="button" @click="showEditModal = false" class="btn-secondary">
              Annuler
            </button>
            <button type="submit" class="btn-primary" :disabled="isLoading">
              <i class="fas fa-spinner fa-spin" v-if="isLoading"></i>
              <span v-else>Mettre à jour</span>
            </button>
          </div>
        </form>
      </div>
    </Modal>
  </Layout>
</template>

<style scoped>
/* Main container */
.products-container {
  padding: 1rem 0;
}

/* Header section */
.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
  flex-wrap: wrap;
  gap: 1rem;
}

.header-section h1 {
  font-size: 1.8rem;
  color: var(--text-color);
  margin: 0;
}

.action-bar {
  display: flex;
  gap: 1rem;
  align-items: center;
}

/* Search input */
.search-container {
  position: relative;
  width: 300px;
}

.search-icon {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: var(--light-text);
}

.search-input {
  width: 100%;
  padding: 10px 10px 10px 40px;
  border-radius: var(--border-radius);
  border: 1px solid var(--border-color);
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
}

/* Add button */
.btn-add {
  padding: 10px 16px;
  background: var(--primary-color);
  color: white;
  border: none;
  border-radius: var(--border-radius);
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-add:hover {
  background: #0d5ca9;
  transform: translateY(-2px);
}

/* Success alert */
.alert-success {
  background-color: #d4edda;
  color: #155724;
  padding: 12px 20px;
  border-radius: var(--border-radius);
  margin-bottom: 20px;
  border-left: 4px solid #28a745;
}

/* Products grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  margin-top: 1.5rem;
}

/* Empty state */
.no-products {
  grid-column: 1 / -1;
  text-align: center;
  padding: 3rem;
  background: white;
  border-radius: var(--border-radius);
  box-shadow: var(--shadow);
}

.empty-icon {
  font-size: 3rem;
  color: var(--light-text);
  margin-bottom: 1rem;
}

.no-products p {
  margin-bottom: 1.5rem;
  color: var(--light-text);
  font-size: 1.1rem;
}

/* Product card */
.product-card {
  background: white;
  border-radius: var(--border-radius);
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  position: relative;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.product-image-container {
  height: 200px;
  overflow: hidden;
  background: #f5f5f5;
  display: flex;
  align-items: center;
  justify-content: center;
}

.product-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

.product-details {
  padding: 1.25rem;
}

.product-details h3 {
  margin: 0 0 0.5rem;
  font-size: 1.15rem;
  color: var(--text-color);
}

.product-description {
  color: var(--light-text);
  font-size: 0.9rem;
  margin-bottom: 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-meta {
  display: flex;
  justify-content: space-between;
  color: var(--light-text);
  font-size: 0.85rem;
}

.product-price {
  color: var(--primary-color);
  font-weight: 600;
}

.product-actions {
  position: absolute;
  top: 10px;
  right: 10px;
  display: flex;
  gap: 5px;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.product-card:hover .product-actions {
  opacity: 1;
}

.btn-edit, .btn-delete {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-edit {
  background: white;
  color: var(--primary-color);
}

.btn-edit:hover {
  background: var(--primary-color);
  color: white;
}

.btn-delete {
  background: white;
  color: var(--danger-color);
}

.btn-delete:hover {
  background: var(--danger-color);
  color: white;
}

/* Modal styles */
.modal-content {
  max-width: 600px;
  width: 100%;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-bottom: 1rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid var(--border-color);
}

.modal-header h2 {
  font-size: 1.5rem;
  margin: 0;
  color: var(--text-color);
}

.close-button {
  background: none;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: var(--light-text);
  transition: color 0.2s ease;
}

.close-button:hover {
  color: var(--danger-color);
}

/* Form styles */
.product-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-weight: 500;
  color: var(--text-color);
  font-size: 0.95rem;
}

.form-control {
  padding: 10px 12px;
  border-radius: var(--border-radius);
  border: 1px solid var(--border-color);
  font-size: 0.95rem;
  transition: all 0.3s ease;
}

.form-control:focus {
  outline: none;
  border-color: var(--primary-color);
  box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
}

.is-invalid {
  border-color: var(--danger-color) !important;
}

.invalid-feedback {
  color: var(--danger-color);
  font-size: 0.85rem;
  margin-top: 0.25rem;
}

/* Image upload */
.image-upload-container {
  position: relative;
  height: 160px;
  border: 2px dashed var(--border-color);
  border-radius: var(--border-radius);
  overflow: hidden;
  cursor: pointer;
}

.image-upload {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  color: var(--light-text);
}

.image-upload i {
  font-size: 2rem;
}

.image-upload p {
  font-size: 0.9rem;
}

.image-preview {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.file-input {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

/* Form actions */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1rem;
}

.btn-primary, .btn-secondary {
  padding: 10px 20px;
  border-radius: var(--border-radius);
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
}

.btn-primary {
  background: var(--primary-color);
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #0d5ca9;
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.btn-secondary {
  background: var(--secondary-color);
  color: var(--text-color);
}

.btn-secondary:hover {
  background: #c7c7c7;
}

/* Responsive */
@media (max-width: 768px) {
  .header-section {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .action-bar {
    width: 100%;
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-container {
    width: 100%;
  }
  
  .products-grid {
    grid-template-columns: 1fr;
  }
}
</style>