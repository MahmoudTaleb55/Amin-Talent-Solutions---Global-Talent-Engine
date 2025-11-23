<template>
  <div class="min-h-screen bg-secondary-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="text-center">
        <img src="/assets/logo.png" alt="Amin Talent Solutions" class="mx-auto h-16 w-auto object-contain" />
        <h2 class="mt-6 text-3xl font-bold text-secondary-900">
          Sign in to your account
        </h2>
        <p class="mt-2 text-sm text-secondary-600">
          Welcome back to Amin Talent Solutions
        </p>
      </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow-sm border border-secondary-200 sm:rounded-lg sm:px-10">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label for="email" class="block text-sm font-medium text-secondary-900">
              Email address
            </label>
                <div class="mt-1 relative">
                  <BaseInput id="email" type="email" placeholder="Enter your email" v-model="email" :disabled="isLoading" />
                </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-secondary-900">
              Password
            </label>
            <div class="mt-1 relative">
              <BaseInput id="password" type="password" placeholder="Enter your password" v-model="password" :disabled="isLoading" />
            </div>
          </div>

          <div>
            <BaseButton type="submit" variant="primary" class="w-full" :loading="isLoading">{{ isLoading ? 'Signing in...' : 'Sign in' }}</BaseButton>
          </div>

          <div class="text-center">
            <p class="text-sm text-secondary-600">
              Don't have an account?
              <router-link to="/register" class="font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                Create one now
              </router-link>
            </p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import api from '@/services/api';
import BaseInput from '@/components/ui/BaseInput.vue';
import BaseButton from '@/components/ui/BaseButton.vue';

export default {
  name: 'UserLogin',
  components: { BaseInput, BaseButton },
  setup() {
    const toast = useToast();
    return { toast };
  },
  data() {
    return {
      email: '',
      password: '',
      isLoading: false
    };
  },
  methods: {
    async handleLogin() {
      // Validation
      if (!this.email || !this.password) {
        this.toast.error('❌ Please enter both email and password');
        return;
      }

      if (!this.isValidEmail(this.email)) {
        this.toast.error('❌ Please enter a valid email address');
        return;
      }

      this.isLoading = true;

      try {
        const response = await api.login({
          email: this.email,
          password: this.password
        });

        const user = response.data.user;
        const token = response.data.access_token;

        if (!token) {
          this.toast.error('❌ Login failed: No authentication token received');
          return;
        }

        // Safety check for user roles
        const userRole = user.roles?.[0]?.name?.toLowerCase().trim();

        if (!userRole) {
          this.toast.error('❌ Error: User role not found. Please contact administrator.');
          return;
        }

        // Store authentication data
        localStorage.setItem('authToken', token);
        localStorage.setItem('userRole', userRole);
        localStorage.setItem('userEmail', user.email);

        // Emit event for Header to update
        this.$root.$emit('auth-changed');

        // Show success message
        this.toast.success(`✅ Welcome back, ${user.name}! Redirecting...`);

        // Role-based redirect with slight delay for UX
        setTimeout(() => {
          const roleRoute = this.getRoleRoute(userRole);
          this.$router.push(`/dashboard/${roleRoute}`);
        }, 1000);

      } catch (err) {
        console.error('Login error:', err);
        
        // Handle different error scenarios
        if (err.response?.status === 422) {
          this.toast.error('❌ Invalid email or password');
        } else if (err.response?.status === 401) {
          this.toast.error('❌ Invalid credentials. Please try again.');
        } else if (err.response?.status === 500) {
          this.toast.error('❌ Server error. Please try again later.');
        } else if (err.message === 'Network Error') {
          this.toast.error('❌ Network error. Please check your connection.');
        } else {
          this.toast.error('❌ Login failed: ' + (err.response?.data?.message || err.message || 'Please try again.'));
        }
      } finally {
        this.isLoading = false;
      }
    },
    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    getRoleRoute(role) {
      const roleMap = {
        'admin': 'admin',
        'ceo': 'ceo',
        'company': 'company',
        'freelancer': 'freelancer',
        'project-manager': 'project-manager'
      };
      return roleMap[role] || 'freelancer'; // Default to freelancer if role not found
    }
  }
};
</script>

<style scoped>
/* Professional styling for enterprise platforms */
</style>
