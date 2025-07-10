<template>
  <div class="search-container position-relative">
    <div class="input-group">
      <input 
        type="text" 
        v-model="searchQuery"
        @input="onSearchInput"
        @focus="showResults = true"
        @blur="onBlur"
        class="form-control"
        :class="{ 'is-invalid': hasError }"
        placeholder="Buscar por ID (#123), email o nombre..."
        aria-label="Buscar usuarios"
        autocomplete="off"
      >
      <button 
        class="btn btn-outline-secondary" 
        type="button"
        @click="executeSearch"
      >
        <i class="fas fa-search"></i>
      </button>
      <button 
        v-if="searchQuery" 
        class="btn btn-outline-danger" 
        type="button"
        @click="clearSearch"
        title="Limpiar búsqueda"
      >
        <i class="fas fa-times"></i>
      </button>
    </div>
    
    <!-- Resultados de la búsqueda -->
    <div 
      v-if="showResults && searchResults.length > 0" 
      class="search-results dropdown-menu show"
      :class="{ 'w-100': true }"
      style="display: block;"
    >
      <div 
        v-for="user in searchResults" 
        :key="user.id"
        class="dropdown-item d-flex align-items-center py-2"
        @mousedown="selectUser(user)"
      >
        <img 
          :src="user.profile_photo_url" 
          :alt="user.name"
          class="rounded-circle me-2"
          width="32"
          height="32"
        >
        <div>
          <div class="fw-bold">{{ user.name }}</div>
          <small class="text-muted">{{ user.email }}</small>
        </div>
      </div>
    </div>
    
    <div 
      v-if="showResults && searchQuery && searchResults.length === 0 && !isLoading"
      class="search-results dropdown-menu show"
      style="display: block;"
    >
      <div class="dropdown-item text-muted">
        No se encontraron resultados
      </div>
    </div>
    
    <div 
      v-if="hasError" 
      class="invalid-feedback d-block"
    >
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

export default {
  name: 'SearchUser',
  
  setup() {
    const searchQuery = ref('');
    const searchResults = ref([]);
    const showResults = ref(false);
    const isLoading = ref(false);
    const hasError = ref(false);
    const errorMessage = ref('');
    let searchTimeout = null;

    const onSearchInput = () => {
      // Limpiar el timeout anterior
      clearTimeout(searchTimeout);
      
      // Si el campo está vacío, limpiar resultados
      if (!searchQuery.value.trim()) {
        searchResults.value = [];
        showResults.value = false;
        return;
      }
      
      // Validar longitud mínima
      if (searchQuery.value.trim().length < 2) {
        return;
      }
      
      isLoading.value = true;
      hasError.value = false;
      
      // Configurar un nuevo timeout
      searchTimeout = setTimeout(() => {
        performSearch();
      }, 300);
    };

    const performSearch = async () => {
      try {
        const response = await axios.get('/search/users', {
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
        console.error('Error al buscar usuarios:', error);
        hasError.value = true;
        errorMessage.value = 'Ocurrió un error al realizar la búsqueda: ' + (error.response?.data?.message || error.message);
        searchResults.value = [];
      } finally {
        isLoading.value = false;
      }
    };

    const selectUser = (user) => {
      // Navegar al perfil del usuario seleccionado
      if (user.show_url) {
        window.location.href = user.show_url;
      }
    };

    const clearSearch = () => {
      searchQuery.value = '';
      searchResults.value = [];
      showResults.value = false;
      hasError.value = false;
      errorMessage.value = '';
    };

    const onBlur = () => {
      // Pequeño retraso para permitir la selección de un elemento
      setTimeout(() => {
        showResults.value = false;
      }, 200);
    };

    const executeSearch = () => {
      if (searchQuery.value.trim()) {
        performSearch();
      }
    };

    // Limpiar el timeout cuando el componente se desmonte
    onMounted(() => {
      return () => {
        if (searchTimeout) {
          clearTimeout(searchTimeout);
        }
      };
    });

    return {
      searchQuery,
      searchResults,
      showResults,
      isLoading,
      hasError,
      errorMessage,
      onSearchInput,
      performSearch,
      selectUser,
      clearSearch,
      onBlur,
      executeSearch
    };
  },
  
  methods: {
    onSearchInput() {
      // Limpiar el timeout anterior
      clearTimeout(this.searchTimeout);
      
      // Si el campo está vacío, limpiar resultados
      if (!this.searchQuery.trim()) {
        this.searchResults = [];
        this.showResults = false;
        return;
      }
      
      // Validar longitud mínima
      if (this.searchQuery.trim().length < 2) {
        return;
      }
      
      this.isLoading = true;
      this.hasError = false;
      
      // Configurar un nuevo timeout
      this.searchTimeout = setTimeout(() => {
        this.performSearch();
      }, 300);
    },
    
    async performSearch() {
      try {
        const response = await axios.get('/search/users', {
          params: { query: this.searchQuery }
        });
        
        this.searchResults = response.data || [];
        this.showResults = true;
      } catch (error) {
        console.error('Error al buscar usuarios:', error);
        this.hasError = true;
        this.errorMessage = 'Ocurrió un error al realizar la búsqueda';
      } finally {
        this.isLoading = false;
      }
    },
    
    selectUser(user) {
      // Navegar al perfil del usuario seleccionado
      window.location.href = user.show_url;
    },
    
    clearSearch() {
      this.searchQuery = '';
      this.searchResults = [];
      this.showResults = false;
      this.hasError = false;
    },
    
    onBlur() {
      // Pequeño retraso para permitir la selección de un elemento
      setTimeout(() => {
        this.showResults = false;
      }, 200);
    },
    
    executeSearch() {
      if (this.searchQuery.trim()) {
        this.performSearch();
      }
    }
  }
};
</script>

<style scoped>
.search-container {
  position: relative;
  min-width: 300px;
}

.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  z-index: 1000;
  max-height: 300px;
  overflow-y: auto;
  margin-top: 2px;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
}

.dropdown-item {
  cursor: pointer;
  transition: background-color 0.2s;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

.dropdown-item img {
  object-fit: cover;
}
</style>
