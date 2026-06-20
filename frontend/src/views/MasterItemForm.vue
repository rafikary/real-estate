<template>
  <div class="form-view">
    <div class="card">
      <h2 class="card-title">{{ isEdit ? 'Edit Item' : 'Create Item' }}</h2>
      
      <form @submit.prevent="submitForm" class="form-layout">
        <div class="form-group">
          <label>Code <span class="required">*</span></label>
          <input type="text" v-model="form.code" required class="form-control" />
        </div>
        
        <div class="form-group">
          <label>Name <span class="required">*</span></label>
          <input type="text" v-model="form.name" required class="form-control" />
        </div>

        <div class="form-group">
          <label>Type ID <span class="required">*</span></label>
          <input type="number" v-model="form.type_id" required class="form-control" />
        </div>

        <div class="form-group">
          <label>Group ID <span class="required">*</span></label>
          <input type="number" v-model="form.group_id" required class="form-control" />
        </div>

        <div class="form-group">
          <label>UOM ID <span class="required">*</span></label>
          <input type="number" v-model="form.uom_id" required class="form-control" />
        </div>

        <div class="form-actions full-width">
          <button type="button" class="btn btn-secondary" @click="$router.push('/master/item')">Cancel</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            {{ loading ? 'Saving...' : 'Save' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../utils/api';

const route = useRoute();
const router = useRouter();
const isEdit = computed(() => !!route.params.id);
const loading = ref(false);

const form = ref({
  code: '',
  name: '',
  type_id: null,
  group_id: null,
  uom_id: null
});

onMounted(async () => {
  if (isEdit.value) {
    loading.value = true;
    try {
      const response = await api.get(`/items/${route.params.id}`);
      form.value = response.data;
    } catch (error) {
      console.error('Failed to load record:', error);
      alert('Data tidak ditemukan');
      router.push('/master/item');
    } finally {
      loading.value = false;
    }
  }
});

const submitForm = async () => {
  loading.value = true;
  try {
    if (isEdit.value) {
      await api.put(`/items/${route.params.id}`, form.value);
    } else {
      await api.post('/items', form.value);
    }
    router.push('/master/item');
  } catch (error) {
    console.error('Failed to save:', error);
    alert('Gagal menyimpan data.');
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.form-view { animation: fadeIn 0.3s ease-in-out; }
.form-layout { display: flex; flex-wrap: wrap; gap: 20px; }
.form-group { flex: 1 1 calc(50% - 20px); display: flex; flex-direction: column; }
.full-width { flex: 1 1 100%; }
label { margin-bottom: 5px; font-weight: 500; }
.required { color: red; }
.form-control { padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; }
.form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
