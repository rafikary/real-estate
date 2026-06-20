<template>
  <div class="list-view">
    <div class="card">
      <h2 class="card-title" style="border: none; margin-bottom: 10px; padding: 0;">Master Supplier</h2>
      
      <div class="action-bar">
        <div class="action-left">
          <div class="input-search-group">
            <input type="text" placeholder="Search..." v-model="searchQuery" />
          </div>
          <button class="btn-icon" @click="fetchRecords" title="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </button>
        </div>

        <div class="action-buttons-group">
          <router-link to="/master/supplier/create" class="btn-create">
            <span>Create New</span>
          </router-link>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Code</th>
              <th>Name</th>
              <th>Type ID</th>
              <th>Prov ID</th>
              <th>City ID</th>
              <th>District ID</th>
              <th style="width: 140px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="8" style="text-align: center; padding: 30px;">Loading data...</td>
            </tr>
            <tr v-else-if="records.length === 0">
              <td colspan="8" style="text-align: center; padding: 30px;">Tidak ada data ditemukan.</td>
            </tr>
            <tr v-else v-for="(row, index) in records" :key="row.id">
              <td>{{ index + 1 }}</td>
              <td>{{ row.code }}</td>
              <td>{{ row.name }}</td>
              <td>{{ row.type_id }}</td>
              <td>{{ row.prov_id }}</td>
              <td>{{ row.city_id }}</td>
              <td>{{ row.district_id }}</td>
              <td style="text-align: center;">
                <button class="table-action-btn" @click="editRecord(row.id)">Edit</button>
                <button class="table-action-btn danger" @click="deleteRecord(row.id)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../utils/api';

const router = useRouter();
const searchQuery = ref('');
const records = ref([]);
const loading = ref(false);

const fetchRecords = async () => {
  loading.value = true;
  try {
    const response = await api.get('/suppliers');
    records.value = response.data;
  } catch (error) {
    console.error('Failed to load records:', error);
  } finally {
    loading.value = false;
  }
};

const editRecord = (id) => {
  router.push(`/master/supplier/${id}/edit`);
};

const deleteRecord = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
    try {
      await api.delete(`/suppliers/${id}`);
      fetchRecords();
    } catch (error) {
      console.error('Failed to delete record:', error);
      alert('Gagal menghapus data.');
    }
  }
};

onMounted(() => {
  fetchRecords();
});
</script>

<style scoped>
.list-view { animation: fadeIn 0.3s ease-in-out; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
