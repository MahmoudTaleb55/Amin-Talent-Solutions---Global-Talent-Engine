<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Company Dashboard</h1>
          <p class="text-gray-600 mt-1">Manage your projects, jobs, and applications</p>
        </div>
        <div class="flex items-center space-x-3">
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-sm transition-shadow duration-200">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </div>
          </div>
          <div class="ml-4">
            <h3 class="text-sm font-medium text-gray-900">Post New Job</h3>
            <p class="text-sm text-gray-500">Create a project request</p>
            <button @click="showPostJobModal = true" class="mt-2 text-blue-600 hover:text-blue-500 text-sm font-medium">
              Get started →
            </button>
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
            <h3 class="text-sm font-medium text-gray-900">Active Projects</h3>
            <p class="text-sm text-gray-500">{{ ongoingProjects.length }} ongoing</p>
            <router-link to="#ongoing" class="mt-2 text-green-600 hover:text-green-500 text-sm font-medium">
              View projects →
            </router-link>
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
            <h3 class="text-sm font-medium text-gray-900">Total Spent</h3>
            <p class="text-sm text-gray-500">${{ billing.total_spent || 0 }}</p>
            <router-link to="#billing" class="mt-2 text-yellow-600 hover:text-yellow-500 text-sm font-medium">
              View billing →
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- My Job Posts Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">My Job Posts</h2>
          <p class="text-gray-600 text-sm mt-1">Projects you've posted and their status</p>
        </div>
      </div>

      <div v-if="loadingJobs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SkeletonCard v-for="n in 3" :key="`job-skel-${n}`" />
      </div>

      <div v-else-if="jobs.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No jobs posted yet</h3>
        <p class="mt-1 text-sm text-gray-500">Start by posting your first job to find talented freelancers.</p>
        <button @click="showPostJobModal = true" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
          Post Your First Job
        </button>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div v-for="job in jobs" :key="job.id" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-start justify-between mb-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ job.title }}</h3>
              <p class="text-gray-700 text-sm mb-4 line-clamp-2">{{ job.description }}</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2"
                  :class="job.status === 'open' ? 'bg-green-100 text-green-800' : job.status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800'">
              {{ job.status }}
            </span>
          </div>

          <div class="space-y-3 mb-4">
            <div class="flex items-center text-sm text-gray-600">
              <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
              Budget: ${{ job.budget }}
            </div>
            <div class="flex items-center text-sm text-gray-600">
              <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              Deadline: {{ job.deadline }}
            </div>
            <div class="flex items-center text-sm text-gray-600">
              <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              Applications: {{ job.assignments ? job.assignments.length : 0 }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Applications Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Applications</h2>
          <p class="text-gray-600 text-sm mt-1">Freelancers who have applied to your projects</p>
        </div>
      </div>

      <div v-if="loadingApplications" class="grid grid-cols-1 gap-4">
        <SkeletonCard v-for="n in 3" :key="`app-skel-${n}`" />
      </div>

      <div v-else-if="applications.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No applications yet</h3>
        <p class="mt-1 text-sm text-gray-500">Applications will appear here once freelancers apply to your jobs.</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Freelancer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="application in applications" :key="application.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                      <span class="text-sm font-medium text-gray-700">{{ application.freelancer.user.name.charAt(0).toUpperCase() }}</span>
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ application.freelancer.user.name }}</div>
                    <div class="text-sm text-gray-500">{{ application.freelancer.user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ application.project_request.title }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="application.status === 'assigned' ? 'bg-green-100 text-green-800' : application.status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'">
                  {{ application.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(application.assigned_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-4">View Profile</button>
                <button v-if="application.status === 'pending'" class="text-green-600 hover:text-green-900">Accept</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Ongoing Projects Section -->
    <div id="ongoing" class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Ongoing Projects</h2>
          <p class="text-gray-600 text-sm mt-1">Active projects with freelancer assignments</p>
        </div>
      </div>

      <div v-if="loadingProjects" class="space-y-6">
        <SkeletonCard v-for="n in 2" :key="`ongoing-skel-${n}`" />
      </div>

      <div v-else-if="ongoingProjects.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No ongoing projects</h3>
        <p class="mt-1 text-sm text-gray-500">Projects will appear here once freelancers are assigned.</p>
      </div>

      <div v-else class="space-y-6">
        <div v-for="project in ongoingProjects" :key="project.id" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-sm transition-shadow duration-200">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-gray-900">{{ project.title }}</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
              In Progress
            </span>
          </div>
          <p class="text-gray-700 mb-6">{{ project.description }}</p>

          <div class="space-y-4">
            <div v-for="assignment in project.assignments" :key="assignment.id" class="bg-gray-50 rounded-lg p-4">
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                  <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                    <span class="text-xs font-medium text-gray-700">{{ assignment.freelancer.user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <div>
                    <h4 class="text-sm font-medium text-gray-900">{{ assignment.freelancer.user.name }}</h4>
                    <p class="text-xs text-gray-500">Freelancer</p>
                  </div>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="assignment.status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : assignment.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'">
                  {{ assignment.status }}
                </span>
              </div>

              <div v-if="assignment.deliverables && assignment.deliverables.length > 0" class="mt-4">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Deliverables:</h5>
                <div class="space-y-2">
                  <div v-for="deliverable in assignment.deliverables" :key="deliverable.id" class="flex items-center justify-between bg-white p-3 rounded border">
                    <span class="text-sm text-gray-600">{{ deliverable.description }}</span>
                    <a :href="getFileUrl(deliverable.file_path)" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors duration-200">
                      Download
                    </a>
                  </div>
                </div>
              </div>

              <div v-if="assignment.status_updates && assignment.status_updates.length > 0" class="mt-4">
                <h5 class="text-sm font-medium text-gray-700 mb-2">Recent Updates:</h5>
                <div class="space-y-2">
                  <div v-for="update in assignment.status_updates.slice(-2)" :key="update.id" class="bg-white p-3 rounded border">
                    <div class="flex items-center justify-between">
                      <span class="text-sm font-medium text-gray-900">{{ update.status }}</span>
                      <span class="text-xs text-gray-500">{{ formatDate(update.created_at) }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ update.message }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Billing & Payments Section -->
    <div id="billing" class="bg-white rounded-lg border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h2 class="text-lg font-semibold text-gray-900">Billing & Payments</h2>
          <p class="text-gray-600 text-sm mt-1">Payment history and outstanding balances</p>
        </div>
      </div>

      <div v-if="loadingBilling" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <SkeletonCard v-for="n in 3" :key="`billing-skel-${n}`" />
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-6 border border-yellow-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-yellow-800">Outstanding</p>
              <p class="text-2xl font-bold text-yellow-900">${{ billing.outstanding_payments || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-green-800">Total Spent</p>
              <p class="text-2xl font-bold text-green-900">${{ billing.total_spent || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
          <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div>
              <p class="text-sm font-medium text-blue-800">Transactions</p>
              <p class="text-2xl font-bold text-blue-900">{{ billing.payment_history ? billing.payment_history.length : 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <div v-if="billing.payment_history && billing.payment_history.length > 0">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Transactions</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="payment in billing.payment_history.slice(0, 5)" :key="payment.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${{ payment.amount }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(payment.date) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Completed
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ payment.project || 'N/A' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Post Job Modal -->
    <div v-if="showPostJobModal" class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" @click="closePostJobModal">
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
                <h3 class="text-lg leading-6 font-medium text-gray-900">Post New Job</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Create a new project request to find freelancers</p>
                </div>
                <div class="mt-4">
                  <form @submit.prevent="postJob" class="space-y-4">
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Project Title</label>
                      <input v-model="jobForm.title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g. Website Development" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Description</label>
                      <textarea v-model="jobForm.description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Describe your project requirements..." required></textarea>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Requirements</label>
                      <textarea v-model="jobForm.requirements" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" rows="3" placeholder="Optional requirements or skills needed..."></textarea>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Budget</label>
                      <input v-model.number="jobForm.budget" type="number" min="0" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Enter budget amount" required>
                    </div>
                    <div>
                      <label class="block text-sm font-medium text-gray-700">Deadline</label>
                      <input v-model="jobForm.deadline" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="submit" @click="postJob" :disabled="posting" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 w-full sm:w-auto sm:ml-3">
              <span v-if="posting" class="loading-spinner mr-2"></span>
              Post Job
            </button>
            <button type="button" @click="closePostJobModal" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-md text-sm font-medium border border-gray-300 transition-colors duration-200 w-full sm:w-auto mt-3 sm:mt-0">
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
  name: 'CompanyDashboard',
  components: { SkeletonCard },
  setup() {
    return {
      toast: useToast(),
    };
  },
  data() {
    return {
      jobs: [],
      applications: [],
      ongoingProjects: [],
      billing: {},
      loadingJobs: true,
      loadingApplications: true,
      loadingProjects: true,
      loadingBilling: true,
      showPostJobModal: false,
      jobForm: {
        title: '',
        description: '',
        requirements: '',
        budget: 0,
        deadline: ''
      },
      posting: false
    };
  },
  mounted() {
    this.fetchMyJobs();
    this.fetchApplications();
    this.fetchOngoingProjects();
    this.fetchBilling();
  },
  methods: {
    async fetchMyJobs() {
      try {
        const response = await api.company.getMyJobs();
        this.jobs = response.data;
      } catch (error) {
        console.error('Error fetching jobs:', error);
        this.toast.error('Failed to load jobs');
      } finally {
        this.loadingJobs = false;
      }
    },
    async fetchApplications() {
      try {
        const response = await api.company.getApplications();
        this.applications = response.data;
      } catch (error) {
        console.error('Error fetching applications:', error);
        this.toast.error('Failed to load applications');
      } finally {
        this.loadingApplications = false;
      }
    },
    async fetchOngoingProjects() {
      try {
        const response = await api.company.getOngoingProjects();
        this.ongoingProjects = response.data;
      } catch (error) {
        console.error('Error fetching ongoing projects:', error);
        this.toast.error('Failed to load projects');
      } finally {
        this.loadingProjects = false;
      }
    },
    async fetchBilling() {
      try {
        const response = await api.company.getBilling();
        this.billing = response.data;
      } catch (error) {
        console.error('Error fetching billing:', error);
        this.toast.error('Failed to load billing information');
      } finally {
        this.loadingBilling = false;
      }
    },
    async postJob() {
      this.posting = true;
      try {
        await api.company.postJob(this.jobForm);
        this.toast.success('Job posted successfully');
        this.closePostJobModal();
        this.fetchMyJobs();
      } catch (error) {
        console.error('Error posting job:', error);
        this.toast.error(error.response?.data?.message || 'Failed to post job');
      } finally {
        this.posting = false;
      }
    },
    getFileUrl(filePath) {
      return `http://localhost:8000/storage/${filePath}`;
    },
    closePostJobModal() {
      this.showPostJobModal = false;
      this.jobForm = { title: '', description: '', requirements: '', budget: 0, deadline: '' };
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
