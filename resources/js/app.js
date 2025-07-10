import './bootstrap';
import { createApp } from 'vue';
import SearchUser from './components/SearchUser.vue';
import SearchCarga from './components/SearchCarga.vue';
import SearchRuta from './components/SearchRuta.vue';
import SearchDocument from './components/SearchDocument.vue';
import SearchBid from './components/SearchBid.vue';
import axios from 'axios';

// Configurar axios globalmente
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Crear la aplicación Vue
const app = createApp({
    // Opciones de la aplicación
});

// Hacer que axios esté disponible en toda la aplicación
app.config.globalProperties.$http = axios;

// Registrar componentes globalmente
app.component('search-user', SearchUser);
app.component('search-carga', SearchCarga);
app.component('search-ruta', SearchRuta);
app.component('search-document', SearchDocument);
app.component('search-bid', SearchBid);

// Montar la aplicación en los elementos correspondientes
const mountApp = (selector, componentName) => {
    const element = document.getElementById(selector);
    if (element) {
        const app = createApp({});
        let component;
        
        switch(componentName) {
            case 'search-user':
                component = SearchUser;
                break;
            case 'search-carga':
                component = SearchCarga;
                break;
            case 'search-ruta':
                component = SearchRuta;
                break;
            case 'search-document':
                component = SearchDocument;
                break;
            case 'search-bid':
                component = SearchBid;
                break;
            case 'global-search':
                component = GlobalSearch;
                break;
            default:
                console.error('Componente no reconocido:', componentName);
                return;
        }
        
        app.component(componentName, component);
        app.mount(`#${selector}`);
    }
};

// Montar componentes en sus respectivos contenedores
const mountComponents = () => {
  mountApp('search-user-component', 'search-user');
  mountApp('search-carga-component', 'search-carga');
  mountApp('search-ruta-component', 'search-ruta');
  mountApp('search-document-component', 'search-document');
  mountApp('search-bid-component', 'search-bid');
  mountApp('global-search-component', 'global-search');
};

// Inicializar componentes cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mountComponents);
} else {
  mountComponents();
}
