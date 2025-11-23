<template>
  <div class="min-h-screen bg-secondary-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-3xl font-bold text-secondary-900">User Management</h1>
          <p class="mt-1 text-secondary-600">Manage system users and their roles</p>
        </div>
        <button
          @click="openCreateModal"
          class="mt-4 sm:mt-0 btn btn-primary"
        >
          + Add New User
        </button>
      </div>

      <!-- Search and Filter -->
      <div class="mb-6 bg-white rounded-lg border border-secondary-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-2">Search by Name or Email</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Search..."
              class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-2">Filter by Role</label>
            <select
              v-model="selectedRole"
              class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
              <option value="">All Roles</option>
              <option value="admin">Admin</option>
              <option value="ceo">CEO</option>
              <option value="project_manager">Project Manager</option>
              <option value="company">Company</option>
              <option value="freelancer">Freelancer</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="resetFilters"
              class="w-full btn btn-secondary"
            >
              Reset Filters
            </button>
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg border border-secondary-200 overflow-hidden">
        <div v-if="loading" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <p class="mt-2 text-secondary-600">Loading users...</p>
        </div>

        <table v-else-if="filteredUsers.length > 0" class="w-full">
          <thead>
            <tr class="bg-secondary-50 border-b border-secondary-200">
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-700 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-700 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-700 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-700 uppercase tracking-wider">Joined</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-secondary-700 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id" class="border-b border-secondary-100 hover:bg-secondary-50 transition">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-secondary-900">{{ user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" :class="getRoleBadgeClass(user.role)">
                  {{ formatRoleName(user.role) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-secondary-600">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button
                  @click="openEditModal(user)"
                  class="text-primary-600 hover:text-primary-700 font-medium"
                >
                  Edit
                </button>
                <button
                  @click="confirmDelete(user)"
                  class="text-danger-600 hover:text-danger-700 font-medium"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-else class="p-8 text-center">
          <p class="text-secondary-600">No users found matching your filters.</p>
        </div>
      </div>

      <!-- Create/Edit User Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-secondary-900 bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-large max-w-md w-full p-6">
          <h2 class="text-2xl font-bold text-secondary-900 mb-4">
            {{ editingUser ? 'Edit User' : 'Create New User' }}
          </h2>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-secondary-700 mb-1">Full Name</label>
              <input
                v-model="formData.name"
                type="text"
                placeholder="Full Name"
                class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
              <p v-if="errors.name" class="mt-1 text-sm text-danger-600">{{ errors.name }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-secondary-700 mb-1">Email</label>
              <input
                v-model="formData.email"
                type="email"
                placeholder="user@example.com"
                :disabled="!!editingUser"
                class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:bg-secondary-100"
              />
              <p v-if="errors.email" class="mt-1 text-sm text-danger-600">{{ errors.email }}</p>
            </div>

            <div v-if="!editingUser">
              <label class="block text-sm font-medium text-secondary-700 mb-1">Password</label>
              <input
                v-model="formData.password"
                type="password"
                placeholder="Password"
                class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
              />
              <p v-if="errors.password" class="mt-1 text-sm text-danger-600">{{ errors.password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-secondary-700 mb-1">Role</label>
              <select
                v-model="formData.role"
                class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"
              >
                <option value="">Select a role</option>
                <option value="admin">Admin</option>
                <option value="ceo">CEO</option>
                <option value="project_manager">Project Manager</option>
                <option value="company">Company</option>
                <option value="freelancer">Freelancer</option>
              </select>
              <p v-if="errors.role" class="mt-1 text-sm text-danger-600">{{ errors.role }}</p>
            </div>
          </div>

          <div class="mt-6 flex space-x-3">
            <button
              @click="closeModal"
              class="flex-1 btn btn-secondary"
            >
              Cancel
            </button>
            <button
              @click="saveUser"
              :disabled="saving"
              class="flex-1 btn btn-primary disabled:opacity-50"
            >
              {{ saving ? 'Saving...' : 'Save User' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteConfirm" class="fixed inset-0 bg-secondary-900 bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-large max-w-md w-full p-6">
          <h2 class="text-xl font-bold text-secondary-900 mb-2">Delete User?</h2>
          <p class="text-secondary-600 mb-6">
            Are you sure you want to delete <strong>{{ userToDelete?.name }}</strong>? This action cannot be undone.
          </p>

          <div class="flex space-x-3">
            <button
              @click="showDeleteConfirm = false"
              class="flex-1 btn btn-secondary"
            >
              Cancel
            </button>
            <button
              @click="deleteUser"
              :disabled="deleting"
              class="flex-1 btn btn-danger disabled:opacity-50"
            >
              {{ deleting ? 'Deleting...' : 'Delete' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import api from '@/services/api';

export default {
  name: 'UserManagement',
  setup() {
    return { toast: useToast() };
  },
  data() {
    return {
      users: [],
      loading: false,
      saving: false,
      deleting: false,
      searchQuery: '',
      selectedRole: '',
      showModal: false,
      showDeleteConfirm: false,
      editingUser: null,
      userToDelete: null,
      formData: {
        name: '',
        email: '',
        password: '',
        role: ''
      },
      errors: {}
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user => {
        const matchesSearch = !this.searchQuery || 
          user.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          user.email.toLowerCase().includes(this.searchQuery.toLowerCase());
        
        const matchesRole = !this.selectedRole || user.role === this.selectedRole;
        
        return matchesSearch && matchesRole;
      });
    }
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      this.loading = true;
      try {
        const response = await api.admin.getUsers();
        this.users = response.data || [];
        this.toast.success('Users loaded successfully');
      } catch (error) {
        console.error('Error fetching users:', error);
        this.toast.error(error.response?.data?.message || 'Failed to load users');
      } finally {
        this.loading = false;
      }
    },
    openCreateModal() {
      this.editingUser = null;
      this.formData = { name: '', email: '', password: '', role: '' };
      this.errors = {};
      this.showModal = true;
    },
    openEditModal(user) {
      this.editingUser = user;
      this.formData = {
        name: user.name,
        email: user.email,
        password: '',
        role: user.role
      };
      this.errors = {};
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.editingUser = null;
      this.formData = { name: '', email: '', password: '', role: '' };
      this.errors = {};
    },
    validateForm() {
      this.errors = {};
      
      if (!this.formData.name.trim()) {
        this.errors.name = 'Name is required';
      }
      
      if (!this.formData.email.trim()) {
        this.errors.email = 'Email is required';
      } else if (!this.isValidEmail(this.formData.email)) {
        this.errors.email = 'Please enter a valid email';
      }
      
      if (!this.editingUser && !this.formData.password) {
        this.errors.password = 'Password is required for new users';
      }
      
      if (!this.formData.role) {
        this.errors.role = 'Role is required';
      }
      
      return Object.keys(this.errors).length === 0;
    },
    isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    },
    async saveUser() {
      if (!this.validateForm()) {
        return;
      }
      
      this.saving = true;
      try {
        const payload = {
          name: this.formData.name,
          email: this.formData.email,
          role: this.formData.role
        };
        
        if (!this.editingUser && this.formData.password) {
          payload.password = this.formData.password;
        }
        
        if (this.editingUser) {
          await api.admin.updateUser(this.editingUser.id, payload);
          this.toast.success('User updated successfully');
        } else {
          await api.admin.createUser(payload);
          this.toast.success('User created successfully');
        }
        
        this.closeModal();
        await this.fetchUsers();
      } catch (error) {
        console.error('Error saving user:', error);
        this.toast.error(error.response?.data?.message || 'Failed to save user');
      } finally {
        this.saving = false;
      }
    },
    confirmDelete(user) {
      this.userToDelete = user;
      this.showDeleteConfirm = true;
    },
    async deleteUser() {
      if (!this.userToDelete) return;
      
      this.deleting = true;
      try {
        await api.admin.deleteUser(this.userToDelete.id);
        this.toast.success('User deleted successfully');
        this.showDeleteConfirm = false;
        await this.fetchUsers();
      } catch (error) {
        console.error('Error deleting user:', error);
        this.toast.error(error.response?.data?.message || 'Failed to delete user');
      } finally {
        this.deleting = false;
      }
    },
    resetFilters() {
      this.searchQuery = '';
      this.selectedRole = '';
    },
    formatRoleName(role) {
      return role.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
    },
    getRoleBadgeClass(role) {
      const classes = {
        admin: 'bg-danger-100 text-danger-800',
        ceo: 'bg-primary-100 text-primary-800',
        project_manager: 'bg-warning-100 text-warning-800',
        company: 'bg-success-100 text-success-800',
        freelancer: 'bg-secondary-100 text-secondary-800'
      };
      return classes[role] || 'bg-secondary-100 text-secondary-800';
    },
    formatDate(dateString) {
      if (!dateString) return 'N/A';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }
  }
};
</script>
