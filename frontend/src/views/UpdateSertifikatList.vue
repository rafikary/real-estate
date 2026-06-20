<template>
  <div class="ups-list-view">
    <div class="card">
      <h2 class="card-title" style="border:none;margin-bottom:10px;padding:0;">Update Sertifikat</h2>

      <!-- Filter Tabs -->
      <div class="tab-filters">
        <button v-for="tab in docStatuses" :key="tab" @click="changeStatusFilter(tab)"
          class="tab-btn" :class="{ active: activeStatusFilter === tab }">{{ tab }}</button>
      </div>

      <!-- Action Bar -->
      <div class="action-bar">
        <div class="action-left">
          <div class="input-search-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Cari kode, no. perubahan, pemilik baru..." v-model="searchQuery" @input="onSearchInput"/>
          </div>
          <button class="btn-icon" @click="fetchRecords" title="Refresh">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          </button>
          <select class="filter-select" v-model="jenisFilter" @change="onFilterChange">
            <option value="">Semua Jenis</option>
            <option v-for="j in jenisOptions" :key="j" :value="j">{{ j }}</option>
          </select>
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
          <router-link to="/master/update-sertifikat/create" class="btn-create">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Create New</span>
          </router-link>
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:50px">No</th>
              <th>Kode</th>
              <th>Land Bank</th>
              <th>Jenis Perubahan</th>
              <th>No Perubahan</th>
              <th>Tgl Perubahan</th>
              <th>Pemilik Baru</th>
              <th>Status Doc</th>
              <th>Status</th>
              <th style="width:130px;text-align:center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="10" style="text-align:center;padding:30px;color:var(--text-muted)">Loading data...</td>
            </tr>
            <tr v-else-if="records.length === 0">
              <td colspan="10" style="text-align:center;padding:40px;color:var(--text-muted)">
                <div style="font-size:2rem;margin-bottom:8px">📋</div>
                Tidak ada data update sertifikat.
              </td>
            </tr>
            <tr v-else v-for="(row, idx) in records" :key="row.id">
              <td>{{ (pagination.current_page - 1) * pagination.per_page + idx + 1 }}</td>
              <td style="font-weight:600;color:var(--primary-color)">{{ row.kode_update }}</td>
              <td>
                <div v-if="row.land_bank">
                  <span style="font-size:0.75rem;color:var(--text-muted)">{{ row.land_bank.code }}</span><br/>
                  {{ row.land_bank.name }}
                </div>
                <span v-else style="color:var(--text-muted)">-</span>
              </td>
              <td>
                <span class="badge-jenis" :class="getJenisClass(row.jenis_perubahan)">{{ row.jenis_perubahan }}</span>
              </td>
              <td>{{ row.no_perubahan || '-' }}</td>
              <td>{{ formatDate(row.tgl_perubahan) }}</td>
              <td>{{ row.pemilik_baru || '-' }}</td>
              <td><span class="badge-status" :class="getStatusDocClass(row.status_doc)">{{ row.status_doc }}</span></td>
              <td><span class="badge-status" :class="row.status === 'Aktif' ? 'active' : 'inactive'">{{ row.status }}</span></td>
              <td style="text-align:center">
                <button class="table-action-btn" @click="editRecord(row.id)">Edit</button>
                <button class="table-action-btn danger" @click="deleteRecord(row.id)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination" v-if="pagination.total > 0">
        <div class="pagination-info">Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries</div>
        <div class="pagination-controls">
          <button class="pagination-btn" :disabled="pagination.current_page === 1" @click="goToPage(pagination.current_page - 1)">Previous</button>
          <button v-for="page in pagination.last_page" :key="page" class="pagination-btn"
            :class="{ active: pagination.current_page === page }" @click="goToPage(page)">{{ page }}</button>
          <button class="pagination-btn" :disabled="pagination.current_page === pagination.last_page" @click="goToPage(pagination.current_page + 1)">Next</button>
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
const jenisOptions = ref(['Balik Nama', 'Perubahan Jenis Hak', 'Perpanjang Masa Sertifikat', 'Penggabungan Tanah', 'Pemisahan Tanah']);
const activeStatusFilter = ref('All');
const jenisFilter = ref('');
const searchQuery = ref('');
const records = ref([]);
const loading = ref(false);
const pagination = ref({ current_page: 1, last_page: 1, per_page: 10, total: 0, from: 0, to: 0 });

let searchTimeout = null;
const onSearchInput = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => { pagination.value.current_page = 1; fetchRecords(); }, 350);
};
const changeStatusFilter = (s) => { activeStatusFilter.value = s; pagination.value.current_page = 1; fetchRecords(); };
const onFilterChange = () => { pagination.value.current_page = 1; fetchRecords(); };

const fetchRecords = async () => {
  loading.value = true;
  try {
    const res = await api.get('/update-sertifikats', {
      params: { page: pagination.value.current_page, status_doc: activeStatusFilter.value, search: searchQuery.value, jenis_perubahan: jenisFilter.value || undefined }
    });
    records.value = res.data.data || [];
    pagination.value = { current_page: res.data.current_page||1, last_page: res.data.last_page||1, per_page: res.data.per_page||10, total: res.data.total||0, from: res.data.from||0, to: res.data.to||0 };
  } catch (e) { console.error(e); } finally { loading.value = false; }
};

const formatDate = (d) => { if (!d) return '-'; return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }); };
const getStatusDocClass = (s) => { if (!s) return 'draft'; const v = s.toLowerCase(); if (v === 'in approval') return 'in-approval'; return v; };
const getJenisClass = (j) => {
  const map = { 'Balik Nama': 'jenis-balik', 'Perubahan Jenis Hak': 'jenis-jenis', 'Perpanjang Masa Sertifikat': 'jenis-perpanjang', 'Penggabungan Tanah': 'jenis-gabung', 'Pemisahan Tanah': 'jenis-pisah' };
  return map[j] || '';
};
const editRecord = (id) => router.push(`/master/update-sertifikat/${id}/edit`);
const deleteRecord = async (id) => {
  if (confirm('Hapus data update sertifikat ini?')) {
    try { await api.delete(`/update-sertifikats/${id}`); alert('Berhasil dihapus'); fetchRecords(); }
    catch (e) { alert('Gagal: ' + (e.response?.data?.message || e.message)); }
  }
};
const goToPage = (p) => { if (p >= 1 && p <= pagination.value.last_page) { pagination.value.current_page = p; fetchRecords(); } };
const mockExport = (f) => alert(`Ekspor ${f} sedang disiapkan!`);
onMounted(fetchRecords);
</script>

<style scoped>
.ups-list-view { animation: fadeIn 0.3s ease-in-out; }
.filter-select { border:1px solid var(--border-color); border-radius:6px; padding:7px 10px; font-size:0.825rem; background:#fff; outline:none; cursor:pointer; min-width:160px; font-family:var(--font-family); color:var(--text-color); }
.badge-jenis { display:inline-flex; align-items:center; padding:3px 8px; font-size:0.7rem; font-weight:600; border-radius:12px; white-space:nowrap; }
.jenis-balik     { background:#ede9fe; color:#6d28d9; }
.jenis-jenis     { background:#fef9c3; color:#854d0e; }
.jenis-perpanjang{ background:#cffafe; color:#0e7490; }
.jenis-gabung    { background:#dcfce7; color:#166534; }
.jenis-pisah     { background:#fee2e2; color:#991b1b; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
