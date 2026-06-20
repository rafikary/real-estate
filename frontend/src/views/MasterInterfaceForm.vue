<template>
  <div class="form-view">
    <div class="card">
      <h2 class="card-title">{{ isEdit ? 'Edit Interface' : 'Create Interface' }}</h2>
      
      <form @submit.prevent="submitForm" class="form-layout">
        <div class="form-group">
          <label>Type <span class="required">*</span></label>
          <input type="text" v-model="form.type" required class="form-control" />
        </div>
        
        <div class="form-group">
          <label>Var ID <span class="required">*</span></label>
          <input type="number" v-model="form.var_id" required class="form-control" />
        </div>

        <div class="form-group full-width">
          <label>Note</label>
          <textarea v-model="form.note" class="form-control" rows="3"></textarea>
        </div>

        <!-- Detail Table -->
        <div class="form-group full-width" style="margin-top: 20px;">
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
            <h3 style="margin: 0; font-size: 16px; color: var(--text-color);">Detail COA</h3>
            <button type="button" class="btn btn-secondary" style="padding: 4px 10px; font-size: 13px;" @click="addDetail">+ Tambah Detail</button>
          </div>
          
          <table class="data-table" style="width: 100%;">
            <thead>
              <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 30%;">COA ID <span class="required">*</span></th>
                <th>Note</th>
                <th style="width: 80px; text-align: center;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="form.details.length === 0">
                <td colspan="4" style="text-align: center; color: #888;">Belum ada detail COA. Klik "Tambah Detail" untuk menambahkan.</td>
              </tr>
              <tr v-for="(detail, index) in form.details" :key="index">
                <td>{{ index + 1 }}</td>
                <td>
                  <input type="number" v-model="detail.m_coa_id" required class="form-control" style="width: 100%;" placeholder="ID COA" />
                </td>
                <td>
                  <input type="text" v-model="detail.note" class="form-control" style="width: 100%;" placeholder="Catatan (opsional)" />
                </td>
                <td style="text-align: center;">
                  <button type="button" class="table-action-btn danger" @click="removeDetail(index)">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="form-actions full-width">
          <button type="button" class="btn btn-secondary" @click="$router.push('/master/interface')">Cancel</button>
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
  type: '',
  var_id: null,
  note: '',
  details: []
});

const addDetail = () => {
  form.value.details.push({
    m_coa_id: null,
    note: ''
  });
};

const removeDetail = (index) => {
  form.value.details.splice(index, 1);
};

onMounted(async () => {
  if (isEdit.value) {
    loading.value = true;
    try {
      const response = await api.get(`/interfaces/${route.params.id}`);
      form.value = response.data;
      if (!form.value.details) {
        form.value.details = [];
      }
    } catch (error) {
      console.error('Failed to load record:', error);
      alert('Data tidak ditemukan');
      router.push('/master/interface');
    } finally {
      loading.value = false;
    }
  }
});

const submitForm = async () => {
  loading.value = true;
  try {
    if (isEdit.value) {
      await api.put(`/interfaces/${route.params.id}`, form.value);
    } else {
      await api.post('/interfaces', form.value);
    }
    router.push('/master/interface');
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
.data-table { border-collapse: collapse; width: 100%; font-size: 14px; }
.data-table th, .data-table td { border: 1px solid #eee; padding: 10px; text-align: left; }
.data-table th { background-color: #fcfcfc; font-weight: 600; color: #555; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
