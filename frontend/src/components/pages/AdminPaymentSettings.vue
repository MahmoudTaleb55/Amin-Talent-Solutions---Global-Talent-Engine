<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Payment Settings</h2>
    <form @submit.prevent="save" class="space-y-3 max-w-lg">
      <div>
        <label class="block text-sm">Stripe Secret</label>
        <input v-model="form.stripe_secret" type="password" class="w-full p-2 border rounded" />
      </div>
      <div>
        <label class="block text-sm">Stripe Publishable Key</label>
        <input v-model="form.stripe_publishable_key" class="w-full p-2 border rounded" />
      </div>
      <div>
        <label class="block text-sm">Stripe Webhook Secret</label>
        <input v-model="form.stripe_webhook_secret" type="password" class="w-full p-2 border rounded" />
      </div>
      <div>
        <button class="px-4 py-2 bg-primary-600 text-white rounded">Save</button>
      </div>
    </form>
  </div>
</template>

<script>
import api from '@/services/api';
export default {
  data() { return { form: { stripe_secret: '', stripe_publishable_key: '', stripe_webhook_secret: '' } } },
  mounted() { this.load(); },
  methods: {
    load() {
      api.get('/admin/payment-settings').then(r=>{
        const data = r.data || {};
        this.form.stripe_secret = data.stripe_secret || '';
        this.form.stripe_publishable_key = data.stripe_publishable_key || '';
        this.form.stripe_webhook_secret = data.stripe_webhook_secret || '';
      }).catch(()=>{});
    },
    save() {
      api.put('/admin/payment-settings', this.form).then(()=>{
        alert('Saved');
      }).catch(()=>{ alert('Save failed'); });
    }
  }
}
</script>
