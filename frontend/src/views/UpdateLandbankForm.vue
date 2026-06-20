<template>
  <div class="form-view">
    <div class="card">
      <h2 class="card-title">{{ isEdit ? 'Edit Update Landbank' : 'Create Update Landbank' }}</h2>
      
      <form @submit.prevent="submitForm" class="form-layout">
        <!-- Fields will go here when schema is updated -->
        <div class="form-group full-width">
          <p style="color: var(--text-muted);">
            Schema for this form is currently incomplete. Form fields will be added later.
          </p>
        </div>

        <div class="form-actions full-width">
          <button type="button" class="btn btn-secondary" @click="$router.push('/transaction/update-landbank')">Cancel</button>
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
  // Add fields here
});

onMounted(async () => {
  if (isEdit.value) {
    loading.value = true;
    try {
      const response = await api.get(`/update-landbanks/${route.params.id}`);
      form.value = response.data;
    } catch (error) {
      console.error('Failed to load record:', error);
      alert('Data tidak ditemukan');
      router.push('/transaction/update-landbank');
    } finally {
      loading.value = false;
    }
  }
});

const submitForm = async () => {
  loading.value = true;
  try {
    if (isEdit.value) {
      await api.put(`/update-landbanks/${route.params.id}`, form.value);
    } else {
      await api.post('/update-landbanks', form.value);
    }
    router.push('/transaction/update-landbank');
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
