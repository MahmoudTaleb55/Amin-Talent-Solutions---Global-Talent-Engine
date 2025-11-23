<template>
  <div class="space-y-6 fade-in-up">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Project Manager Dashboard</h1>
        <p class="text-gray-600 mt-1">Oversee projects, allocate resources, and track team performance</p>
      </div>
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Active Projects Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Active Projects</h2>
          <p class="text-gray-600 text-sm mt-1">Projects currently in progress</p>
        </div>
      </div>
      <div v-if="loadingProjects" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <SkeletonCard v-for="n in 3" :key="`proj-skel-${n}`" />
      </div>
      <div v-else-if="activeProjects.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No active projects</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new project.</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="project in activeProjects" :key="project.id" class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-all duration-200 transform hover:-translate-y-1">
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ project.title }}</h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  {{ project.company.company_name }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                  </svg>
                  ${{ project.budget }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ project.deadline }}
                </div>
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                  </svg>
                  {{ project.assignments ? project.assignments.length : 0 }} assignments
                </div>
              </div>
            </div>
          </div>
          <div class="mt-4">
            <button @click="allocateResource(project)" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full transform hover:scale-105">
              <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Allocate Resource
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Project Reports Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Project Reports</h2>
          <p class="text-gray-600 text-sm mt-1">Detailed analytics and progress tracking</p>
        </div>
      </div>
      <div v-if="loadingReports" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <SkeletonCard v-for="n in 3" :key="`report-skel-${n}`" />
      </div>
      <div v-else-if="projectReports.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No reports available</h3>
        <p class="mt-1 text-sm text-gray-500">Reports will appear once projects are underway.</p>
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="report in projectReports" :key="report.project.id" class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ report.project.title }}</h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Total Assignments</span>
              <span class="text-sm font-medium text-gray-900">{{ report.total_assignments }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">Completed</span>
              <span class="text-sm font-medium text-green-600">{{ report.completed_assignments }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-gray-600">In Progress</span>
              <span class="text-sm font-medium text-yellow-600">{{ report.in_progress }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Team Performance Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Team Performance</h2>
          <p class="text-gray-600 text-sm mt-1">Key performance indicators and top performers</p>
        </div>
      </div>
      <div v-if="loadingPerformance" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <SkeletonCard v-for="n in 4" :key="`perf-skel-${n}`" />
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-blue-800">Completion Rate</p>
              <p class="text-2xl font-bold text-blue-900">{{ teamPerformance.project_completion_rate || 0 }}%</p>
            </div>
          </div>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-green-800">Avg Duration</p>
              <p class="text-2xl font-bold text-green-900">{{ teamPerformance.average_project_duration || 0 }} days</p>
            </div>
          </div>
        </div>
        <div class="md:col-span-2">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performers</h3>
          <div v-if="teamPerformance.top_performers && teamPerformance.top_performers.length > 0" class="space-y-3">
            <div v-for="performer in teamPerformance.top_performers" :key="performer.id" class="flex items-center justify-between bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                  <span class="text-white font-semibold text-sm">{{ performer.name.charAt(0).toUpperCase() }}</span>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ performer.name }}</p>
                  <p class="text-sm text-gray-600">Score: {{ performer.score }}</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                  <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No top performers yet</h3>
            <p class="mt-1 text-sm text-gray-500">Performance data will appear as projects complete.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Allocate Resource Modal -->
    <div v-if="showAllocateModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closeAllocateModal">
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Allocate Resource</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Assign a freelancer to <strong>{{ selectedProject ? selectedProject.title : '' }}</strong></p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="submitAllocation" class="space-y-3">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Select Freelancer</label>
                      <select v-model="allocationForm.freelancer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                        <option value="">Choose a freelancer</option>
                        <option v-for="freelancer in freelancers" :key="freelancer.id" :value="freelancer.id">
                          {{ freelancer.user.name }} - {{ freelancer.skills }}
                        </option>
                      </select>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                      <textarea v-model="allocationForm.notes" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Add any special instructions or notes..."></textarea>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="submitAllocation" :disabled="allocating" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="allocating" class="loading-spinner mr-2"></span>
              Allocate Resource
            </button>
            <button type="button" @click="closeAllocateModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
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
import SkeletonCard from '@/components/ui/SkeletonCard.vue';

export default {
  name: 'ProjectManagerDashboard',
  components: { SkeletonCard },
  data() {
    return {
      activeProjects: [],
      projectReports: [],
      teamPerformance: {},
      freelancers: [],
      loadingProjects: true,
      loadingReports: true,
      loadingPerformance: true,
      loadingFreelancers: true,
      showAllocateModal: false,
      selectedProject: null,
      allocationForm: {
        freelancer_id: '',
        notes: ''
      },
      allocating: false
    };
  },
  mounted() {
    this.fetchActiveProjects();
    this.fetchProjectReports();
    this.fetchTeamPerformance();
    this.fetchFreelancers();
  },
  methods: {
    async fetchActiveProjects() {
      try {
        const response = await api.projectManager.getActiveProjects();
        this.activeProjects = response.data;
      } catch (error) {
        console.error('Error fetching active projects:', error);
        this.$toast.error('Failed to load projects');
      } finally {
        this.loadingProjects = false;
      }
    },
    async fetchProjectReports() {
      try {
        const response = await api.projectManager.getProjectReports();
        this.projectReports = response.data;
      } catch (error) {
        console.error('Error fetching project reports:', error);
        this.$toast.error('Failed to load reports');
      } finally {
        this.loadingReports = false;
      }
    },
    async fetchTeamPerformance() {
      try {
        const response = await api.projectManager.getTeamPerformance();
        this.teamPerformance = response.data;
      } catch (error) {
        console.error('Error fetching team performance:', error);
        this.$toast.error('Failed to load team performance');
      } finally {
        this.loadingPerformance = false;
      }
    },
    async fetchFreelancers() {
      try {
        // This would require a dedicated endpoint in the backend
        // For now, using mock data
        this.freelancers = [
          { id: 1, name: 'John Developer', skills: 'JavaScript, Vue.js', rating: 4.8 },
          { id: 2, name: 'Jane Designer', skills: 'UI/UX Design', rating: 4.9 }
        ];
      } catch (error) {
        console.error('Error fetching freelancers:', error);
        this.$toast.error('Failed to load freelancers');
      } finally {
        this.loadingFreelancers = false;
      }
    },
    allocateResource(project) {
      this.selectedProject = project;
      this.showAllocateModal = true;
    },
    async submitAllocation() {
      if (!this.allocationForm.freelancer_id) {
        this.$toast.error('Please select a freelancer');
        return;
      }

      this.allocating = true;
      try {
        await api.projectManager.allocateResource(this.selectedProject.id, this.allocationForm);
        this.$toast.success('Resource allocated successfully');
        this.closeAllocateModal();
        this.fetchActiveProjects();
        this.fetchProjectReports();
      } catch (error) {
        console.error('Error allocating resource:', error);
        this.$toast.error(error.response?.data?.message || 'Failed to allocate resource');
      } finally {
        this.allocating = false;
      }
    },
    closeAllocateModal() {
      this.showAllocateModal = false;
      this.selectedProject = null;
      this.allocationForm = { freelancer_id: '', notes: '' };
    }
  }
};
</script>

<style scoped>
/* All styles handled by Tailwind CSS */
</style>
