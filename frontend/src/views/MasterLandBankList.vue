<template>
  <div class="land-bank-list-view">
    <div class="card">
      <h2 class="card-title" style="border: none; margin-bottom: 10px; padding: 0;">Master Land Bank</h2>
      
      <!-- Filter Status Tabs -->
      <div class="tab-filters">
        <button 
          v-for="tab in docStatuses" 
          :key="tab"
          @click="changeStatusFilter(tab)"
          class="tab-btn"
          :class="{ active: activeStatusFilter === tab }"
        >
          {{ tab }}
        </button>
      </div>

      <!-- Action and Search Bar -->
      <div class="action-bar">
        <div class="action-left">
          <div class="input-search-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            <input 
              type="text" 
              placeholder="Search..." 
              v-model="searchQuery" 
              @input="onSearchInput"
            />
          </div>
          <button class="btn-icon" @click="fetchRecords" title="Reset Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          </button>
        </div>

        <div class="action-buttons-group">
          <!-- Export Excel -->
          <button class="btn-export btn-excel" @click="mockExport('Excel')" title="Export to Excel">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/><path d="M8 9h6"/><path d="M8 21h8"/></svg>
            <span>Export Excel</span>
          </button>
          <!-- Export Word -->
          <button class="btn-export btn-word" @click="mockExport('Word')" title="Export to Word">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8v10h2"/><path d="M12 9v10"/><path d="M16 9h-2v10h2"/></svg>
            <span>Export Word</span>
          </button>
          <!-- Export PDF -->
          <button class="btn-export btn-pdf" @click="mockExport('PDF')" title="Export to PDF">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M9 15h3a2 2 0 0 0 2-2v0a2 2 0 0 0-2-2H9v6Z"/></svg>
            <span>Export PDF</span>
          </button>
          <!-- Print -->
          <button class="btn-export btn-print" @click="mockExport('Print')" title="Print">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/><rect x="6" y="14" width="12" height="8" rx="1"/></svg>
            <span>Print</span>
          </button>
          
          <!-- Create New -->
          <router-link to="/master/land-bank/create" class="btn-create">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Create New</span>
          </router-link>
        </div>
      </div>

      <!-- Data Table -->
      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width: 50px;">No</th>
              <th>Kode</th>
              <th>Nama/Lahan</th>
              <th>Alamat Detail</th>
              <th>Kota</th>
              <th>Luas Cert (m²)</th>
              <th>Referensi</th>
              <th>Jenis Sertifikat</th>
              <th>Status Doc</th>
              <th>Status</th>
              <th style="width: 140px; text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="11" style="text-align: center; padding: 30px; color: var(--text-muted);">
                Loading data...
              </td>
            </tr>
            <tr v-else-if="records.length === 0">
              <td colspan="11" style="text-align: center; padding: 30px; color: var(--text-muted);">
                Tidak ada data ditemukan.
              </td>
            </tr>
            <tr v-else v-for="(row, index) in records" :key="row.id">
              <td>{{ (pagination.current_page - 1) * pagination.per_page + index + 1 }}</td>
              <td style="font-weight: 600; color: var(--primary-color);">{{ row.code }}</td>
              <td>{{ row.name }}</td>
              <td>{{ row.loc || '-' }}</td>
              <td>{{ getCityLabel(row.city_id) }}</td>
              <td>{{ formatNumber(row.area_cert) }}</td>
              <td>{{ row.reference || '-' }}</td>
              <td>{{ row.cert_type }}</td>
              <td>
                <span class="badge-status" :class="getStatusDocClass(row.status_doc)">
                  {{ row.status_doc }}
                </span>
              </td>
              <td>
                <span class="badge-status" :class="row.status === 'Aktif' ? 'active' : 'inactive'">
                  {{ row.status }}
                </span>
              </td>
              <td style="text-align: center;">
                <button class="table-action-btn" @click="editRecord(row.id)">Edit</button>
                <button class="table-action-btn danger" @click="deleteRecord(row.id)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="pagination.total > 0">
        <div class="pagination-info">
          Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries
        </div>
        <div class="pagination-controls">
          <button 
            class="pagination-btn" 
            :disabled="pagination.current_page === 1"
            @click="goToPage(pagination.current_page - 1)"
          >
            Previous
          </button>
          
          <button 
            v-for="page in pagination.last_page" 
            :key="page"
            class="pagination-btn"
            :class="{ active: pagination.current_page === page }"
            @click="goToPage(page)"
          >
            {{ page }}
          </button>

          <button 
            class="pagination-btn" 
            :disabled="pagination.current_page === pagination.last_page"
            @click="goToPage(pagination.current_page + 1)"
          >
            Next
          </button>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../utils/api';

const router = useRouter();

const docStatuses = ref(['All', 'Draft', 'In Approval', 'Approved', 'Reject', 'Revisi']);
const activeStatusFilter = ref('All');
const searchQuery = ref('');
const records = ref([]);
const loading = ref(false);

const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 10,
  total: 0,
  from: 0,
  to: 0
});

const getCityLabel = (id) => {
  const map = {
    11: 'Surabaya',
    12: 'Kota Malang',
    21: 'Jakarta Pusat',
    22: 'Jakarta Selatan'
  };
  return map[id] || id || '-';
};

// Search input debouncer
let searchTimeout = null;
const onSearchInput = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.value.current_page = 1;
    fetchRecords();
  }, 350);
};

const changeStatusFilter = (status) => {
  activeStatusFilter.value = status;
  pagination.value.current_page = 1;
  fetchRecords();
};

const fetchRecords = async () => {
  loading.value = true;
  try {
    const params = {
      page: pagination.value.current_page,
      status_doc: activeStatusFilter.value,
      search: searchQuery.value
    };
    
    const response = await api.get('/land-banks', { params });
    const resData = response.data;
    
    records.value = resData.data || [];
    pagination.value = {
      current_page: resData.current_page || 1,
      last_page: resData.last_page || 1,
      per_page: resData.per_page || 10,
      total: resData.total || 0,
      from: resData.from || 0,
      to: resData.to || 0
    };
  } catch (error) {
    console.error('Failed to load land bank records:', error);
  } finally {
    loading.value = false;
  }
};

const formatNumber = (num) => {
  if (!num) return '0';
  return Number(num).toLocaleString('id-ID');
};

const getStatusDocClass = (status) => {
  if (!status) return 'draft';
  const val = status.toLowerCase();
  if (val === 'in approval' || val === 'in-approval') return 'in-approval';
  return val;
};

const editRecord = (id) => {
  router.push(`/master/land-bank/${id}/edit`);
};

const deleteRecord = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data Land Bank ini?')) {
    try {
      await api.delete(`/land-banks/${id}`);
      alert('Data berhasil dihapus');
      fetchRecords();
    } catch (error) {
      console.error('Failed to delete record:', error);
      alert('Gagal menghapus data: ' + (error.response?.data?.message || error.message));
    }
  }
};

const goToPage = (page) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    pagination.value.current_page = page;
    fetchRecords();
  }
};

const mockExport = (format) => {
  alert(`Fungsi ekspor data ke format ${format} sedang disiapkan!`);
};

onMounted(() => {
  fetchRecords();
});
</script>

<style scoped>
.land-bank-list-view {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
