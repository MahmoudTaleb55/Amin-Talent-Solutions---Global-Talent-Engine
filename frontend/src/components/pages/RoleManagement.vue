<template>
  <div class="min-h-screen bg-secondary-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-secondary-900">Role & Permission Management</h1>
        <p class="mt-1 text-secondary-600">Configure roles and assign permissions</p>
      </div>

      <!-- Roles Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div
          v-for="role in availableRoles"
          :key="role.id"
          class="bg-white rounded-lg border border-secondary-200 p-6 hover:shadow-medium transition-shadow cursor-pointer"
          @click="selectRole(role)"
          :class="{ 'ring-2 ring-primary-500': selectedRoleId === role.id }"
        >
          <div class="flex items-start justify-between mb-4">
            <div>
              <h3 class="text-lg font-semibold text-secondary-900">{{ formatRoleName(role.name) }}</h3>
              <p class="text-sm text-secondary-600">{{ role.permissions_count }} permissions</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" :class="getRoleBadgeClass(role.name)">
              {{ role.name.toUpperCase() }}
            </span>
          </div>
          
          <div class="space-y-2">
            <p v-if="role.permissions_count === 0" class="text-sm text-secondary-500 italic">No permissions assigned</p>
            <div v-else class="flex flex-wrap gap-2">
              <span
                v-for="(perm, idx) in (role.permissions || []).slice(0, 3)"
                :key="idx"
                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-secondary-100 text-secondary-700"
              >
                {{ perm.name || perm }}
              </span>
              <span
                v-if="(role.permissions || []).length > 3"
                class="inline-flex items-center px-2 py-1 text-xs font-medium text-secondary-600"
              >
                +{{ (role.permissions || []).length - 3 }} more
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Permission Assignment Panel -->
      <div v-if="selectedRole" class="bg-white rounded-lg border border-secondary-200 p-6">
        <h2 class="text-2xl font-bold text-secondary-900 mb-6">
          Permissions for <span class="text-primary-600">{{ formatRoleName(selectedRole.name) }}</span>
        </h2>

        <div v-if="loadingPermissions" class="text-center py-8">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
          <p class="mt-2 text-secondary-600">Loading permissions...</p>
        </div>

        <div v-else>
          <!-- Permission Categories -->
          <div class="space-y-6">
            <div v-for="category in permissionCategories" :key="category" class="border border-secondary-200 rounded-lg p-4">
              <h3 class="font-semibold text-secondary-900 mb-4 capitalize">{{ category }}</h3>
              
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <label
                  v-for="permission in getCategoryPermissions(category)"
                  :key="permission.id"
                  class="flex items-center space-x-3 cursor-pointer p-2 hover:bg-secondary-50 rounded transition"
                >
                  <input
                    type="checkbox"
                    :checked="isPermissionAssigned(permission.id)"
                    @change="togglePermission(permission.id)"
                    class="w-4 h-4 border-secondary-300 rounded focus:ring-primary-500"
                  />
                  <span class="text-sm text-secondary-700">{{ permission.display_name || permission.name }}</span>
                </label>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="mt-8 flex space-x-3">
            <button
              @click="clearSelection"
              class="btn btn-secondary"
            >
              Clear Selection
            </button>
            <button
              @click="savePermissions"
              :disabled="savingPermissions"
              class="btn btn-primary disabled:opacity-50"
            >
              {{ savingPermissions ? 'Saving...' : 'Save Permissions' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white rounded-lg border border-secondary-200 p-12 text-center">
        <p class="text-secondary-600">Select a role above to manage its permissions</p>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toastification';

export default {
  name: 'RoleManagement',
  setup() {
    return { toast: useToast() };
  },
  data() {
    return {
      availableRoles: [],
      allPermissions: [],
      selectedRole: null,
      selectedRoleId: null,
      assignedPermissions: [],
      loadingPermissions: false,
      savingPermissions: false
    };
  },
  computed: {
    permissionCategories() {
      const categories = new Set();
      this.allPermissions.forEach(perm => {
        const category = perm.name?.split('-')[0] || 'other';
        categories.add(category);
      });
      return Array.from(categories).sort();
    }
  },
  mounted() {
    this.loadRolesAndPermissions();
  },
  methods: {
    async loadRolesAndPermissions() {
      try {
        // For demo purposes, create mock roles and permissions
        // In production, these would come from the backend API
        this.availableRoles = [
          {
            id: 1,
            name: 'admin',
            permissions_count: 8,
            permissions: ['view-users', 'create-user', 'edit-user', 'delete-user', 'view-projects', 'view-system-settings', 'edit-system-settings', 'export-reports']
          },
          {
            id: 2,
            name: 'ceo',
            permissions_count: 4,
            permissions: ['view-users', 'view-projects', 'view-financial-reports', 'export-reports']
          },
          {
            id: 3,
            name: 'project_manager',
            permissions_count: 8,
            permissions: ['view-projects', 'create-project', 'edit-project', 'delete-project', 'view-assignments', 'create-assignment', 'edit-assignment', 'delete-assignment']
          },
          {
            id: 4,
            name: 'company',
            permissions_count: 5,
            permissions: ['view-jobs', 'post-job', 'edit-job', 'delete-job', 'view-freelancers']
          },
          {
            id: 5,
            name: 'freelancer',
            permissions_count: 3,
            permissions: ['view-assignments', 'submit-deliverable', 'review-deliverable']
          }
        ];

        this.allPermissions = [
          // User permissions
          { id: 1, name: 'view-users', display_name: 'View Users' },
          { id: 2, name: 'create-user', display_name: 'Create Users' },
          { id: 3, name: 'edit-user', display_name: 'Edit Users' },
          { id: 4, name: 'delete-user', display_name: 'Delete Users' },
          // Project permissions
          { id: 5, name: 'view-projects', display_name: 'View Projects' },
          { id: 6, name: 'create-project', display_name: 'Create Projects' },
          { id: 7, name: 'edit-project', display_name: 'Edit Projects' },
          { id: 8, name: 'delete-project', display_name: 'Delete Projects' },
          // Assignment permissions
          { id: 9, name: 'view-assignments', display_name: 'View Assignments' },
          { id: 10, name: 'create-assignment', display_name: 'Create Assignments' },
          { id: 11, name: 'edit-assignment', display_name: 'Edit Assignments' },
          { id: 12, name: 'delete-assignment', display_name: 'Delete Assignments' },
          // Freelancer permissions
          { id: 13, name: 'view-freelancers', display_name: 'View Freelancers' },
          { id: 14, name: 'submit-deliverable', display_name: 'Submit Deliverables' },
          { id: 15, name: 'review-deliverable', display_name: 'Review Deliverables' },
          // Job permissions
          { id: 16, name: 'view-jobs', display_name: 'View Jobs' },
          { id: 17, name: 'post-job', display_name: 'Post Jobs' },
          { id: 18, name: 'edit-job', display_name: 'Edit Jobs' },
          { id: 19, name: 'delete-job', display_name: 'Delete Jobs' },
          // Report permissions
          { id: 20, name: 'view-financial-reports', display_name: 'View Financial Reports' },
          { id: 21, name: 'export-reports', display_name: 'Export Reports' },
          // System permissions
          { id: 22, name: 'view-system-settings', display_name: 'View System Settings' },
          { id: 23, name: 'edit-system-settings', display_name: 'Edit System Settings' }
        ];

        this.toast.success('Roles and permissions loaded');
      } catch (error) {
        console.error('Error loading roles and permissions:', error);
        this.toast.error('Failed to load roles and permissions');
      }
    },
    selectRole(role) {
      this.selectedRole = role;
      this.selectedRoleId = role.id;
      this.assignedPermissions = [...(role.permissions || [])];
      this.loadingPermissions = false;
    },
    getCategoryPermissions(category) {
      return this.allPermissions.filter(perm => perm.name.startsWith(category + '-'));
    },
    isPermissionAssigned(permissionId) {
      const permission = this.allPermissions.find(p => p.id === permissionId);
      return this.assignedPermissions.includes(permission.name);
    },
    togglePermission(permissionId) {
      const permission = this.allPermissions.find(p => p.id === permissionId);
      if (!permission) return;

      const index = this.assignedPermissions.indexOf(permission.name);
      if (index > -1) {
        this.assignedPermissions.splice(index, 1);
      } else {
        this.assignedPermissions.push(permission.name);
      }
    },
    async savePermissions() {
      if (!this.selectedRole) return;

      this.savingPermissions = true;
      try {
        // In production, this would call: await api.admin.updateRolePermissions(this.selectedRole.id, this.assignedPermissions);
        // For now, just update the local state
        this.selectedRole.permissions = [...this.assignedPermissions];
        this.selectedRole.permissions_count = this.assignedPermissions.length;
        
        this.toast.success(`Permissions saved for ${this.formatRoleName(this.selectedRole.name)}`);
      } catch (error) {
        console.error('Error saving permissions:', error);
        this.toast.error('Failed to save permissions');
      } finally {
        this.savingPermissions = false;
      }
    },
    clearSelection() {
      this.selectedRole = null;
      this.selectedRoleId = null;
      this.assignedPermissions = [];
    },
    formatRoleName(name) {
      return name.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
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
    }
  }
};
</script>
