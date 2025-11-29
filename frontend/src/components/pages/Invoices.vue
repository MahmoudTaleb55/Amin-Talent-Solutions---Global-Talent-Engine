<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">Invoices</h2>
    <div v-if="invoices.length === 0" class="text-secondary-600">No invoices yet.</div>
    <div class="space-y-4">
      <div v-for="inv in invoices" :key="inv.id" class="p-4 border rounded">
        <div class="flex justify-between items-center">
          <div>
            <div class="font-medium">{{ inv.number }} — {{ inv.currency }} {{ inv.amount }}</div>
            <div class="text-sm text-secondary-600">Status: <strong>{{ inv.status }}</strong></div>
          </div>
          <div>
            <button @click="markPaid(inv.id)" class="px-3 py-1 bg-green-600 text-white rounded mr-2">Mark Paid</button>
            <button @click="release(inv.id)" class="px-3 py-1 bg-blue-600 text-white rounded">Release</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';
export default {
  name: 'InvoicesPage',
  data() {
    return { invoices: [] };
  },
  mounted() {
    this.load();
  },
  methods: {
    load() {
      api.get('/invoices').then(res => { this.invoices = res.data; }).catch(()=>{});
    },
    markPaid(id) { api.post(`/invoices/${id}/paid`).then(()=>this.load()); },
    release(id) { api.post(`/invoices/${id}/release`).then(()=>this.load()); }
  }
}
</script>

<style scoped></style>
