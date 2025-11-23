<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
          <p class="text-gray-600 mt-1">Manage users, roles, and system settings</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Management Navigation -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <router-link
        to="/dashboard/admin/users"
        class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200 p-6 hover:shadow-lg transition-all cursor-pointer group"
      >
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-lg font-semibold text-blue-900 group-hover:text-blue-700">User Management</h3>
            <p class="text-sm text-blue-700 mt-1">Manage system users and their roles</p>
          </div>
          <div class="w-10 h-10 bg-blue-200 rounded-lg flex items-center justify-center group-hover:bg-blue-300 transition">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
          </div>
        </div>
      </router-link>

      <router-link
        to="/dashboard/admin/roles"
        class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg border-2 border-purple-200 p-6 hover:shadow-lg transition-all cursor-pointer group"
      >
        <div class="flex items-start justify-between">
          <div>
            <h3 class="text-lg font-semibold text-purple-900 group-hover:text-purple-700">Role & Permission Management</h3>
            <p class="text-sm text-purple-700 mt-1">Configure roles and permissions</p>
          </div>
          <div class="w-10 h-10 bg-purple-200 rounded-lg flex items-center justify-center group-hover:bg-purple-300 transition">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
        </div>
      </router-link>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalUsers || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Projects</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.activeProjects || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Completed Tasks</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.completedTasks || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Pending Issues</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.pendingIssues || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- User Management Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">User Management</h2>
          <p class="text-gray-600 text-sm mt-1">Manage user accounts and permissions</p>
        </div>
        <button @click="showCreateUserModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
          <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Add User
        </button>
      </div>

      <div v-if="loadingUsers" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <SkeletonCard v-for="n in 6" :key="`user-skel-${n}`" />
      </div>

      <div v-else-if="users.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by adding your first user.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-700">{{ user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="getRoleBadgeClass(user.role)">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="editUser(user)" class="text-blue-600 hover:text-blue-900 mr-4">Edit</button>
                <button @click="deleteUser(user)" class="text-red-600 hover:text-red-900">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- System Settings Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">System Settings</h2>
          <p class="text-gray-600 text-sm mt-1">Configure platform settings and preferences</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Platform Status</label>
            <div class="mt-1">
              <select v-model="settings.platformStatus" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="active">Active</option>
                <option value="maintenance">Maintenance</option>
                <option value="disabled">Disabled</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Max File Upload Size (MB)</label>
            <div class="mt-1">
              <input v-model.number="settings.maxFileSize" type="number" min="1" max="100" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Email Notifications</label>
            <div class="mt-1">
              <div class="flex items-center">
                <input v-model="settings.emailNotifications" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label class="ml-2 block text-sm text-gray-900">Enable email notifications</label>
              </div>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Auto Backup</label>
            <div class="mt-1">
              <div class="flex items-center">
                <input v-model="settings.autoBackup" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label class="ml-2 block text-sm text-gray-900">Enable automatic backups</label>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <button @click="saveSettings" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
          Save Settings
        </button>
      </div>
    </div>

    <!-- Create User Modal -->
    <div v-if="showCreateUserModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeCreateUserModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Create New User</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Add a new user to the platform</p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="createUser" class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Name</label>
                      <input v-model="newUser.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Email</label>
                      <input v-model="newUser.email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Password</label>
                      <input v-model="newUser.password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Role</label>
                      <select v-model="newUser.role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2" required>
                        <option value="">Select role</option>
                        <option value="admin">Admin</option>
                        <option value="ceo">CEO</option>
                        <option value="company">Company</option>
                        <option value="freelancer">Freelancer</option>
                        <option value="project_manager">Project Manager</option>
                      </select>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="createUser" :disabled="creatingUser" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="creatingUser" class="loading-spinner mr-2"></span>
              Create User
            </button>
            <button type="button" @click="closeCreateUserModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
              Cancel
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
import SkeletonCard from '@/components/ui/SkeletonCard.vue';

export default {
  name: 'AdminDashboard',
  components: { SkeletonCard },
  setup() {
    return {
      toast: useToast(),
    };
  },
  data() {
    return {
      stats: {},
      users: [],
      loadingUsers: true,
      showCreateUserModal: false,
      newUser: {
        name: '',
        email: '',
        password: '',
        role: ''
      },
      creatingUser: false,
      settings: {
        platformStatus: 'active',
        maxFileSize: 10,
        emailNotifications: true,
        autoBackup: true
      }
    };
  },
  mounted() {
    this.fetchStats();
    this.fetchUsers();
    this.loadSettings();
  },
  methods: {
    async fetchStats() {
      try {
        const response = await api.admin.getStats();
        this.stats = response.data;
      } catch (error) {
        console.error('Error fetching stats:', error);
        this.toast.error('Failed to load system stats');
      }
    },
    async fetchUsers() {
      try {
        const response = await api.admin.getUsers();
        this.users = response.data;
      } catch (error) {
        console.error('Error fetching users:', error);
        this.toast.error('Failed to load users');
      } finally {
        this.loadingUsers = false;
      }
    },
    async createUser() {
      this.creatingUser = true;
      try {
        await api.admin.createUser(this.newUser);
        this.toast.success('User created successfully');
        this.closeCreateUserModal();
        this.fetchUsers();
      } catch (error) {
        console.error('Error creating user:', error);
        this.toast.error(error.response?.data?.message || 'Failed to create user');
      } finally {
        this.creatingUser = false;
      }
    },
    async deleteUser(user) {
      if (confirm(`Are you sure you want to delete ${user.name}?`)) {
        try {
          await api.admin.deleteUser(user.id);
          this.toast.success('User deleted successfully');
          this.fetchUsers();
        } catch (error) {
          console.error('Error deleting user:', error);
          this.toast.error('Failed to delete user');
        }
      }
    },
    editUser() {
      // TODO: Implement edit functionality with modal
      this.$toast.info('Edit functionality coming soon');
    },
    closeCreateUserModal() {
      this.showCreateUserModal = false;
      this.newUser = { name: '', email: '', password: '', role: '' };
    },
    saveSettings() {
      this.$toast.success('Settings saved successfully');
      console.log('Saving settings:', this.settings);
    },
    loadSettings() {
      // Load settings from API or local storage
      console.log('Loading settings');
    },
    getRoleBadgeClass(role) {
      const classes = {
        'admin': 'bg-purple-100 text-purple-800',
        'ceo': 'bg-red-100 text-red-800',
        'company': 'bg-blue-100 text-blue-800',
        'freelancer': 'bg-green-100 text-green-800',
        'project_manager': 'bg-yellow-100 text-yellow-800'
      };
      return classes[role] || 'bg-gray-100 text-gray-800';
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    }
  }
};
</script>

<style scoped>
/* Professional styling for enterprise platforms */
</style>
