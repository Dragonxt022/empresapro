import './bootstrap';
import '../css/app.css';

import '@fortawesome/fontawesome-free/css/all.css';
import '@fortawesome/fontawesome-free/js/all.js';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

import NotifyPlugin from './Plugins/Notify';

const pinia = createPinia();
const appName = import.meta.env.VITE_APP_NAME || 'Provendas';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue')
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props, plugin) })
      .use(plugin)
      .use(ZiggyVue)
      .use(Toast, { position: 'top-center', timeout: 3000 })
      .use(NotifyPlugin)
      .use(pinia)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
    showSpinner: true,
  },
});
