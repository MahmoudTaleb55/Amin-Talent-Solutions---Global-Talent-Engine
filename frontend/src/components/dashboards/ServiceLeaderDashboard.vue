<template>
  <div class="space-y-6 fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Service Leader Dashboard</h1>
        <p class="text-gray-600 mt-1">Manage freelancers, quality control, and service operations</p>
      </div>
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Freelancer Pool Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Freelancer Pool</h2>
          <p class="text-gray-600 text-sm mt-1">Available freelancers for project assignments</p>
        </div>
      </div>
      <div v-if="loadingFreelancers" class="space-y-3">
        <div v-for="n in 4" :key="n" class="skeleton h-24 rounded-md"></div>
      </div>
      <div v-else-if="freelancers.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No freelancers available</h3>
        <p class="mt-1 text-sm text-gray-500">Freelancers will appear here once they register.</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="freelancer in freelancers" :key="freelancer.id" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ freelancer.user.name }}</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                  </svg>
                  Skills: {{ freelancer.skills }}
                </div>
              </div>
            </div>
          </div>
          <button @click="assignJobToFreelancer(freelancer)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Assign Job
          </button>
        </div>
      </div>
    </div>

    <!-- Quality Control Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Quality Control</h2>
          <p class="text-gray-600 text-sm mt-1">Review and approve submitted deliverables</p>
        </div>
      </div>
      <div v-if="loadingDeliverables" class="space-y-3">
        <div v-for="n in 3" :key="n" class="skeleton h-32 rounded-md"></div>
      </div>
      <div v-else-if="deliverables.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No deliverables to review</h3>
        <p class="mt-1 text-sm text-gray-500">Deliverables will appear here once freelancers submit work.</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="deliverable in deliverables" :key="deliverable.id" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ deliverable.assignment.projectRequest.title }}</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  {{ deliverable.assignment.freelancer.user.name }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ deliverable.submitted_at }}
                </div>
              </div>
            </div>
          </div>
          <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ deliverable.description }}</p>
          <div class="flex space-x-2">
            <a :href="getFileUrl(deliverable.file_path)" target="_blank" class="bg-white hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 flex-1 text-center">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              Download
            </a>
            <button @click="approveDeliverable(deliverable.id)" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex-1">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Approve
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Service Reports Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Service Reports</h2>
          <p class="text-gray-600 text-sm mt-1">Key performance indicators and service metrics</p>
        </div>
      </div>
      <div v-if="loadingReports" class="space-y-3">
        <div v-for="n in 4" :key="n" class="skeleton h-16 rounded-md"></div>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-blue-800">Total Freelancers</p>
              <p class="text-2xl font-bold text-blue-900">{{ reports.total_freelancers || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-green-800">Active Projects</p>
              <p class="text-2xl font-bold text-green-900">{{ reports.active_projects || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-yellow-800">Total Assignments</p>
              <p class="text-2xl font-bold text-yellow-900">{{ reports.total_assignments || 0 }}</p>
            </div>
          </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-purple-800">Completed Deliverables</p>
              <p class="text-2xl font-bold text-purple-900">{{ reports.completed_deliverables || 0 }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Assign Job Modal -->
    <div v-if="showAssignModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeAssignModal">
          <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-lg transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Assign Job</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Assign a project to <strong>{{ selectedFreelancer ? selectedFreelancer.user.name : '' }}</strong></p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="submitAssignment" class="space-y-3">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Select Project</label>
                      <select v-model="assignmentForm.project_request_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Choose a project</option>
                        <option v-for="project in projects" :key="project.id" :value="project.id">
                          {{ project.title }} - {{ project.company.company_name }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                      <textarea v-model="assignmentForm.notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Add any special instructions or notes..."></textarea>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="submitAssignment" :disabled="assigning" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="assigning" class="loading-spinner mr-2"></span>
              Assign Job
            </button>
            <button type="button" @click="closeAssignModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';

export default {
  name: 'ServiceLeaderDashboard',
  // No local components registered; using global/shared primitives

  data() {
    return {
      freelancers: [],
      deliverables: [],
      reports: {},
      projects: [],
      loadingFreelancers: true,
      loadingDeliverables: true,
      loadingReports: true,
      loadingProjects: true,
      showAssignModal: false,
      selectedFreelancer: null,
      assignmentForm: {
        project_request_id: '',
        notes: ''
      },
      assigning: false
    };
  },
  mounted() {
    this.fetchFreelancerPool();
    this.fetchQualityControl();
    this.fetchServiceReports();
    this.fetchProjects();
  },
  methods: {
    async fetchFreelancerPool() {
      try {
        const response = await api.get('/service/freelancer-pool');
        this.freelancers = response.data;
      } catch (error) {
        console.error('Error fetching freelancer pool:', error);
      } finally {
        this.loadingFreelancers = false;
      }
    },
    async fetchQualityControl() {
      try {
        const response = await api.get('/service/quality-control');
        this.deliverables = response.data;
      } catch (error) {
        console.error('Error fetching quality control:', error);
      } finally {
        this.loadingDeliverables = false;
      }
    },
    async fetchServiceReports() {
      try {
        const response = await api.get('/service/reports');
        this.reports = response.data;
      } catch (error) {
        console.error('Error fetching service reports:', error);
      } finally {
        this.loadingReports = false;
      }
    },
    async fetchProjects() {
      try {
        const response = await api.get('/projects');
        this.projects = response.data.data || response.data; // Handle pagination if any
      } catch (error) {
        console.error('Error fetching projects:', error);
      } finally {
        this.loadingProjects = false;
      }
    },
    assignJobToFreelancer(freelancer) {
      this.selectedFreelancer = freelancer;
      this.showAssignModal = true;
    },
    async submitAssignment() {
      this.assigning = true;
      try {
        await api.post('/service/assign-job', {
          project_request_id: this.assignmentForm.project_request_id,
          freelancer_id: this.selectedFreelancer.id,
          notes: this.assignmentForm.notes
        });
        this.closeAssignModal();
        this.fetchFreelancerPool();
        this.fetchServiceReports();
      } catch (error) {
        console.error('Error assigning job:', error);
      } finally {
        this.assigning = false;
      }
    },
    async approveDeliverable(deliverableId) {
      try {
        await api.post(`/service/deliverables/${deliverableId}/approve`);
        this.fetchQualityControl();
      } catch (error) {
        console.error('Error approving deliverable:', error);
      }
    },
    getFileUrl(filePath) {
      return `${process.env.VUE_APP_API_URL}/storage/${filePath}`;
    },
    closeAssignModal() {
      this.showAssignModal = false;
      this.selectedFreelancer = null;
      this.assignmentForm = { project_request_id: '', notes: '' };
    },
    mapFreelancerData(freelancer) {
      return {
        id: freelancer.id,
        name: freelancer.user.name,
        title: freelancer.title || 'Freelancer',
        avatar: freelancer.avatar || null,
        rating: freelancer.rating || 4.5,
        reviews: freelancer.reviews || Math.floor(Math.random() * 100) + 10,
        topSkills: freelancer.skills ? freelancer.skills.split(',').slice(0, 4) : ['Web Development', 'Design'],
        hourlyRate: freelancer.hourly_rate || 50,
        description: freelancer.bio || 'Experienced professional ready to help with your projects.',
        completedJobs: freelancer.completed_jobs || Math.floor(Math.random() * 50) + 10,
        successRate: freelancer.success_rate || 95,
        responseTime: freelancer.response_time || '< 2 hours',
        isOnline: freelancer.is_online || Math.random() > 0.5,
        isVerified: freelancer.is_verified || Math.random() > 0.3
      };
    },
    viewFreelancerProfile(freelancer) {
      // Navigate to freelancer profile or show modal
      console.log('Viewing profile for:', freelancer.name);
      // You can implement navigation or modal here
    }

  }
};
</script>

<style scoped>
/* All styles handled by Tailwind CSS */
</style>
