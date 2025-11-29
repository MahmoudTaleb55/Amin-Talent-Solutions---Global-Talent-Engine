<template>
  <div class="min-h-screen bg-secondary-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
      <div class="text-center">
        <img src="/assets/logo.png" alt="Amin Talent Solutions" class="mx-auto h-16 w-auto object-contain" />
        <h2 class="mt-6 text-3xl font-bold text-secondary-900">
          Create your account
        </h2>
        <p class="mt-2 text-sm text-secondary-600">
          Join Amin Talent Solutions today
        </p>
      </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
      <div class="bg-white py-8 px-4 shadow-sm border border-secondary-200 sm:rounded-lg sm:px-10">
        <form @submit.prevent="register" class="space-y-6">
          <div>
            <label for="name" class="block text-sm font-medium text-secondary-900">
              Full name
            </label>
            <div class="mt-1">
              <BaseInput id="name" placeholder="Enter your full name" v-model="form.name" :disabled="loading" />
            </div>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-secondary-900">
              Email address
            </label>
            <div class="mt-1">
              <BaseInput id="email" type="email" placeholder="Enter your email" v-model="form.email" :disabled="loading" />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-secondary-900">
              Password
            </label>
            <div class="mt-1">
              <BaseInput id="password" type="password" placeholder="Create a password" v-model="form.password" :disabled="loading" />
            </div>
          </div>

          <div>
            <label for="password_confirmation" class="block text-sm font-medium text-secondary-900">
              Confirm password
            </label>
            <div class="mt-1">
              <BaseInput id="password_confirmation" type="password" placeholder="Confirm your password" v-model="form.password_confirmation" :disabled="loading" />
            </div>
          </div>

          <div>
            <label for="role" class="block text-sm font-medium text-secondary-900">
              Account type
            </label>
            <div class="mt-1">
              <BaseInput id="role" tag="select" v-model="form.role" :disabled="loading">
                <option value="">Select account type</option>
                <option value="company">Company</option>
                <option value="freelancer">Freelancer</option>
              </BaseInput>
            </div>
          </div>

          <div>
            <BaseButton type="submit" variant="primary" class="w-full" :loading="loading">{{ loading ? 'Creating account...' : 'Create account' }}</BaseButton>
          </div>

          <div class="text-center">
            <p class="text-sm text-secondary-600">
              Already have an account?
              <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-700 transition-colors duration-200">
                Sign in here
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
  name: 'RegisterForm',
  components: { BaseInput, BaseButton },
  setup() {
    return {
      toast: useToast(),
    };
  },
  data() {
    return {
      form: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: ''
      },
      loading: false
    };
  },
  methods: {
    async register() {
      // Validation
      if (!this.form.name || !this.form.email || !this.form.password || !this.form.password_confirmation || !this.form.role) {
        this.toast.error('❌ Please fill in all fields');
        return;
      }

      if (this.form.name.trim().length < 2) {
        this.toast.error('❌ Full name must be at least 2 characters');
        return;
      }

      if (!this.isValidEmail(this.form.email)) {
        this.toast.error('❌ Please enter a valid email address');
        return;
      }

      if (this.form.password.length < 8) {
        this.toast.error('❌ Password must be at least 8 characters');
        return;
      }

      if (this.form.password !== this.form.password_confirmation) {
        this.toast.error('❌ Passwords do not match');
        return;
      }

      this.loading = true;
      try {
        const response = await api.register({
          name: this.form.name,
          email: this.form.email,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
          role: this.form.role
        });

        if (!response.data.access_token) {
          this.toast.error('❌ Registration failed: No authentication token received');
          return;
        }

        localStorage.setItem('authToken', response.data.access_token);
        localStorage.setItem('userRole', this.form.role);
        localStorage.setItem('userEmail', this.form.email);
        // Cache basic profile from registration response
        localStorage.setItem('userProfile', JSON.stringify({
          name: this.form.name,
          email: this.form.email,
          avatar: response.data.user?.avatar || null
        }));
        
        this.toast.success('✅ Registration successful! Welcome!');
        
        setTimeout(() => {
          this.$root.$emit('auth-changed');
          this.$router.push('/dashboard');
        }, 1500);
      } catch (error) {
        console.error('Registration failed:', error);
        
        // Extract specific error messages from backend
        if (error.response?.status === 422) {
          const errors = error.response.data.errors;
          if (errors) {
            const errorMessages = Object.values(errors).flat();
            this.toast.error('❌ ' + (errorMessages[0] || 'Validation failed'));
          } else {
            this.toast.error('❌ ' + (error.response.data.message || 'Validation failed'));
          }
        } else if (error.response?.status === 500) {
          this.toast.error('❌ Server error: ' + (error.response.data.message || 'Please try again later'));
        } else {
          this.toast.error('❌ ' + (error.response?.data?.message || error.message || 'Registration failed. Please try again.'));
        }
      } finally {
        this.loading = false;
      }
    },
    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
  }
};
</script>

<style scoped>
/* Professional styling for enterprise platforms */
</style>
