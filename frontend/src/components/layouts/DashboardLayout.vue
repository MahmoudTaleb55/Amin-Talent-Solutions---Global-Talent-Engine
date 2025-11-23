<template>
  <div class="flex h-screen bg-secondary-50">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-large transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-secondary-200',
        { '-translate-x-full': !sidebarOpen }
      ]"
    >
      <!-- Header -->
      <div class="flex items-center justify-center h-20 px-4 bg-gradient-primary relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-primary opacity-90"></div>
        <div class="relative z-10 flex items-center space-x-3">
          <img src="/assets/logo.png" alt="Amin Talent Solutions" class="h-10 w-auto object-contain" />
          <h1 class="text-xl font-bold text-white">Amin Talent</h1>
        </div>
      </div>

      <!-- User Info -->
  <div class="px-4 py-4 border-b border-secondary-200 bg-secondary-50">
        <div class="flex items-center space-x-3">
          <div class="h-10 w-10 bg-primary-100 rounded-full flex items-center justify-center">
            <svg class="h-5 w-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-secondary-900 truncate">{{ userEmail || 'User' }}</p>
            <p class="text-xs text-secondary-500 capitalize">{{ capitalizeRole(userRole) }}</p>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="mt-6 px-4">
        <div class="space-y-2">
          <!-- Dashboard Link -->
          <router-link
            :to="`/dashboard/${getRoleRoute(userRole)}`"
            class="nav-link group"
            active-class="nav-link-active"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3 text-secondary-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z" />
              </svg>
              <span>Dashboard</span>
            </div>
          </router-link>

          <!-- Profile Link -->
          <router-link to="/profile" class="nav-link group">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-3 text-secondary-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span>Profile</span>
            </div>
          </router-link>

          <!-- Role-specific navigation -->
          <div v-if="userRole === 'admin'" class="mt-8">
            <h3 class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider mb-2">Admin Tools</h3>
            <div class="space-y-1">
              <router-link to="/dashboard/admin/users" class="nav-link group">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-3 text-secondary-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                  <span>User Management</span>
                </div>
              </router-link>
              <router-link to="/dashboard/admin/roles" class="nav-link group">
                <div class="flex items-center">
                  <svg class="w-5 h-5 mr-3 text-secondary-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                  </svg>
                  <span>Role Management</span>
                </div>
              </router-link>
            </div>
          </div>

          <!-- Logout Button -->
          <div class="mt-8 pt-4 border-t border-secondary-200">
            <button @click="handleLogout" class="nav-link group w-full text-left">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-secondary-400 group-hover:text-danger-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span>Logout</span>
              </div>
            </button>
          </div>
        </div>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Top Bar -->
      <header class="bg-white shadow-soft border-b border-secondary-200 px-6 py-4">
        <div class="flex items-center justify-between">
          <button
            @click="sidebarOpen = !sidebarOpen"
            class="lg:hidden p-2 rounded-lg text-secondary-500 hover:text-secondary-700 hover:bg-secondary-100 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <div class="flex-1 lg:ml-0 ml-4">
            <h2 class="text-2xl font-bold text-secondary-900">{{ capitalizeRole(userRole) }} Dashboard</h2>
          </div>
          <div class="flex items-center space-x-4">
            <div class="text-right">
              <p class="text-sm font-medium text-secondary-900">{{ userEmail || 'User' }}</p>
              <p class="text-xs text-secondary-500">{{ new Date().toLocaleDateString() }}</p>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto bg-secondary-50 p-6">
        <router-view />
      </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-40 bg-secondary-600 bg-opacity-75 lg:hidden"
    ></div>
  </div>
</template>


<script>
import { useToast } from 'vue-toastification';

export default {
  name: 'DashboardLayout',
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      sidebarOpen: false,
      userRole: localStorage.getItem('userRole') || 'user',
      userEmail: localStorage.getItem('userEmail') || null
    };
  },
  mounted() {
    // Close sidebar on route change for mobile
    this.$router.afterEach(() => {
      this.sidebarOpen = false;
    });
  },
  methods: {
    getRoleRoute(role) {
      const roleMap = {
        'admin': 'admin',
        'ceo': 'ceo',
        'company': 'company',
        'freelancer': 'freelancer',
        'project-manager': 'project-manager'
      };
      return roleMap[role] || 'freelancer';
    },
    capitalizeRole(role) {
      if (!role) return 'User';
      return role.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    },
    async handleLogout() {
      try {
        // Clear local storage
        localStorage.removeItem('authToken');
        localStorage.removeItem('userRole');
        localStorage.removeItem('userEmail');

        // Show success message
        this.toast.success('Logged out successfully');

        // Redirect to login
        this.$router.push('/login');
      } catch (error) {
        console.error('Logout error:', error);
        this.toast.error('Error during logout');
      }
    }
  }
};
</script>


<style scoped>
  .nav-link {
  @apply flex items-center px-3 py-3 text-sm font-medium text-secondary-600 rounded-xl hover:bg-primary-50 hover:text-primary-700 transition-all duration-200;
}

.nav-link-active {
  @apply bg-primary-100 text-primary-700 border-r-4 border-primary-500 shadow-soft;
}

.nav-link-active svg {
  @apply text-primary-600;
}
</style>
