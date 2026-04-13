import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';

// --- ZIGGY & ROUTE FUNCTION SETUP ---
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// Note: The original 'route' function from Ziggy is imported but
// we will rely on the global mixin for template usage.

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
    return pages[`./Pages/${name}.vue`];
  },
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) });

    // Use the Inertia plugin
    app.use(plugin);

    // Use the ZiggyVue plugin, which makes the route() function available
    app.use(ZiggyVue);

    // Mount the app to the DOM
    app.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
