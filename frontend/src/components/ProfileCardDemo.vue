<template>
  <div class="min-h-screen bg-secondary-50 dark:bg-secondary-900 py-8 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-secondary-900 dark:text-secondary-50 mb-4">Component Showcase</h1>
        <p class="text-lg text-secondary-600 dark:text-secondary-400 max-w-2xl mx-auto">
          Explore our reusable UI components designed for freelancer profiles. These components are fully customizable, accessible, and optimized for all devices.
        </p>
      </div>

      <!-- Component Overview Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white dark:bg-secondary-800 rounded-xl shadow-soft border border-secondary-200 dark:border-secondary-700 p-6">
          <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="ml-3 font-semibold text-secondary-900 dark:text-secondary-50">Professional</h3>
          </div>
          <p class="text-sm text-secondary-600 dark:text-secondary-400">Production-ready components built with modern design principles and best practices.</p>
        </div>

        <div class="bg-white dark:bg-secondary-800 rounded-xl shadow-soft border border-secondary-200 dark:border-secondary-700 p-6">
          <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-success-100 dark:bg-success-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="ml-3 font-semibold text-secondary-900 dark:text-secondary-50">Accessible</h3>
          </div>
          <p class="text-sm text-secondary-600 dark:text-secondary-400">WCAG compliant with keyboard navigation, ARIA labels, and screen reader support.</p>
        </div>

        <div class="bg-white dark:bg-secondary-800 rounded-xl shadow-soft border border-secondary-200 dark:border-secondary-700 p-6">
          <div class="flex items-center mb-3">
            <div class="w-10 h-10 bg-warning-100 dark:bg-warning-900 rounded-lg flex items-center justify-center">
              <svg class="w-6 h-6 text-warning-600 dark:text-warning-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
              </svg>
            </div>
            <h3 class="ml-3 font-semibold text-secondary-900 dark:text-secondary-50">Responsive</h3>
          </div>
          <p class="text-sm text-secondary-600 dark:text-secondary-400">Mobile-first design that adapts beautifully to any screen size and device.</p>
        </div>
      </div>

      <!-- Freelancer Cards Grid -->
      <div class="mb-12">
        <div class="mb-6">
          <h2 class="text-2xl font-bold text-secondary-900 dark:text-secondary-50 mb-2">Featured Freelancers</h2>
          <p class="text-secondary-600 dark:text-secondary-400">Browse our community of talented professionals ready to help with your next project.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <ProfileCard
            v-for="freelancer in freelancers"
            :key="freelancer.id"
            :freelancer="freelancer"
            @view-profile="handleViewProfile"
            @hire="handleHire"
            class="fade-in-up"
          />
        </div>
      </div>

      <!-- Event Log Section -->
      <div class="bg-white dark:bg-secondary-800 rounded-xl shadow-soft border border-secondary-200 dark:border-secondary-700 p-6">
        <h2 class="text-xl font-bold text-secondary-900 dark:text-secondary-50 mb-4">Interaction Log</h2>
        <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-4">Click "View Profile" or "Hire" buttons on the freelancer cards above to see interactions logged here.</p>
        <div class="space-y-2 max-h-48 overflow-y-auto bg-secondary-50 dark:bg-secondary-900 rounded-lg p-4">
          <div
            v-for="(event, index) in eventLog"
            :key="index"
            class="text-sm bg-white dark:bg-secondary-800 p-3 rounded-md border border-secondary-200 dark:border-secondary-700"
          >
            <span class="font-semibold text-primary-600 dark:text-primary-400">{{ event.type }}</span>
            <span class="text-secondary-700 dark:text-secondary-300 ml-2">{{ event.message }}</span>
            <span class="text-secondary-500 dark:text-secondary-500 text-xs ml-2 float-right">{{ event.timestamp }}</span>
          </div>
          <div v-if="eventLog.length === 0" class="text-secondary-500 dark:text-secondary-400 text-sm italic py-8 text-center">
            No interactions yet. Try clicking a button on one of the profile cards.
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ProfileCard from './ProfileCard.vue';

