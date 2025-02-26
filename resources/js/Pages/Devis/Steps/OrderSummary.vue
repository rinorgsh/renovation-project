<template>
    <div class="summary-container">
        <!-- Informations client -->
        <div class="section">
            <h2>Informations du client</h2>
            <div class="client-info">
                <div class="info-group">
                    <strong>Nom complet:</strong>
                    <span>{{ clientData.prenom }} {{ clientData.nom }}</span>
                </div>
                <div class="info-group">
                    <strong>Email:</strong>
                    <span>{{ clientData.email }}</span>
                </div>
                <div class="info-group">
                    <strong>Téléphone:</strong>
                    <span>{{ clientData.telephone }}</span>
                </div>
                <div class="info-group">
                    <strong>Adresse:</strong>
                    <span>
                        {{ clientData.adresse }} {{ clientData.numero_domicile }},
                        {{ clientData.code_postal }} {{ clientData.ville }}
                    </span>
                </div>
                <div class="info-group">
                    <strong>TVA:</strong>
                    <span>{{ clientData.numero_tva || 'Non assujetti' }}</span>
                </div>
            </div>
        </div>

        <!-- Produits sélectionnés -->
        <div class="section">
            <h2>Produits sélectionnés</h2>
            <div class="products-list">
                <div v-for="(item, index) in selectedProducts" 
                     :key="index" 
                     class="product-card">
                    <img 
                        :src="item.produit.image" 
                        :alt="item.produit.nom"
                        class="product-image"
                    />
                    <div class="product-details">
                        <h3>{{ item.produit.nom }}</h3>
                        <p class="description">{{ item.produit.description }}</p>
                        <div class="specs">
                            <span>Type: {{ item.produit.type }}</span>
                            <span>Quantité: {{ item.mesure }} {{ getMesureUnit(item.produit.type) }}</span>
                            <span>Prix unitaire: {{ item.prix }}€</span>
                            <span>Total: {{ (item.prix * item.mesure).toFixed(2) }}€</span>
                        </div>
                    </div>
                    <div v-if="item.commentaire" class="product-comment">
                        <strong>Commentaire:</strong> {{ item.commentaire }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Totaux -->
        <div class="totals-section">
            <div class="total-line">
                <span>Total HT:</span>
                <span>{{ totalHT.toFixed(2) }}€</span>
            </div>
            <div class="total-line">
                <span>TVA ({{ clientData.valeur_tva }}%):</span>
                <span>{{ totalTVA.toFixed(2) }}€</span>
            </div>
            <div class="total-line total-ttc">
                <span>Total TTC:</span>
                <span>{{ totalTTC.toFixed(2) }}€</span>
            </div>
        </div>

        <!-- Zone de signature -->
        <!-- Zone de signature -->
        <div class="signature-section">
            <h2>Signature</h2>
            <div class="signature-pad-container">
                <canvas 
                    ref="signaturePad"
                    class="signature-pad"
                ></canvas>
                <div class="signature-actions">
                    <button 
                        type="button" 
                        class="clear-button"
                        @click="clearSignature"
                    >
                        Effacer
                    </button>
                </div>
            </div>
        </div>

        <!-- Bouton de confirmation -->
        <div class="actions">
            <button 
                class="confirm-button"
                @click="submitOrder"
            >
                Confirmer le bon de commande
                <i class="fas fa-check"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import SignaturePad from 'signature_pad';

const props = defineProps({
    clientData: {
        type: Object,
        required: true
    },
    selectedProducts: {
        type: Array,
        required: true
    },
    devis: {
        type: Object,
        default: null
    },
    isEditing: {
        type: Boolean,
        default: false
    },
    signatureUrl: {
        type: String,
        default: null
    }
});



const signaturePad = ref(null);
const signaturePadInstance = ref(null);
const hasSignature = ref(false);

onMounted(() => {
    const canvas = signaturePad.value;
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    signaturePadInstance.value = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        onEnd: () => {
            // Mettre à jour hasSignature quand l'utilisateur termine de signer
            hasSignature.value = true;
        }
    });
    if (props.isEditing && props.devis && props.devis.signature_path) {
    hasSignature.value = true;
    // On peut ajouter un message ou un placeholder indiquant qu'une signature existe déjà
  }
});

const clearSignature = () => {
    if (signaturePadInstance.value) {
        signaturePadInstance.value.clear();
        hasSignature.value = false;
    }
};

const emit = defineEmits(['submit-order']);

const getMesureUnit = (type) => {
    switch(type) {
        case 'carre': return 'm²';
        case 'metre': return 'm';
        default: return 'unité(s)';
    }
};

const totalHT = computed(() => {
    return props.selectedProducts.reduce((total, item) => {
        return total + (item.prix * item.mesure);
    }, 0);
});

const totalTVA = computed(() => {
    return totalHT.value * (props.clientData.valeur_tva / 100);
});

const totalTTC = computed(() => {
    return totalHT.value + totalTVA.value;
});

const submitOrder = () => {
  // Si c'est en édition et qu'il y a une signature existante et qu'aucune nouvelle n'a été ajoutée
  let signatureData = null;
  
  if (props.isEditing && props.devis && props.devis.signature_path) {
    if (signaturePadInstance.value?.isEmpty()) {
      // Garder la signature existante
      signatureData = 'unchanged';
    } else {
      // Utiliser la nouvelle signature
      signatureData = signaturePadInstance.value.toDataURL();
    }
  } else if (signaturePadInstance.value?.isEmpty()) {
    // Pas de signature, mais on permet quand même la soumission
    signatureData = null; // Ou une valeur par défaut si nécessaire
  } else {
    // Nouvelle signature
    signatureData = signaturePadInstance.value.toDataURL();
  }
  
  emit('submit-order', {
    total_ht: totalHT.value,
    total_tva: totalTVA.value,
    total_ttc: totalTTC.value,
    signature_data: signatureData,
    signature_date: new Date().toISOString()
  });
};
</script>

<style scoped>
.summary-container {
    background-color: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.section h2 {
    margin-bottom: 1rem;
    color: #333;
}

.client-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.info-group strong {
    color: #666;
}

.products-list {
    display: grid;
    gap: 1.5rem;
}

.product-card {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.product-image {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 4px;
}

.product-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.product-details h3 {
    margin: 0;
    color: #333;
}

.description {
    color: #666;
    font-size: 0.9rem;
    margin: 0;
}

.specs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.5rem;
    margin-top: 0.5rem;
}

.totals-section {
    margin: 2rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.total-line {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
}

.total-ttc {
    font-weight: bold;
    font-size: 1.2rem;
    border-top: 1px solid #ddd;
    margin-top: 0.5rem;
    padding-top: 1rem;
}

.signature-section {
    margin: 2rem 0;
}

.signature-placeholder {
    height: 200px;
    background: #f8f9fa;
    border: 2px dashed #ddd;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 2rem;
}

.confirm-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

.confirm-button:hover {
    background-color: #45a049;
}

@media (max-width: 768px) {
    .product-card {
        grid-template-columns: 1fr;
    }

    .product-image {
        width: 100%;
        height: 200px;
    }
}

.signature-pad-container {
    width: 100%;
    margin: 1rem 0;
}

.signature-pad {
    width: 100%;
    height: 200px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: white;
    touch-action: none;
}

.signature-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 0.5rem;
}

.clear-button {
    padding: 0.5rem 1rem;
    background-color: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.clear-button:hover {
    background-color: #c82333;
}

.confirm-button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
</style>