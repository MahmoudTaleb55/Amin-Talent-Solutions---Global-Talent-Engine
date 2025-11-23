<template>
  <header class="w-full bg-white border-b border-secondary-100 transition-colors">
    <div class="container flex items-center justify-between h-16">
      <router-link to="/dashboard" class="flex items-center space-x-2">
        <img src="/assets/logo.png" alt="Amin Talent Solutions" class="h-10 w-auto object-contain" />
        <div class="hidden sm:block">
          <div class="text-sm font-bold text-secondary-900">Amin Talent</div>
          <div class="text-xs text-secondary-500">Solutions</div>
        </div>
      </router-link>

      <nav class="flex items-center space-x-4">
        <!-- Public Navigation (not logged in) -->
        <template v-if="!isAuthenticated">
          <router-link to="/login" class="text-sm text-secondary-700 hover:text-primary-600">Login</router-link>
          <router-link to="/register" class="ml-2 btn btn-primary btn-sm">Sign up</router-link>
        </template>

        <!-- Authenticated Navigation -->
        <template v-else>
          <div class="flex items-center space-x-3">
            <!-- User Role Display -->
            <span class="text-sm text-secondary-700 capitalize bg-secondary-100 px-3 py-1 rounded-full">{{ userRole }}</span>
            
            <!-- Profile Link -->
            <router-link to="/profile" class="text-sm text-secondary-700 hover:text-primary-600">Profile</router-link>
            
            <!-- Logout Button -->
            <button @click="handleLogout" class="text-sm btn btn-secondary btn-sm">Logout</button>
          </div>
        </template>
      </nav>
    </div>
  </header>
</template>

<script>
import { useToast } from 'vue-toastification';
import api from '@/services/api';

export default {
  name: 'AppHeader',
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      isAuthenticated: false,
      userRole: ''
    };
  },
  mounted() {
    this.checkAuthentication();
    // Listen for auth changes
    this.$root.$on('auth-changed', this.checkAuthentication);
  },
  beforeUnmount() {
    this.$root.$off('auth-changed', this.checkAuthentication);
  },
  methods: {
    checkAuthentication() {
      const token = localStorage.getItem('authToken');
      const role = localStorage.getItem('userRole');
      this.isAuthenticated = !!token;
      this.userRole = role || '';
    },
    async handleLogout() {
      try {
        await api.logout();
        localStorage.removeItem('authToken');
        localStorage.removeItem('userRole');
        this.isAuthenticated = false;
        this.userRole = '';
        this.toast.success('Logged out successfully');
        this.$router.push('/login');
      } catch (err) {
        this.toast.error('Logout failed');
      }
    }
  }
};
</script>

<style scoped>
.container { max-width: 1200px; margin: 0 auto; padding: 0 1rem; }
</style>
