<template>
  <div class="bg-white rounded-xl border border-gray-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
    <!-- Profile Header -->
    <div class="relative">
      <!-- Cover Image Placeholder -->
      <div class="h-24 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

      <!-- Profile Picture -->
      <div class="absolute -bottom-8 left-6">
        <div class="w-16 h-16 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center">
          <img
            v-if="freelancer.avatar"
            :src="freelancer.avatar"
            :alt="freelancer.name"
            class="w-full h-full rounded-full object-cover"
          />
          <div
            v-else
            class="w-full h-full rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl"
          >
            {{ freelancer.name.charAt(0).toUpperCase() }}
          </div>
        </div>
      </div>

      <!-- Online Status -->
      <div
        v-if="freelancer.isOnline"
        class="absolute top-4 right-4 w-3 h-3 bg-green-400 rounded-full border-2 border-white shadow-sm"
        title="Online now"
      ></div>
    </div>

    <!-- Profile Content -->
    <div class="pt-10 px-6 pb-6">
      <!-- Name and Title -->
      <div class="mb-3">
        <h3 class="text-xl font-bold text-gray-900 mb-1">{{ freelancer.name }}</h3>
        <p class="text-gray-600 text-sm">{{ freelancer.title || 'Freelancer' }}</p>
      </div>

      <!-- Rating and Reviews -->
      <div class="flex items-center mb-4">
        <div class="flex items-center">
          <div class="flex text-yellow-400 mr-2">
            <svg
              v-for="star in 5"
              :key="star"
              class="w-4 h-4"
              :class="star <= freelancer.rating ? 'fill-current' : 'text-gray-300'"
              viewBox="0 0 24 24"
            >
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
          </div>
          <span class="text-sm font-medium text-gray-900">{{ freelancer.rating }}</span>
          <span class="text-sm text-gray-500 ml-1">({{ freelancer.reviews }} reviews)</span>
        </div>
      </div>

      <!-- Skills -->
      <div class="mb-4">
        <div class="flex flex-wrap gap-2">
          <span
            v-for="skill in freelancer.topSkills.slice(0, 4)"
            :key="skill"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
          >
            {{ skill }}
          </span>
          <span
            v-if="freelancer.topSkills.length > 4"
            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600"
          >
            +{{ freelancer.topSkills.length - 4 }} more
          </span>
        </div>
      </div>

      <!-- Hourly Rate -->
      <div class="flex items-center justify-between mb-4">
        <div class="text-sm text-gray-600">Hourly Rate</div>
        <div class="text-lg font-bold text-gray-900">${{ freelancer.hourlyRate }}/hr</div>
      </div>

      <!-- Description -->
      <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ freelancer.description }}</p>

      <!-- Stats -->
      <div class="grid grid-cols-3 gap-4 mb-4 text-center">
        <div>
          <div class="text-lg font-bold text-gray-900">{{ freelancer.completedJobs }}</div>
          <div class="text-xs text-gray-500">Jobs Done</div>
        </div>
        <div>
          <div class="text-lg font-bold text-gray-900">{{ freelancer.successRate }}%</div>
          <div class="text-xs text-gray-500">Success Rate</div>
        </div>
        <div>
          <div class="text-lg font-bold text-gray-900">{{ freelancer.responseTime }}</div>
          <div class="text-xs text-gray-500">Response Time</div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex space-x-3">
        <button
          @click="$emit('view-profile', freelancer)"
          class="flex-1 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          View Profile
        </button>
        <button
          @click="$emit('hire', freelancer)"
          class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2 text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Hire Now
        </button>
      </div>
    </div>

    <!-- Verification Badge -->
    <div
      v-if="freelancer.isVerified"
      class="absolute top-4 left-4 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium flex items-center"
    >
      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
      </svg>
      Verified
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfileCard',
  props: {
    freelancer: {
      type: Object,
      required: true,
      validator: function (value) {
        return value.name && typeof value.name === 'string';
      }
    }
  },
  emits: ['view-profile', 'hire']
};
</script>

<style scoped>
.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom animations for enhanced UX */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

/* Hover effects for better interactivity */
.hover-lift {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
