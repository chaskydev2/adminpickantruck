<template>
  <div class="position-relative">
    <div class="input-group">
      <input
        type="text"
        class="form-control form-control-sm"
        :class="{ 'rounded-end': !searchQuery }"
        v-model="searchQuery"
        @input="onSearchInput"
        @focus="showResults = true"
        @blur="onBlur"
        :placeholder="placeholder"
        autocomplete="off"
      >
      <button 
        v-if="searchQuery" 
        @click="clearSearch"
        class="btn btn-outline-secondary border-start-0"
        type="button"
      >
        <i class="fas fa-times"></i>
      </button>
      <button 
        class="btn btn-outline-secondary" 
        :class="{ 'rounded-start-0': searchQuery }"
        type="button"
        @click="performSearch"
      >
        <i class="fas fa-search"></i>
      </button>
    </div>

    <!-- Resultados de la búsqueda -->
    <div 
      v-if="showResults && searchResults.length > 0" 
      class="search-results dropdown-menu show w-100"
      style="position: absolute; z-index: 1000; max-height: 400px; overflow-y: auto;"
    >
      <div 
        v-for="bid in searchResults" 
        :key="bid.id"
        class="dropdown-item p-3 border-bottom"
        @mousedown="selectBid(bid)"
      >
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h6 class="mb-1">
              Oferta #{{ bid.id }}
              <span class="badge ms-2" :class="getStatusBadgeClass(bid.estado)">
                {{ formatStatus(bid.estado) }}
              </span>
            </h6>
            <p class="mb-1 small">
              <i class="fas fa-user me-1"></i> 
              <a :href="bid.postor_url" class="text-decoration-none" @click.prevent="$inertia.visit(bid.postor_url)">
                {{ bid.postor_name }}
              </a>
              <i class="fas fa-arrow-right mx-2 text-muted"></i>
              <i class="fas fa-user-tie me-1"></i>
              <a :href="bid.propietario_url" class="text-decoration-none" @click.prevent="$inertia.visit(bid.propietario_url)">
                {{ bid.propietario_name }}
              </a>
            </p>
            <p class="mb-1 small">
              <i class="fas" :class="bid.tipo === 'Ruta' ? 'fa-route' : 'fa-box'"></i>
              {{ bid.tipo }}
              <span class="mx-2">•</span>
              <i class="fas fa-dollar-sign"></i>
              {{ formatCurrency(bid.monto) }}
              <span class="mx-2">•</span>
              <i class="far fa-calendar-alt"></i>
              {{ bid.fecha }}
            </p>
          </div>
          <div class="d-flex flex-column align-items-end">
            <a 
              :href="bid.show_url" 
              class="btn btn-sm btn-outline-primary"
              @click.prevent="$inertia.visit(bid.show_url)"
            >
              Ver
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensaje de error -->
    <div 
      v-if="errorMessage" 
      class="alert alert-danger mt-2 mb-0"
    >
      {{ errorMessage }}
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
  placeholder: {
    type: String,
    default: 'Buscar ofertas por ID, postor, propietario, monto o estado...'
  },
  modelValue: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue', 'select']);

const searchQuery = ref('');
const searchResults = ref([]);
const showResults = ref(false);
const isLoading = ref(false);
const errorMessage = ref('');

// Formatear moneda
const formatCurrency = (value) => {
  return new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
    minimumFractionDigits: 2
  }).format(value);
};

// Formatear estado
const formatStatus = (status) => {
  const statusMap = {
    'pendiente': 'Pendiente',
    'aceptado': 'Aceptado',
    'rechazado': 'Rechazado',
    'cancelado': 'Cancelado'
  };
  return statusMap[status] || status;
};

// Obtener clase CSS para el badge de estado
const getStatusBadgeClass = (status) => {
  return {
    'bg-warning text-dark': status === 'pendiente',
    'bg-success': status === 'aceptado',
    'bg-danger': status === 'rechazado' || status === 'cancelado'
  };
};

// Manejar entrada de búsqueda
const onSearchInput = () => {
  emit('update:modelValue', searchQuery.value);
  if (searchQuery.value.length > 1) {
    performSearch();
  } else {
    searchResults.value = [];
  }
};

// Realizar búsqueda
const performSearch = async () => {
  if (!searchQuery.value || searchQuery.value.length < 2) {
    searchResults.value = [];
    return;
  }

  isLoading.value = true;
  errorMessage.value = '';

  try {
    const response = await axios.get('/search/bids', {
      params: { query: searchQuery.value },
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    });
    
    if (response.data && response.data.success && Array.isArray(response.data.data)) {
      searchResults.value = response.data.data;
      showResults.value = true;
    } else {
      throw new Error(response.data?.message || 'Formato de respuesta inesperado');
    }
  } catch (error) {
    console.error('Error al buscar ofertas:', error);
    errorMessage.value = 'Error al realizar la búsqueda. Por favor, intente nuevamente.';
    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message;
    }
  } finally {
    isLoading.value = false;
  }
};

// Seleccionar una oferta
const selectBid = (bid) => {
  searchQuery.value = `Oferta #${bid.id} - ${bid.postor_name} (${formatCurrency(bid.monto)})`;
  showResults.value = false;
  emit('select', bid);
};

// Limpiar búsqueda
const clearSearch = () => {
  searchQuery.value = '';
  searchResults.value = [];
  errorMessage.value = '';
  emit('update:modelValue', '');
};

// Manejar evento blur del input
const onBlur = () => {
  // Pequeño retraso para permitir la selección de un elemento
  setTimeout(() => {
    showResults.value = false;
  }, 200);
};

// Observar cambios en el valor del modelo
watch(() => props.modelValue, (newValue) => {
  if (newValue !== searchQuery.value) {
    searchQuery.value = newValue;
  }
});
</script>

<style scoped>
.search-results {
  display: block;
  margin-top: 0.125rem;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.175);
  border-radius: 0.375rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item {
  cursor: pointer;
  transition: background-color 0.15s ease-in-out;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

.dropdown-item:active {
  background-color: #e9ecef;
}

.form-control:focus {
  border-color: #86b7fe;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.badge {
  font-size: 0.75rem;
  font-weight: 500;
  padding: 0.35em 0.65em;
}

.small {
  font-size: 0.875em;
}
</style>
