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
            <div v-if="it.attachments && it.attachments.length" class="mt-2">
              <div v-for="(a, idx) in it.attachments" :key="idx">
                <a :href="a" target="_blank" class="text-sm text-primary-600">Attachment {{ idx + 1 }}</a>
              </div>
            </div>
          </div>
          <div>
            <div class="space-y-2">
              <div>
                <input type="file" :ref="`file-${it.id}`" @change="selectFile($event, it.id)" />
                <button @click="uploadAttachment(it.id)" class="ml-2 px-3 py-1 bg-primary-600 text-white rounded">Upload</button>
              </div>
              <div>
                <button @click="remove(it.id)" class="px-3 py-1 bg-danger-600 text-white rounded">Delete</button>
              </div>
            </div>
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
    remove(id) { api.delete(`/portfolios/${id}`).then(()=>this.load()); },
    selectFile(e, id) {
      const file = e.target.files[0];
      if (!file) return;
      this.$set(this, `selectedFile_${id}`, file);
    },
    uploadAttachment(id) {
      const file = this[`selectedFile_${id}`];
      if (!file) { alert('Select a file first'); return; }
      const formData = new FormData();
      formData.append('file', file);
      api.post(`/portfolios/${id}/attachments`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
        .then(()=>{ this.load(); delete this[`selectedFile_${id}`]; })
        .catch(()=>{ alert('Upload failed'); });
    }
  }
}
</script>

<style scoped></style>
