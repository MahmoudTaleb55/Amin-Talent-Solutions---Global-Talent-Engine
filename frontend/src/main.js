import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import './assets/styles/main.css';
import './assets/styles/toast.css';

const app = createApp(App);

app.use(router);
app.use(Toast, {
  position: 'top-center',
  timeout: 4000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: true,
  closeButton: 'button',
  icon: true,
  rtl: false,
  transition: 'Vue-Toastification__bounce',
  maxToasts: 5,
  newestOnTop: true,
  toastDefaults: {
    success: {
      className: 'toast-success',
      icon: '✓',
    },
    error: {
      className: 'toast-error',
      icon: '✕',
    },
    info: {
      className: 'toast-info',
      icon: 'ℹ',
    },
    warning: {
      className: 'toast-warning',
      icon: '⚠',
    },
  },
});


app.mount('#app');
