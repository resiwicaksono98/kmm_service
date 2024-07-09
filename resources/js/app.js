import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import VCalendar from 'v-calendar';
import 'v-calendar/style.css';

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(Toast)
      .use(VCalendar, {})
      .component("EasyDataTable",Vue3EasyDataTable)
      .mount(el)
  },
})
