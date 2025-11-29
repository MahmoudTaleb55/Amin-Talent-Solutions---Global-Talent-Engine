<template>
  <div class="min-h-screen bg-secondary-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-secondary-900">My Profile</h1>
        <p class="mt-1 text-secondary-600">Update your personal information and preferences</p>
      </div>

      <!-- Profile Card -->
      <div class="bg-white rounded-lg border border-secondary-200 p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8">
          <!-- Avatar Section -->
          <div class="flex flex-col items-center">
            <div v-if="profileData.avatar" class="h-24 w-24 rounded-full overflow-hidden border-4 border-primary-500 mb-4">
              <img :src="profileData.avatar" :alt="profileData.name" class="h-full w-full object-cover" />
            </div>
            <div v-else class="h-24 w-24 rounded-full bg-primary-100 border-4 border-primary-500 flex items-center justify-center mb-4">
              <span class="text-3xl font-bold text-primary-600">{{ profileData.name?.charAt(0).toUpperCase() }}</span>
            </div>
            <label class="cursor-pointer">
              <input type="file" accept="image/*" @change="handleAvatarUpload" class="hidden" />
              <span class="text-primary-600 hover:text-primary-700 font-medium text-sm">Change Photo</span>
            </label>
          </div>

          <!-- Profile Info -->
          <div class="flex-1 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-secondary-700 mb-1">Full Name</label>
                <input v-model="profileData.name" type="text" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
              </div>
              <div>
                <label class="block text-sm font-medium text-secondary-700 mb-1">Email</label>
                <input v-model="profileData.email" type="email" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" disabled />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-secondary-700 mb-1">Role</label>
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center px-3 py-2 rounded-full text-sm font-medium" :class="getRoleBadgeClass(userRole)">
                    {{ getRoleDisplayName(userRole) }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bio and Description -->
      <div class="bg-white rounded-lg border border-secondary-200 p-8 mb-8">
        <h2 class="text-xl font-semibold text-secondary-900 mb-6">About You</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-2">Bio</label>
            <textarea v-model="profileData.bio" rows="4" placeholder="Tell us about yourself..." class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
          </div>

          <!-- Role-Specific Sections -->
          <div v-if="['freelancer', 'project_manager'].includes(userRole)" class="border-t border-secondary-200 pt-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Experience</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-secondary-700 mb-1">Years of Experience</label>
                <input v-model="profileData.yearsExperience" type="number" min="0" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
              </div>
              <div>
                <label class="block text-sm font-medium text-secondary-700 mb-1">Hourly Rate ($)</label>
                <input v-model="profileData.hourlyRate" type="number" min="0" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-secondary-700 mb-1">Skills</label>
                <input v-model="profileData.skills" type="text" placeholder="e.g., JavaScript, React, Node.js (comma-separated)" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
              </div>
            </div>
          </div>

          <!-- Achievements/Projects -->
          <div v-if="['freelancer', 'project_manager', 'company'].includes(userRole)" class="border-t border-secondary-200 pt-6">
            <h3 class="text-lg font-semibold text-secondary-900 mb-4">Achievements</h3>
            <div class="space-y-3">
              <div v-for="(achievement, idx) in profileData.achievements" :key="idx" class="flex items-center gap-2">
                <input v-model="profileData.achievements[idx]" type="text" class="flex-1 px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
                <button @click="removeAchievement(idx)" class="px-3 py-2 bg-danger-100 text-danger-700 rounded-lg hover:bg-danger-200">Remove</button>
              </div>
              <button @click="addAchievement" class="w-full px-3 py-2 border border-secondary-300 text-secondary-700 rounded-lg hover:bg-secondary-50 font-medium">+ Add Achievement</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="bg-white rounded-lg border border-secondary-200 p-8 mb-8">
        <h2 class="text-xl font-semibold text-secondary-900 mb-6">Contact Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-1">Phone</label>
            <input v-model="profileData.phone" type="tel" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-1">Location</label>
            <input v-model="profileData.location" type="text" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-1">LinkedIn URL</label>
            <input v-model="profileData.linkedinUrl" type="url" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
          </div>
          <div>
            <label class="block text-sm font-medium text-secondary-700 mb-1">Website</label>
            <input v-model="profileData.website" type="url" class="w-full px-3 py-2 border border-secondary-300 bg-white text-secondary-900 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" />
          </div>
        </div>
      </div>

      <!-- Resume Import -->
      <div class="bg-white rounded-lg border border-secondary-200 p-8 mb-8">
        <h2 class="text-xl font-semibold text-secondary-900 mb-4">Import Resume</h2>
        <p class="text-sm text-secondary-600 mb-4">Upload a plain-text or Markdown resume to autofill basic fields (name, email, phone, skills).</p>
        <div>
          <label class="cursor-pointer inline-flex items-center gap-3 px-4 py-2 border border-secondary-300 rounded-lg hover:bg-secondary-50">
            <input type="file" accept=".pdf,.doc,.docx,.txt,.md" @change="handleResumeUpload" class="hidden" />
            <span class="text-secondary-700 font-medium">Upload Resume (PDF/DOC/TXT)</span>
          </label>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex gap-4">
        <button @click="saveProfile" :disabled="saving" class="flex-1 px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:bg-gray-400 font-medium transition-colors">
          {{ saving ? 'Saving...' : 'Save Changes' }}
        </button>
        <button @click="cancel" class="flex-1 px-6 py-3 bg-secondary-200 text-secondary-900 rounded-lg hover:bg-secondary-300 font-medium transition-colors">
          Cancel
        </button>
      </div>
      <div class="mt-4">
        <div v-if="userRole === 'freelancer'" class="bg-white p-4 rounded border">
          <h3 class="font-medium mb-2">Payments / Stripe Connect</h3>
          <p class="text-sm text-secondary-600 mb-2">Create or connect your Stripe Express account so you can receive payouts.</p>
          <button @click="createConnectAccount" class="px-4 py-2 bg-primary-600 text-white rounded">Create Connect Account</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useToast } from 'vue-toastification';
