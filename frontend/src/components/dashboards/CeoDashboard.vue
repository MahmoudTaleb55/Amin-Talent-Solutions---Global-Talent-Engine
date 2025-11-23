<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">CEO Dashboard</h1>
          <p class="text-gray-600 mt-1">Executive overview and business insights</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900">${{ metrics.totalRevenue || 0 }}</p>
            <p class="text-xs text-green-600 mt-1">+12% from last month</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Active Users</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.activeUsers || 0 }}</p>
            <p class="text-xs text-blue-600 mt-1">+8% from last month</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Projects Completed</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.completedProjects || 0 }}</p>
            <p class="text-xs text-yellow-600 mt-1">+15% from last month</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Customer Satisfaction</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.customerSatisfaction || 0 }}%</p>
            <p class="text-xs text-purple-600 mt-1">+5% from last month</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Revenue Chart -->
      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
          <select v-model="chartPeriod" class="text-sm border border-gray-300 rounded-md px-3 py-1">
            <option value="7d">Last 7 days</option>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
          </select>
        </div>
        <div class="h-64 flex items-center justify-center text-gray-500">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p class="mt-2 text-sm">Revenue chart will be displayed here</p>
          </div>
        </div>
      </div>

      <!-- User Growth Chart -->
      <div class="bg-white rounded-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">User Growth</h3>
          <select v-model="userChartPeriod" class="text-sm border border-gray-300 rounded-md px-3 py-1">
            <option value="7d">Last 7 days</option>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
          </select>
        </div>
        <div class="h-64 flex items-center justify-center text-gray-500">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <p class="mt-2 text-sm">User growth chart will be displayed here</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
          <p class="text-gray-600 text-sm mt-1">Latest platform activities and updates</p>
        </div>
      </div>

      <div v-if="loadingActivity" class="grid grid-cols-1 gap-4">
        <SkeletonCard v-for="n in 4" :key="`activity-skel-${n}`" />
      </div>

      <div v-else-if="activities.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No recent activity</h3>
        <p class="mt-1 text-sm text-gray-500">Activity will appear here as users interact with the platform.</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="activity in activities" :key="activity.id" class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors duration-200">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
              <span class="text-xs font-medium text-gray-600">{{ activity.user.charAt(0).toUpperCase() }}</span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm text-gray-900">
              <span class="font-medium">{{ activity.user }}</span>
              {{ activity.action }}
            </p>
            <p class="text-sm text-gray-500">{{ formatDate(activity.timestamp) }}</p>
          </div>
          <div class="flex-shrink-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getActivityTypeClass(activity.type)">
              {{ activity.type }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Top Performers</h2>
          <p class="text-gray-600 text-sm mt-1">Highest rated freelancers and most active companies</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Freelancers -->
        <div>
          <h3 class="text-md font-medium text-gray-900 mb-4">Top Freelancers</h3>
          <div v-if="loadingPerformers" class="space-y-3">
            <SkeletonCard v-for="n in 3" :key="`performer-skel-${n}`" />
          </div>
          <div v-else-if="topFreelancers.length === 0" class="text-center py-8">
            <p class="text-sm text-gray-500">No freelancer data available</p>
          </div>
          <div v-else class="space-y-3">
            <div v-for="(freelancer, index) in topFreelancers" :key="freelancer.id" class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-xs font-medium text-blue-600">{{ index + 1 }}</span>
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ freelancer.name }}</p>
                  <p class="text-xs text-gray-500">{{ freelancer.completedProjects }} projects completed</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">{{ freelancer.rating }}/5</p>
                <div class="flex items-center">
                  <svg v-for="star in 5" :key="star" class="w-3 h-3" :class="star <= freelancer.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Top Companies -->
        <div>
          <h3 class="text-md font-medium text-gray-900 mb-4">Top Companies</h3>
          <div v-if="loadingPerformers" class="space-y-3">
            <SkeletonCard v-for="n in 3" :key="`company-skel-${n}`" />
          </div>
          <div v-else-if="topCompanies.length === 0" class="text-center py-8">
            <p class="text-sm text-gray-500">No company data available</p>
          </div>
          <div v-else class="space-y-3">
            <div v-for="(company, index) in topCompanies" :key="company.id" class="flex items-center justify-between p-3 rounded-lg bg-gray-50">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="text-xs font-medium text-green-600">{{ index + 1 }}</span>
                  </div>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ company.name }}</p>
                  <p class="text-xs text-gray-500">{{ company.activeProjects }} active projects</p>
                </div>
              </div>
              <div class="text-right">
                <p class="text-sm font-medium text-gray-900">${{ company.totalSpent }}</p>
                <p class="text-xs text-gray-500">Total spent</p>
              </div>
            </div>
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
  name: 'CeoDashboard',
  components: { SkeletonCard },
  setup() {
    return {
      toast: useToast(),
    };
  },
  data() {
    return {
      metrics: {},
      activities: [],
      topFreelancers: [],
      topCompanies: [],
      loadingActivity: true,
      loadingPerformers: true,
      chartPeriod: '30d',
      userChartPeriod: '30d'
    };
  },
  mounted() {
    this.fetchMetrics();
    this.fetchActivities();
    this.fetchTopPerformers();
  },
  methods: {
    async fetchMetrics() {
      try {
        const [overviewResponse, reportsResponse] = await Promise.all([
          api.ceo.getOverview(),
          api.ceo.getFinancialReports()
        ]);
        
        this.metrics = {
          totalRevenue: reportsResponse.data.total_revenue || 0,
          activeUsers: overviewResponse.data.total_users || 0,
          completedProjects: overviewResponse.data.completed_assignments || 0,
          customerSatisfaction: 92, // Placeholder - would come from separate API
          activeProjects: overviewResponse.data.active_projects || 0,
          totalCompanies: overviewResponse.data.total_companies || 0,
          totalFreelancers: overviewResponse.data.total_freelancers || 0,
          totalAssignments: overviewResponse.data.total_assignments || 0,
        };
        
        this.toast.success('Metrics loaded successfully');
      } catch (error) {
        console.error('Error fetching metrics:', error);
        this.toast.error('Failed to load metrics: ' + (error.response?.data?.message || error.message));
      }
    },
    async fetchActivities() {
      try {
        // For now, use static mock data as we don't have an activities endpoint yet
        this.activities = [
          { type: 'project_completed', description: 'Project Alpha completed', timestamp: new Date() },
          { type: 'user_registration', description: 'New freelancer registered', timestamp: new Date(Date.now() - 3600000) },
          { type: 'payment', description: 'Payment processed', timestamp: new Date(Date.now() - 7200000) }
        ];
      } catch (error) {
        console.error('Error fetching activities:', error);
        this.toast.error('Failed to load activities');
      } finally {
        this.loadingActivity = false;
      }
    },
    async fetchTopPerformers() {
      try {
        // For now use mock data as we don't have top performers endpoints yet
        this.topFreelancers = [
          { id: 1, name: 'John Developer', rating: 4.9, completedProjects: 145 },
          { id: 2, name: 'Jane Designer', rating: 4.8, completedProjects: 98 }
        ];
        this.topCompanies = [
          { id: 1, name: 'Tech Corp', rating: 4.9, activeProjects: 12 },
          { id: 2, name: 'Design Studios', rating: 4.7, activeProjects: 8 }
        ];
      } catch (error) {
        console.error('Error fetching top performers:', error);
        this.toast.error('Failed to load top performers');
      } finally {
        this.loadingPerformers = false;
      }
    },
    getActivityTypeClass(type) {
      const classes = {
        'user_registration': 'bg-blue-100 text-blue-800',
        'project_created': 'bg-green-100 text-green-800',
        'project_completed': 'bg-purple-100 text-purple-800',
        'payment': 'bg-yellow-100 text-yellow-800'
      };
      return classes[type] || 'bg-gray-100 text-gray-800';
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
