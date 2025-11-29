<template>
  <div class="p-6">
    <h2 class="text-2xl font-semibold mb-4">My Portfolio</h2>
    <form @submit.prevent="create" class="mb-4 space-y-2">
      <input v-model="form.title" placeholder="Title" class="w-full p-2 border rounded" />
      <input v-model="form.url" placeholder="URL (optional)" class="w-full p-2 border rounded" />
      <textarea v-model="form.description" placeholder="Description" class="w-full p-2 border rounded"></textarea>
      <button class="px-4 py-2 bg-primary-600 text-white rounded">Add</button>
    </form>

    <div v-if="items.length === 0" class="text-secondary-600">No portfolio items yet.</div>
    <div class="space-y-4">
      <div v-for="it in items" :key="it.id" class="p-4 border rounded">
        <div class="flex justify-between items-start">
          <div>
            <div class="font-medium">{{ it.title }}</div>
            <div class="text-sm text-secondary-600">{{ it.description }}</div>
            <a v-if="it.url" :href="it.url" target="_blank" class="text-sm text-primary-600">Open link</a>
          </div>
          <div>
            <button @click="remove(it.id)" class="px-3 py-1 bg-danger-600 text-white rounded">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';
export default {
  data() { return { items: [], form: { title: '', description: '', url: '' } }; },
  mounted() { this.load(); },
  methods: {
    load() { api.get('/portfolios').then(r=>this.items=r.data).catch(()=>{}); },
    create() { api.post('/portfolios', this.form).then(()=>{ this.form={title:'',description:'',url:''}; this.load(); }); },
    remove(id) { api.delete(`/portfolios/${id}`).then(()=>this.load()); }
  }
}
</script>

<style scoped></style>
