<template>
    <div class="client-info-container">
      <div class="info-section">
        <h2 class="section-title">Sélection du client</h2>
        
        <!-- Options de sélection client -->
        <div class="client-selection-options">
          <div 
            class="selection-option" 
            :class="{ active: clientType === 'existing' }"
            @click="setClientType('existing')"
          >
            <div class="option-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="option-content">
              <div class="option-title">Client existant</div>
              <div class="option-description">Sélectionner parmi les clients déjà enregistrés</div>
            </div>
          </div>
          
          <div 
            class="selection-option" 
            :class="{ active: clientType === 'new' }"
            @click="setClientType('new')"
          >
            <div class="option-icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="option-content">
              <div class="option-title">Nouveau client</div>
              <div class="option-description">Créer un nouveau profil client</div>
            </div>
          </div>
        </div>
        
        <!-- Sélection d'un client existant -->
        <div v-if="clientType === 'existing'" class="client-dropdown-container">
          <label class="form-label">Sélectionnez un client dans la liste</label>
          <div class="custom-select">
            <select v-model="selectedClientId" @change="handleExistingClientSelect" class="form-select">
              <option value="" disabled selected>Choisir un client</option>
              <option v-for="client in props.clients" :key="client.id" :value="client.id">
                {{ client.prenom }} {{ client.nom }}
              </option>
            </select>
            <i class="select-icon fas fa-chevron-down"></i>
          </div>
          
          <!-- Aperçu du client sélectionné -->
          <div v-if="selectedClientId && clientPreview" class="selected-client-preview">
            <div class="preview-header">
              <div class="client-avatar">{{ getInitials(clientPreview.prenom, clientPreview.nom) }}</div>
              <div class="client-info">
                <h3 class="client-name">{{ clientPreview.prenom }} {{ clientPreview.nom }}</h3>
                <div class="client-detail"><i class="fas fa-envelope"></i> {{ clientPreview.email }}</div>
                <div class="client-detail"><i class="fas fa-phone"></i> {{ clientPreview.telephone }}</div>
              </div>
            </div>
            <div class="preview-address">
              <div class="address-title"><i class="fas fa-map-marker-alt"></i> Adresse</div>
              <div class="address-content">
                {{ clientPreview.adresse }} {{ clientPreview.numero_domicile }}, {{ clientPreview.code_postal }} {{ clientPreview.ville }}
              </div>
            </div>
          </div>
        </div>
        
        <!-- Formulaire pour nouveau client -->
        <form v-if="clientType === 'new'" @submit.prevent="submitForm" class="new-client-form">
          <div class="form-section">
            <h3 class="form-section-title">Informations personnelles</h3>
            <div class="form-grid">
              <div class="form-group">
                <label for="prenom" class="form-label">Prénom <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="prenom" 
                  v-model="formData.prenom" 
                  class="form-input" 
                  placeholder="Prénom du client"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="nom" class="form-label">Nom <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="nom" 
                  v-model="formData.nom" 
                  class="form-input" 
                  placeholder="Nom du client"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="email" class="form-label">Email <span class="required">*</span></label>
                <input 
                  type="email" 
                  id="email" 
                  v-model="formData.email" 
                  class="form-input" 
                  placeholder="email@exemple.com"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="telephone" class="form-label">Téléphone <span class="required">*</span></label>
                <input 
                  type="tel" 
                  id="telephone" 
                  v-model="formData.telephone" 
                  class="form-input" 
                  placeholder="+32 XXX XX XX XX"
                  required
                />
              </div>
            </div>
          </div>
          
          <div class="form-section">
            <h3 class="form-section-title">Adresse</h3>
            <div class="form-grid">
              <div class="form-group col-span-2">
                <label for="adresse" class="form-label">Rue <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="adresse" 
                  v-model="formData.adresse" 
                  class="form-input" 
                  placeholder="Nom de la rue"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="numero_domicile" class="form-label">Numéro <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="numero_domicile" 
                  v-model="formData.numero_domicile" 
                  class="form-input" 
                  placeholder="N°"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="code_postal" class="form-label">Code postal <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="code_postal" 
                  v-model="formData.code_postal" 
                  class="form-input" 
                  placeholder="1000"
                  pattern="[0-9]{4}"
                  title="Le code postal doit contenir 4 chiffres"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="ville" class="form-label">Ville <span class="required">*</span></label>
                <input 
                  type="text" 
                  id="ville" 
                  v-model="formData.ville" 
                  class="form-input" 
                  placeholder="Ville"
                  required
                />
              </div>
              
              <div class="form-group">
                <label for="province" class="form-label">Province</label>
                <select
                  id="province"
                  v-model="formData.province"
                  class="form-select"
                >
                  <option value="">Sélectionnez une province</option>
                  <option value="Anvers">Anvers</option>
                  <option value="Brabant flamand">Brabant flamand</option>
                  <option value="Brabant wallon">Brabant wallon</option>
                  <option value="Bruxelles">Bruxelles</option>
                  <option value="Flandre occidentale">Flandre occidentale</option>
                  <option value="Flandre orientale">Flandre orientale</option>
                  <option value="Hainaut">Hainaut</option>
                  <option value="Liège">Liège</option>
                  <option value="Limbourg">Limbourg</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Namur">Namur</option>
                </select>
              </div>
            </div>
          </div>
  
          <div class="form-section">
            <h3 class="form-section-title">Informations complémentaires</h3>
            <div class="form-grid">
              <div class="form-group">
                <label for="personal_id" class="form-label">Numéro National</label>
                <input 
                type="text" 
                id="personal_id" 
                v-model="formData.personal_id" 
                class="form-input" 
                placeholder="Entrez votre numéro national"
                />
              </div>
              
              <div class="form-group">
                <label for="numero_tva" class="form-label">Numéro de TVA</label>
                <input 
                type="text" 
                id="numero_tva" 
                v-model="formData.numero_tva" 
                class="form-input" 
                placeholder="Entrez votre numéro de TVA"
                />
              </div>
              
              <div class="form-group">
                <label for="valeur_tva" class="form-label">Taux de TVA</label>
                <select 
                  id="valeur_tva" 
                  v-model="formData.valeur_tva"
                  class="form-select"
                >
                  <option value="6">6%</option>
                  <option value="21">21%</option>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      
      <div class="action-buttons">
        <button 
          @click="submitForm" 
          class="next-btn" 
          :disabled="!canProceed"
        >
          <span>Continuer</span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive, computed } from 'vue';
  
  const props = defineProps({
    clientData: {
      type: Object,
      required: true
    },
    clients: {
      type: Array,
      default: () => []
    }
  });
  
  const emit = defineEmits(['update-client', 'next-step']);
  
  // États locaux
  const clientType = ref('existing');
  const selectedClientId = ref('');
  const clientPreview = ref(null);
  
  // Modèle de données pour le formulaire
  const formData = reactive({
    nom: '',
    prenom: '',
    email: '',
    telephone: '',
    personal_id: '',
    pays: 'Belgique',
    province: '',
    ville: '',
    code_postal: '',
    adresse: '',
    numero_domicile: '',
    numero_tva: '',
    valeur_tva: '21'
  });
  
  // Obtenir les initiales du nom et prénom
  const getInitials = (firstname, lastname) => {
    return `${firstname ? firstname[0] : ''}${lastname ? lastname[0] : ''}`.toUpperCase();
  };
  
  // Définir le type de client (nouveau ou existant)
  const setClientType = (type) => {
    clientType.value = type;
    // Réinitialisation si on change de type
    if (type === 'new') {
      selectedClientId.value = '';
      clientPreview.value = null;
    } else {
      // Réinitialiser le formulaire si on passe à "client existant"
      Object.keys(formData).forEach(key => {
        formData[key] = key === 'pays' ? 'Belgique' : key === 'valeur_tva' ? '21' : '';
      });
    }
  };
  
  // Vérifier si on peut passer à l'étape suivante
  const canProceed = computed(() => {
    // Si client existant, un client doit être sélectionné
    if (clientType.value === 'existing') {
      return !!selectedClientId.value;
    }
    
    // Si nouveau client, les champs obligatoires doivent être remplis
    if (clientType.value === 'new') {
      return (
        formData.nom &&
        formData.prenom &&
        formData.email &&
        formData.telephone &&
        formData.adresse &&
        formData.numero_domicile &&
        formData.code_postal &&
        formData.ville
      );
    }
    
    return false;
  });
  
  // Gérer la sélection d'un client existant
  const handleExistingClientSelect = () => {
    if (!selectedClientId.value) {
      clientPreview.value = null;
      return;
    }
    
    const selectedClient = props.clients.find(client => client.id == selectedClientId.value);
    if (selectedClient) {
      clientPreview.value = selectedClient;
      // Copier les données du client sélectionné dans formData pour l'émission
      Object.assign(formData, selectedClient);
    }
  };
  
  // Formater le numéro de TVA
 
  
  // Soumettre le formulaire
  const submitForm = () => {
    if (!canProceed.value) return;
    
    // Formater le numéro de TVA
    
    // Émettre les données client mises à jour
    emit('update-client', formData);
    
    // Passer à l'étape suivante
    emit('next-step');
  };
  </script>
  
  <style scoped>
  .client-info-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  
  .info-section {
    background-color: #f8fafc;
    border-radius: 12px;
    padding: 1.8rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
  }
  
  .section-title {
    font-size: 1.4rem;
    color: #2d3748;
    margin-bottom: 1.6rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid rgba(45, 101, 255, 0.1);
  }
  
  /* Options de sélection du type de client */
  .client-selection-options {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 2rem;
  }
  
  .selection-option {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border-radius: 12px;
    background-color: white;
    cursor: pointer;
    width: 50%;
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }
  
  .selection-option:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  }
  
  .selection-option.active {
    border-color: #3182ce;
    background-color: #ebf8ff;
  }
  
  .option-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 10px;
    background: var(--gradient-sidebar);
    color: white;
    font-size: 1.2rem;
  }
  
  .option-content {
    flex: 1;
  }
  
  .option-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #2d3748;
    margin-bottom: 0.5rem;
  }
  
  .option-description {
    color: #718096;
    font-size: 0.9rem;
  }
  
  /* Sélection client existant */
  .client-dropdown-container {
    margin-bottom: 2rem;
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
  
  .custom-select {
    position: relative;
    margin-bottom: 1.5rem;
  }
  
  .form-select, .form-input {
    width: 100%;
    padding: 0.9rem 1rem;
    font-size: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: white;
    transition: all 0.2s ease;
    color: #4a5568;
  }
  
  .form-select {
    appearance: none;
    padding-right: 2.5rem;
  }
  
  .form-select:focus, .form-input:focus {
    outline: none;
    border-color: #3182ce;
    box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
  }
  
  .select-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    pointer-events: none;
  }
  
  /* Aperçu du client sélectionné */
  .selected-client-preview {
    background-color: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    border-left: 4px solid #3182ce;
  }
  
  .preview-header {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.2rem;
  }
  
  .client-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--gradient-profile);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.2rem;
  }
  
  .client-info {
    flex: 1;
  }
  
  .client-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
  }
  
  .client-detail {
    color: #718096;
    font-size: 0.95rem;
    margin-bottom: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .preview-address {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
  }
  
  .address-title {
    font-weight: 500;
    color: #4a5568;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .address-content {
    color: #718096;
    font-size: 0.95rem;
  }
  
  /* Formulaire nouveau client */
  .new-client-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
  }
  
  .form-section {
    background-color: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }
  
  .form-section-title {
    font-size: 1.15rem;
    color: #2d3748;
    margin-bottom: 1.2rem;
    padding-bottom: 0.6rem;
    border-bottom: 1px solid #edf2f7;
  }
  
  .form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.2rem;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .form-group.col-span-2 {
    grid-column: span 2;
  }
  
  /* Bouton d'action */
  .action-buttons {
    display: flex;
    justify-content: flex-end;
    margin-top: 1rem;
  }
  
  .next-btn {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    padding: 0.9rem 1.8rem;
    background: var(--gradient-sidebar);
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
  
  .next-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .client-selection-options {
      flex-direction: column;
    }
    
    .selection-option {
      width: 100%;
    }
    
    .form-grid {
      grid-template-columns: 1fr;
    }
    
    .form-group.col-span-2 {
      grid-column: auto;
    }
  }
  </style>