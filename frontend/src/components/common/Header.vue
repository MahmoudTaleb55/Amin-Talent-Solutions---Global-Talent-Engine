<template>
  <header class="w-full bg-white border-b border-secondary-100 transition-colors">
    <div class="container flex items-center justify-between h-16">
      <router-link to="/dashboard" class="flex items-center space-x-2">
        <!-- Minimal brand: no purple logo or 'Amin Talent' text per request -->
        <div class="text-sm font-semibold text-secondary-900">Talent Engine</div>
      </router-link>

      <nav class="flex items-center space-x-4">
        <!-- Public Navigation (not logged in) -->
        <template v-if="!isAuthenticated">
          <router-link to="/login" class="text-sm text-secondary-700 hover:text-primary-600">Login</router-link>
          <router-link to="/register" class="ml-2 btn btn-primary btn-sm">Sign up</router-link>
        </template>

        <!-- Authenticated Navigation: show avatar + dropdown -->
        <template v-else>
          <div class="relative">
            <button @click="toggleDropdown" class="flex items-center gap-2 focus:outline-none">
              <img :src="avatarSrc" alt="Profile" class="h-9 w-9 rounded-full object-cover border" />
            </button>
            <div v-if="dropdownOpen" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg py-2 z-50">
              <router-link to="/profile" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">Profile</router-link>
              <router-link to="/dashboard" class="block px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">Dashboard</router-link>
              <button @click="handleLogout" class="w-full text-left px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-50">Logout</button>
            </div>
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
      ,dropdownOpen: false,
      avatarSrc: '/assets/default-avatar.png'
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
      // load avatar from cached profile if present
      const profile = localStorage.getItem('userProfile');
      if (profile) {
        try {
          const p = JSON.parse(profile);
          this.avatarSrc = p.avatar || '/assets/default-avatar.png';
        } catch (e) {
          this.avatarSrc = '/assets/default-avatar.png';
        }
      } else {
        this.avatarSrc = '/assets/default-avatar.png';
      }
    },
    toggleDropdown() {
      this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
      this.dropdownOpen = false;
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
