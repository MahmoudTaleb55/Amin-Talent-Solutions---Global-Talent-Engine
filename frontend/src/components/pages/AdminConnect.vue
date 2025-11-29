<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Connect Onboarding</h2>
    <p class="mb-4 text-sm text-secondary-600">Create onboarding links for freelancers so they can finish Stripe Connect setup.</p>
    <div class="space-y-4">
      <div v-for="user in users" :key="user.id" class="p-4 border rounded flex items-center justify-between">
        <div>
          <div class="font-medium">{{ user.name }} <span class="text-sm text-secondary-600">({{ user.email }})</span></div>
          <div class="text-sm text-secondary-500">Role: {{ user.roles && user.roles.length ? user.roles.map(r=>r.name).join(', ') : '—' }}</div>
          <div v-if="user.stripe_account_id" class="text-sm text-green-600">Connected: {{ user.stripe_account_id }}</div>
        </div>
        <div class="space-x-2">
          <button v-if="user.stripe_account_id" @click="openOnboard(user.id)" class="px-3 py-1 bg-primary-600 text-white rounded">Open Onboard</button>
          <button v-else @click="createOnboard(user.id)" class="px-3 py-1 bg-primary-600 text-white rounded">Create Onboard</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';
export default {
  data() {
    return { users: [] };
  },
  mounted() { this.load(); },
  methods: {
    load() {
      api.admin.getUsers().then(r=>{
        this.users = r.data;
      }).catch(()=>{});
    },
    createOnboard(userId) {
      api.payments.connect.onboard(userId).then(r=>{
        const url = r.data.url;
        if (url) window.open(url, '_blank');
      }).catch(err=>{ alert('Failed to create onboarding link'); console.error(err); });
    },
    openOnboard(userId) {
      // If already connected, still try to create account link to manage account
      api.payments.connect.onboard(userId).then(r=>{
        const url = r.data.url;
        if (url) window.open(url, '_blank');
      }).catch(err=>{ alert('Failed to open onboarding/manage link'); console.error(err); });
    }
  }
}
</script>

<style scoped></style>
