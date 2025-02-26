<template>
    <Layout currentPage="devis">
      <div class="devis-container">
        <!-- Header avec retour -->
        <div class="header-devis">
          <Link href="/home" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Retour
          </Link>
          <h1 class="title">Création du devis</h1>
        </div>
  
        <!-- Indicateur d'étapes -->
        <div class="steps-indicator">
          <div 
            class="step" 
            :class="{ active: currentStep === 1, completed: currentStep > 1, clickable: true }"
            @click="goToStep(1)"
          >
            <div class="step-number">
              <i v-if="currentStep > 1" class="fas fa-check"></i>
              <span v-else>1</span>
            </div>
            <div class="step-label">Informations client</div>
          </div>
          <div 
            class="step" 
            :class="{ 
              active: currentStep === 2, 
              completed: currentStep > 2,
              clickable: currentStep > 1 
            }"
            @click="currentStep > 1 && goToStep(2)"
          >
            <div class="step-number">
              <i v-if="currentStep > 2" class="fas fa-check"></i>
              <span v-else>2</span>
            </div>
            <div class="step-label">Sélection des produits</div>
          </div>
          <div 
            class="step" 
            :class="{ 
              active: currentStep === 3,
              clickable: currentStep > 2 
            }"
            @click="currentStep > 2 && goToStep(3)"
          >
            <div class="step-number">3</div>
            <div class="step-label">Récapitulatif</div>
          </div>
        </div>
  
        <!-- Composants des étapes -->
        <ClientInfo 
          v-if="currentStep === 1"
          :clientData="clientData"
          :clients="props.clients" 
          @update-client="updateClientData"
          @next-step="nextStep"
        />
  
        <ProductSelection 
          v-if="currentStep === 2"
          :selectedProducts="selectedProducts"
          @update-products="updateProducts"
          @next-step="nextStep"
          :produits="props.produits"
          :tvaRate="clientData.valeur_tva"
        />
        <OrderSummary 
          v-if="currentStep === 3"
          :client-data="clientData"
          :selected-products="selectedProducts"
          @submit-order="submitDevis"
          
        />
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue';
  import { Link, router } from '@inertiajs/vue3';
  import Layout from '@/Components/TopNav.vue';
  import ClientInfo from './Steps/ClientInfo.vue';
  import ProductSelection from './Steps/ProductSelection.vue';
  import OrderSummary from './Steps/OrderSummary.vue';
  
  const props = defineProps({
    produits: Array,
    clients: Array
  });
  
  const goToStep = (step) => {
    currentStep.value = step;
  };
  
  // État pour suivre l'étape actuelle
  const currentStep = ref(1);
  
  // État pour stocker les données du client
  const clientData = reactive({
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
  
  // État pour stocker les produits sélectionnés
  const selectedProducts = ref([]);
  
  // Méthode pour mettre à jour les données du client
  const updateClientData = (newData) => {
  Object.assign(clientData, newData);
  // Ajoutez cette ligne pour transmettre l'ID du client existant
  if (newData.existing_client_id) {
    clientData.existing_client_id = newData.existing_client_id;
  }
};
  
  // Méthode pour mettre à jour les produits sélectionnés
  const updateProducts = (products) => {
    selectedProducts.value = products;
  };
  
  const nextStep = () => {
    if (currentStep.value === 3) {
      submitDevis();
    } else {
      currentStep.value++;
    }
  };
  
  // Calcul des totaux
  const calculateTotals = () => {
    const total_ht = selectedProducts.value.reduce((total, item) => {
      return total + (item.produit.prix * item.quantity);
    }, 0);
  
    const total_tva = total_ht * (clientData.valeur_tva / 100);
    const total_ttc = total_ht + total_tva;
  
    return {
      total_ht,
      total_tva,
      total_ttc
    };
  };
  
  // Soumission du devis
  const submitDevis = (totals) => {
    router.post('/devis', {
      client: clientData,
      produits: selectedProducts.value,
      total_ht: totals.total_ht,
      total_tva: totals.total_tva,
      total_ttc: totals.total_ttc,
      signature_data: totals.signature_data,
      signature_date: totals.signature_date
    }, {
      onSuccess: () => {
        console.log('Devis créé avec succès');
      },
      onError: (errors) => {
        console.error('Erreurs:', errors);
      }
    });
  };
  </script>
  
  <style scoped>
  .devis-container {
    padding: 2rem;
    background-color: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
  }
  
  .header-devis {
    display: flex;
    align-items: center;
    margin-bottom: 2.5rem;
  }
  
  .back-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    color: #666;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    background-color: #f5f7fa;
  }
  
  .back-button:hover {
    background-color: #e9ecf1;
    transform: translateX(-3px);
  }
  
  .title {
    margin-left: 2rem;
    font-size: 1.8rem;
    color: #2d3748;
    font-weight: 600;
  }
  
  .steps-indicator {
    display: flex;
    justify-content: center;
    margin-bottom: 3.5rem;
    gap: 3rem;
    position: relative;
  }
  
  .steps-indicator::before {
    content: '';
    position: absolute;
    left: 10%;
    right: 10%;
    top: 50%;
    transform: translateY(-50%);
    height: 3px;
    background-color: #e2e8f0;
    z-index: 0;
  }
  
  .step {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  position: relative;
  z-index: 1;
  background-color: var(--card-bg);
  border-radius: 12px;
  transition: all 0.3s ease;
  cursor: default; /* Par défaut */
}

.step.clickable {
  cursor: pointer; /* Devient cliquable selon la condition */
}

.step.clickable:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.step:not(.clickable) {
  opacity: 0.8;
  cursor: not-allowed; /* Empêche le clic */
}
  
  .step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
  }
  
  .step.completed .step-number,
  .step.active .step-number {
    background: var(--gradient-sidebar);
    color: white;
  }
  
  .step-label {
    color: #4a5568;
    font-weight: 500;
    font-size: 1rem;
  }
  
  .step.active .step-label {
    color: #2d3748;
    font-weight: 600;
  }
  
  /* Media queries pour le responsive */
  @media (max-width: 768px) {
    .devis-container {
      padding: 1.5rem;
    }
  
    .title {
      font-size: 1.5rem;
    }
  
    .steps-indicator {
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }
  
    .steps-indicator::before {
      display: none;
    }
  }
  </style>