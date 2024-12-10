import './bootstrap';
import '../css/app.css';
import '../css/AdminLTE/adminlte.css';
import '../js/AdminLTE/jquery.js'
import '../js/AdminLTE/adminlteLTE.js'
import '../js/AdminLTE/bootstrap461.js'


import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';


const appName = import.meta.env.VITE_APP_NAME || 'ProVendas';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',

    },
});