import api from '@/services/api';

export default {
  name: 'UserProfile',
  created() {
    this.toast = useToast();
  },
  data() {
    return {
      profileData: {
        name: '',
        email: '',
        bio: '',
        avatar: null,
        yearsExperience: 0,
        hourlyRate: 0,
        skills: '',
        achievements: [],
        phone: '',
        location: '',
        linkedinUrl: '',
        website: ''
      },
      userRole: localStorage.getItem('userRole') || 'freelancer',
      saving: false
    };
  },
  mounted() {
    this.loadProfile();
    const token = localStorage.getItem('authToken');
    if (token) {
      api.getUser().then(res => {
        if (res && res.data) {
          const user = res.data;
          this.profileData = {
            ...this.profileData,
            name: user.name || this.profileData.name,
            email: user.email || this.profileData.email,
            bio: user.bio || this.profileData.bio,
            avatar: user.avatar || this.profileData.avatar,
            yearsExperience: user.years_experience || this.profileData.yearsExperience,
            hourlyRate: user.hourly_rate || this.profileData.hourlyRate,
            skills: user.skills || this.profileData.skills,
            achievements: user.achievements ? JSON.parse(user.achievements || '[]') : this.profileData.achievements,
            phone: user.phone || this.profileData.phone,
            location: user.location || this.profileData.location,
            linkedinUrl: user.linkedin_url || this.profileData.linkedinUrl,
            website: user.website || this.profileData.website
          };
          localStorage.setItem('userProfile', JSON.stringify(this.profileData));
        }
      }).catch(() => {});
    }
  },
  methods: {
    loadProfile() {
      // Load from localStorage or API
      const savedProfile = localStorage.getItem('userProfile');
      if (savedProfile) {
        const parsed = JSON.parse(savedProfile);
        this.profileData = { ...this.profileData, ...parsed };
      }
    },
    handleAvatarUpload(event) {
      const file = event.target.files[0];
      if (file) {
        const form = new FormData();
        form.append('avatar', file);
        api.post('/profile/avatar', form, { headers: { 'Content-Type': 'multipart/form-data' } })
          .then(res => {
            if (res && res.data && res.data.avatar) {
              this.profileData.avatar = res.data.avatar;
              localStorage.setItem('userProfile', JSON.stringify(this.profileData));
              this.toast.success('Photo uploaded');
            }
          }).catch(err => {
            console.error('Avatar upload failed', err);
            this.toast.error('Photo upload failed');
          });
      }
    },
    handleResumeUpload(event) {
      const file = event.target.files[0];
      if (!file) return;
      const form = new FormData();
      form.append('resume', file);
      api.post('/profile/resume', form, { headers: { 'Content-Type': 'multipart/form-data' } })
        .then(res => {
          if (res && res.data && res.data.resume) {
            this.profileData.resume = res.data.resume;
            localStorage.setItem('userProfile', JSON.stringify(this.profileData));
            this.toast.success('Resume uploaded — review fields before saving');
          }
        }).catch(err => {
          console.error('Resume upload failed', err);
          this.toast.error('Resume upload failed');
        });
    },
    addAchievement() {
      this.profileData.achievements.push('');
    },
    removeAchievement(idx) {
      this.profileData.achievements.splice(idx, 1);
    },
    async saveProfile() {
      this.saving = true;
      try {
        // Send profile fields to backend
        const payload = {
          name: this.profileData.name,
          bio: this.profileData.bio,
          years_experience: this.profileData.yearsExperience,
          hourly_rate: this.profileData.hourlyRate,
          skills: this.profileData.skills,
          achievements: this.profileData.achievements,
          phone: this.profileData.phone,
          location: this.profileData.location,
          linkedin_url: this.profileData.linkedinUrl,
          website: this.profileData.website
        };

        await api.put('/profile', payload);
        localStorage.setItem('userProfile', JSON.stringify(this.profileData));
        this.toast.success('Profile updated successfully');
        setTimeout(() => {
          this.$router.back();
        }, 1200);
      } catch (error) {
        console.error('Error saving profile:', error);
        this.toast.error('Failed to save profile');
      } finally {
        this.saving = false;
      }
    },
    cancel() {
      this.$router.back();
    },
    createConnectAccount() {
      api.payments.connect.create().then(r=>{
        if (r.data && r.data.stripe_account_id) {
          this.toast.success('Connected account created');
          // refresh user
          api.getUser().then(res=>{
            // optionally update profileData
            this.profileData = { ...this.profileData, ...{
              stripe_account_id: res.data.stripe_account_id
            }};
          }).catch(()=>{});
        }
      }).catch(err=>{ this.toast.error('Failed to create connected account'); console.error(err); });
    }
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
    getRoleDisplayName(role) {
      const names = {
        'admin': 'Admin',
        'ceo': 'CEO',
        'company': 'Company',
        'freelancer': 'Freelancer',
        'project_manager': 'Project Manager'
      };
      return names[role] || role;
    }
  }
};
</script>

<style scoped>
/* Use Tailwind for styling */
</style>
