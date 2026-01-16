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
        placeholder="Buscar en toda la aplicación..."
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
        v-for="item in searchResults" 
        :key="item.id"
        class="dropdown-item d-flex align-items-center py-2"
        @mousedown="selectItem(item)"
      >
        <i :class="item.icon + ' me-2'"></i>
        <div>
          <div class="fw-bold">{{ item.title }}</div>
          <small class="text-muted">{{ item.subtitle }}</small>
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
  </div>
</template>

<script>
import _ from 'lodash';

export default {
  name: 'GlobalSearch',
  
  data() {
    return {
      searchQuery: '',
      searchResults: [],
      showResults: false,
      isLoading: false,
      searchTimeout: null
    };
  },
  
  methods: {
    onSearchInput() {
      clearTimeout(this.searchTimeout);
      
      if (!this.searchQuery.trim()) {
        this.searchResults = [];
        return;
      }
      
      this.searchTimeout = setTimeout(() => {
        this.performSearch();
      }, 300);
    },
    
    performSearch() {
      if (!this.searchQuery.trim()) {
        this.searchResults = [];
        return;
      }

      this.isLoading = true;
      
      axios.get('/search/global', {
        params: { query: this.searchQuery }
      })
      .then(response => {
        this.searchResults = response.data;
      })
      .catch(error => {
        console.error('Error en la búsqueda:', error);
        this.searchResults = [];
      })
      .finally(() => {
        this.isLoading = false;
      });
    },
    
    executeSearch() {
      clearTimeout(this.searchTimeout);
      this.performSearch();
    },

    selectItem(item) {
      if (item && item.url) {
        window.location.href = item.url;
      }
    },

    clearSearch() {
      this.searchQuery = '';
      this.searchResults = [];
      this.showResults = false;
    },

    onBlur() {
      setTimeout(() => {
        this.showResults = false;
      }, 200);
    }
  }
};
</script>

<style scoped>
.search-results {
  position: absolute;
  width: 100%;
  z-index: 1000;
  max-height: 400px;
  overflow-y: auto;
  margin-top: 2px;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 0.25rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.search-results .dropdown-item {
  white-space: normal;
  padding: 0.5rem 1rem;
}

.search-results .dropdown-item:hover {
  background-color: #f8f9fa;
  cursor: pointer;
}

.search-results .dropdown-item:active {
  background-color: #e9ecef;
}

.fa-route { color: #36b9cc; }
.fa-gavel { color: #f6c23e; }
.fa-file-alt { color: #858796; }
</style>