export default {
  name: 'ProfileCardDemo',
  components: {
    ProfileCard
  },
  data() {
    return {
      eventLog: [],
      sampleFreelancer: {
        id: 1,
        name: 'Sarah Johnson',
        title: 'Full Stack Developer',
        avatar: null,
        rating: 4.9,
        reviews: 127,
        topSkills: ['React', 'Node.js', 'Python', 'AWS', 'MongoDB', 'PostgreSQL'],
        hourlyRate: 65,
        description: 'Experienced full-stack developer with 5+ years building scalable web applications. Specializing in React, Node.js, and cloud technologies. Passionate about clean code and user experience.',
        completedJobs: 89,
        successRate: 98,
        responseTime: '< 1 hour',
        isOnline: true,
        isVerified: true
      },
      freelancers: [
        {
          id: 1,
          name: 'Sarah Johnson',
          title: 'Full Stack Developer',
          avatar: null,
          rating: 4.9,
          reviews: 127,
          topSkills: ['React', 'Node.js', 'Python', 'AWS'],
          hourlyRate: 65,
          description: 'Experienced full-stack developer with 5+ years building scalable web applications.',
          completedJobs: 89,
          successRate: 98,
          responseTime: '< 1 hour',
          isOnline: true,
          isVerified: true
        },
        {
          id: 2,
          name: 'Michael Chen',
          title: 'UI/UX Designer',
          avatar: null,
          rating: 4.8,
          reviews: 95,
          topSkills: ['Figma', 'Adobe XD', 'Sketch', 'Prototyping', 'User Research'],
          hourlyRate: 55,
          description: 'Creative UI/UX designer focused on user-centered design and modern interfaces.',
          completedJobs: 67,
          successRate: 96,
          responseTime: '< 2 hours',
          isOnline: false,
          isVerified: true
        },
        {
          id: 3,
          name: 'Alex Rodriguez',
          title: 'DevOps Engineer',
          avatar: null,
          rating: 4.7,
          reviews: 78,
          topSkills: ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Terraform', 'Jenkins'],
          hourlyRate: 70,
          description: 'DevOps specialist helping teams automate and scale their infrastructure.',
          completedJobs: 54,
          successRate: 97,
          responseTime: '< 3 hours',
          isOnline: true,
          isVerified: false
        },
        {
          id: 4,
          name: 'Emma Wilson',
          title: 'Data Scientist',
          avatar: null,
          rating: 5.0,
          reviews: 156,
          topSkills: ['Python', 'Machine Learning', 'TensorFlow', 'Pandas', 'SQL'],
          hourlyRate: 80,
          description: 'Data scientist with expertise in ML models and predictive analytics.',
          completedJobs: 112,
          successRate: 99,
          responseTime: '< 1 hour',
          isOnline: true,
          isVerified: true
        },
        {
          id: 5,
          name: 'David Kim',
          title: 'Mobile App Developer',
          avatar: null,
          rating: 4.6,
          reviews: 89,
          topSkills: ['React Native', 'Flutter', 'iOS', 'Android', 'Firebase'],
          hourlyRate: 60,
          description: 'Cross-platform mobile developer creating native experiences.',
          completedJobs: 73,
          successRate: 94,
          responseTime: '< 4 hours',
          isOnline: false,
          isVerified: true
        },
        {
          id: 6,
          name: 'Lisa Thompson',
          title: 'Content Writer',
          avatar: null,
          rating: 4.8,
          reviews: 203,
          topSkills: ['SEO', 'Copywriting', 'Blog Writing', 'Technical Writing', 'Marketing'],
          hourlyRate: 45,
          description: 'Professional content writer specializing in SEO-optimized articles and marketing copy.',
          completedJobs: 145,
          successRate: 97,
          responseTime: '< 2 hours',
          isOnline: true,
          isVerified: true
        }
      ]
    };
  },
  methods: {
    handleViewProfile(freelancer) {
      this.addEvent('view-profile', `Viewed profile for ${freelancer.name}`);
    },
    handleHire(freelancer) {
      this.addEvent('hire', `Hired ${freelancer.name} for $${freelancer.hourlyRate}/hr`);
    },
    addEvent(type, message) {
      this.eventLog.unshift({
        type,
        message,
        timestamp: new Date().toLocaleTimeString()
      });
      // Keep only last 10 events
      if (this.eventLog.length > 10) {
        this.eventLog = this.eventLog.slice(0, 10);
      }
    }
  }
};
</script>

<style scoped>
.fade-in-up {
  animation: fadeInUp 0.6s ease-out;
}

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
</style>
