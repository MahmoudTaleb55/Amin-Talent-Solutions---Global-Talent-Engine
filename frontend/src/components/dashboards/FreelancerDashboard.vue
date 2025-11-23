<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Freelancer Dashboard</h1>
          <p class="text-gray-600 mt-1">Manage your assignments and deliverables</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-sm transition-shadow duration-200">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-sm font-medium text-gray-900">Active Assignments</h3>
            <p class="text-sm text-gray-500">{{ assignments.filter(a => a.status !== 'completed').length }} active</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-sm transition-shadow duration-200">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-sm font-medium text-gray-900">Completed</h3>
            <p class="text-sm text-gray-500">{{ assignments.filter(a => a.status === 'completed').length }} finished</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-sm transition-shadow duration-200">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-sm font-medium text-gray-900">Earnings</h3>
            <p class="text-sm text-gray-500">${{ totalEarnings || 0 }}</p>
          </div>
        </div>
      </div>
    </div>


    <!-- My Assignments Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">My Assignments</h2>
          <p class="text-gray-600 text-sm mt-1">Projects assigned to you</p>
        </div>
      </div>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SkeletonCard v-for="n in 3" :key="`assign-skel-${n}`" />
      </div>

      <div v-else-if="assignments.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No assignments found</h3>
        <p class="mt-1 text-sm text-gray-500">You haven't been assigned to any projects yet.</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="assignment in assignments" :key="assignment.id" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ assignment.project_request.title }}</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  {{ assignment.project_request.company.company_name }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                  <span class="font-medium">${{ assignment.project_request.budget }}</span>
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span :class="getStatusColor(assignment.status)" class="font-medium capitalize">{{ assignment.status.replace('_', ' ') }}</span>
                </div>
              </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2"
                  :class="assignment.status === 'completed' ? 'bg-green-100 text-green-800' : assignment.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800'">
              {{ assignment.status.replace('_', ' ') }}
            </span>
          </div>
          <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ assignment.project_request.description }}</p>
          <div class="flex space-x-2 mb-4">
            <button @click="selectAssignment(assignment)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 flex-1 transform hover:scale-105">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              Submit Deliverable
            </button>
            <button @click="selectAssignmentForStatus(assignment)" class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-md text-sm font-medium border border-gray-300 transition-all duration-200 flex-1 transform hover:scale-105">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Update Status
            </button>
          </div>
          <!-- Deliverables -->
          <div v-if="assignment.deliverables && assignment.deliverables.length > 0" class="border-t border-gray-100 pt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Submitted Deliverables</h4>
            <div class="space-y-2">
              <div v-for="deliverable in assignment.deliverables" :key="deliverable.id" class="flex items-center justify-between bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors duration-200">
                <div class="flex-1">
                  <p class="text-sm text-gray-700">{{ deliverable.description }}</p>
                  <p class="text-xs text-gray-500">{{ formatDate(deliverable.submitted_at) }}</p>
                </div>
                <a :href="getFileUrl(deliverable.file_path)" target="_blank" class="text-blue-600 hover:text-blue-700 transition-colors duration-200">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </a>
              </div>
            </div>
          </div>
          <!-- Status Updates -->
          <div v-if="assignment.status_updates && assignment.status_updates.length > 0" class="border-t border-gray-100 pt-4 mt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Recent Updates</h4>
            <div class="space-y-2">
              <div v-for="update in assignment.status_updates.slice(-2)" :key="update.id" class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors duration-200">
                <div class="flex items-center justify-between">
                  <span :class="getStatusColor(update.status)" class="text-sm font-medium capitalize">{{ update.status.replace('_', ' ') }}</span>
                  <span class="text-xs text-gray-500">{{ formatDate(update.created_at) }}</span>
                </div>
                <p class="text-sm text-gray-700 mt-1">{{ update.message }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Submit Deliverable Modal -->
    <div v-if="selectedAssignment" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Submit Deliverable</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Upload your work for <strong>{{ selectedAssignment.project_request.title }}</strong></p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="submitDeliverable" class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">File</label>
                      <input type="file" @change="handleFileChange" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Description</label>
                      <textarea v-model="deliverableForm.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Describe your deliverable..."></textarea>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="submitDeliverable" :disabled="submitting" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="submitting" class="loading-spinner mr-2"></span>
              Submit Deliverable
            </button>
            <button type="button" @click="closeModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>


    <!-- Status Update Modal -->
    <div v-if="selectedAssignmentForStatus" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeStatusModal">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Update Status</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Update the status for <strong>{{ selectedAssignmentForStatus.project_request.title }}</strong></p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="postStatusUpdate" class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Status</label>
                      <select v-model="statusForm.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="on_hold">On Hold</option>
                        <option value="cancelled">Cancelled</option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Message</label>
                      <textarea v-model="statusForm.message" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Add a status update message..."></textarea>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="postStatusUpdate" :disabled="updating" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="updating" class="loading-spinner mr-2"></span>
              Update Status
            </button>
            <button type="button" @click="closeStatusModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
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
  name: 'FreelancerDashboard',
  components: { SkeletonCard },
  setup() {
    return {
      toast: useToast(),
    };
  },
  data() {
    return {
      assignments: [],
      loading: true,
      selectedAssignment: null,
      selectedAssignmentForStatus: null,
      deliverableForm: {
        file: null,
        description: ''
      },
      statusForm: {
        status: '',
        message: ''
      },
      submitting: false,
      updating: false,
      totalEarnings: 0
    };
  },

  mounted() {
    this.fetchAssignments();
    this.calculateTotalEarnings();
  },

  methods: {
    async fetchAssignments() {
      try {
        const response = await api.freelancer.getAssignments();
        this.assignments = response.data;
        this.calculateTotalEarnings();
      } catch (error) {
        console.error('Error fetching assignments:', error);
        this.toast.error('Failed to load assignments');
      } finally {
        this.loading = false;
      }
    },
    selectAssignment(assignment) {
      this.selectedAssignment = assignment;
    },
    selectAssignmentForStatus(assignment) {
      this.selectedAssignmentForStatus = assignment;
    },
    closeModal() {
      this.selectedAssignment = null;
      this.deliverableForm = { file: null, description: '' };
    },
    closeStatusModal() {
      this.selectedAssignmentForStatus = null;
      this.statusForm = { status: '', message: '' };
    },
    handleFileChange(event) {
      this.deliverableForm.file = event.target.files[0];
    },
    async submitDeliverable() {
      if (!this.deliverableForm.file) {
        this.toast.error('Please select a file to upload');
        return;
      }

      this.submitting = true;
      const formData = new FormData();
      formData.append('file', this.deliverableForm.file);
      formData.append('description', this.deliverableForm.description);

      try {
        await api.freelancer.submitDeliverable(this.selectedAssignment.id, formData);
        this.toast.success('Deliverable submitted successfully');
        this.closeModal();
        this.fetchAssignments();
      } catch (error) {
        console.error('Error submitting deliverable:', error);
        this.toast.error(error.response?.data?.message || 'Failed to submit deliverable');
      } finally {
        this.submitting = false;
      }
    },
    async postStatusUpdate() {
      this.updating = true;
      try {
        await api.freelancer.postStatusUpdate(this.selectedAssignmentForStatus.id, this.statusForm);
        this.toast.success('Status updated successfully');
        this.closeStatusModal();
        this.fetchAssignments();
      } catch (error) {
        console.error('Error posting status update:', error);
        this.toast.error('Failed to update status');
      } finally {
        this.updating = false;
      }
    },
    getFileUrl(filePath) {
      return `http://localhost:8000/storage/${filePath}`;
    },
    getStatusColor(status) {
      const colors = {
        'in_progress': 'text-yellow-600',
        'completed': 'text-green-600',
        'on_hold': 'text-gray-600',
        'cancelled': 'text-red-600'
      };
      return colors[status] || 'text-gray-600';
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString();
    },
    calculateTotalEarnings() {
      this.totalEarnings = this.assignments
        .filter(assignment => assignment.status === 'completed')
        .reduce((total, assignment) => total + (assignment.project_request?.budget || 0), 0);
    }
  }
};
</script>

<style scoped>
/* All styles handled by Tailwind CSS */
</style>
