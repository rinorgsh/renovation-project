import './bootstrap';
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Import Bootstrap et jQuery
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.css';
import '@fortawesome/fontawesome-free/css/all.css'
import jQuery from 'jquery';

window.$ = jQuery;

// Importation de Ziggy
import { Ziggy } from './ziggy';

createInertiaApp({
    title: (title) => `${title} - Renovation`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        app.mount(el);

        // Ajouter Ziggy globalement
        app.config.globalProperties.$route = route;
    },
});