<template>
  <div class="spl-list-view">
    <div class="card">
      <h2 class="card-title" style="border:none;margin-bottom:10px;padding:0;">Master Siteplan</h2>

      <div class="tab-filters">
        <button v-for="tab in docStatuses" :key="tab" @click="changeStatusFilter(tab)"
          class="tab-btn" :class="{ active: activeStatusFilter === tab }">{{ tab }}</button>
      </div>

      <div class="action-bar">
        <div class="action-left">
          <div class="input-search-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Cari kode, nama, tipe unit, cluster..." v-model="searchQuery" @input="onSearchInput"/>
          </div>
          <button class="btn-icon" @click="fetchRecords" title="Refresh">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          </button>
          <select class="filter-select" v-model="tipeFilter" @change="onFilterChange">
            <option value="">Semua Tipe Unit</option>
            <option v-for="t in tipeOptions" :key="t" :value="t">{{ t }}</option>
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
          <router-link to="/master/siteplan/create" class="btn-create">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Create New</span>
          </router-link>
        </div>
      </div>

      <div class="table-wrapper">
        <table class="data-table">
          <thead>
            <tr>
              <th style="width:50px">No</th>
              <th>Kode</th>
              <th>Nama Siteplan</th>
              <th>Proyek</th>
              <th>Tipe Unit</th>
              <th>Cluster</th>
              <th>LT/LB (m²)</th>
              <th>Jml Unit</th>
              <th>Harga Dasar</th>
              <th>Status Doc</th>
              <th style="width:130px;text-align:center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="11" style="text-align:center;padding:30px;color:var(--text-muted)">Loading data...</td>
            </tr>
            <tr v-else-if="records.length === 0">
              <td colspan="11" style="text-align:center;padding:40px;color:var(--text-muted)">
                <div style="font-size:2rem;margin-bottom:8px">🏘️</div>
                Tidak ada data siteplan.
              </td>
            </tr>
            <tr v-else v-for="(row, idx) in records" :key="row.id">
              <td>{{ (pagination.current_page - 1) * pagination.per_page + idx + 1 }}</td>
              <td style="font-weight:600;color:var(--primary-color)">{{ row.kode_siteplan }}</td>
              <td>
                <div style="font-weight:500">{{ row.nama_siteplan }}</div>
                <div v-if="row.deskripsi" style="font-size:0.72rem;color:var(--text-muted)">{{ row.deskripsi.slice(0,40) }}...</div>
              </td>
              <td>
                <div v-if="row.master_project">
                  <span style="font-size:0.72rem;color:var(--text-muted)">{{ row.master_project.kode_proyek }}</span><br/>
                  {{ row.master_project.nama_proyek }}
                </div>
                <span v-else style="color:var(--text-muted)">-</span>
              </td>
              <td><span class="badge-tipe-unit" :class="getTipeUnitClass(row.tipe_unit)">{{ row.tipe_unit }}</span></td>
              <td>{{ row.cluster || '-' }}</td>
              <td>{{ formatNumber(row.luas_tanah) }} / {{ formatNumber(row.luas_bangunan) }}</td>
              <td style="text-align:center;font-weight:600">{{ row.jumlah_unit }}</td>
              <td>{{ formatCurrency(row.harga_dasar) }}</td>
              <td><span class="badge-status" :class="getStatusDocClass(row.status_doc)">{{ row.status_doc }}</span></td>
              <td style="text-align:center">
                <button class="table-action-btn" @click="editRecord(row.id)">Edit</button>
                <button class="table-action-btn danger" @click="deleteRecord(row.id)">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="pagination" v-if="pagination.total > 0">
        <div class="pagination-info">Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} entries</div>
        <div class="pagination-controls">
          <button class="pagination-btn" :disabled="pagination.current_page === 1" @click="goToPage(pagination.current_page - 1)">Previous</button>
          <button v-for="page in pagination.last_page" :key="page" class="pagination-btn" :class="{ active: pagination.current_page === page }" @click="goToPage(page)">{{ page }}</button>
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
const tipeOptions = ref(['Rumah', 'Ruko', 'Kavling', 'Apartemen', 'Gudang', 'Kios']);
const activeStatusFilter = ref('All');
const tipeFilter = ref('');
const searchQuery = ref('');
const records = ref([]);
const loading = ref(false);
const pagination = ref({ current_page:1, last_page:1, per_page:10, total:0, from:0, to:0 });

let searchTimeout = null;
const onSearchInput = () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => { pagination.value.current_page=1; fetchRecords(); }, 350); };
const changeStatusFilter = (s) => { activeStatusFilter.value=s; pagination.value.current_page=1; fetchRecords(); };
const onFilterChange = () => { pagination.value.current_page=1; fetchRecords(); };

const fetchRecords = async () => {
  loading.value = true;
  try {
    const res = await api.get('/siteplans', { params: { page:pagination.value.current_page, status_doc:activeStatusFilter.value, search:searchQuery.value, tipe_unit:tipeFilter.value||undefined } });
    records.value = res.data.data || [];
    const d = res.data;
    pagination.value = { current_page:d.current_page||1, last_page:d.last_page||1, per_page:d.per_page||10, total:d.total||0, from:d.from||0, to:d.to||0 };
  } catch (e) { console.error(e); } finally { loading.value = false; }
};

const formatNumber = (n) => n ? Number(n).toLocaleString('id-ID') : '0';
const formatCurrency = (n) => { if (!n || Number(n)===0) return '-'; const v=Number(n); if(v>=1e9) return 'Rp '+(v/1e9).toFixed(1)+'M'; if(v>=1e6) return 'Rp '+(v/1e6).toFixed(1)+'jt'; return 'Rp '+v.toLocaleString('id-ID'); };
const getStatusDocClass = (s) => { if(!s) return 'draft'; const v=s.toLowerCase(); if(v==='in approval') return 'in-approval'; return v; };
const getTipeUnitClass = (t) => ({ Rumah:'tipe-rumah', Ruko:'tipe-ruko', Kavling:'tipe-kavling', Apartemen:'tipe-apartemen', Gudang:'tipe-gudang', Kios:'tipe-kios' }[t] || '');
const editRecord = (id) => router.push(`/master/siteplan/${id}/edit`);
const deleteRecord = async (id) => { if(confirm('Hapus siteplan ini?')) { try { await api.delete(`/siteplans/${id}`); alert('Berhasil dihapus'); fetchRecords(); } catch(e){ alert('Gagal: '+(e.response?.data?.message||e.message)); } } };
const goToPage = (p) => { if(p>=1&&p<=pagination.value.last_page) { pagination.value.current_page=p; fetchRecords(); } };
const mockExport = (f) => alert(`Ekspor ${f} sedang disiapkan!`);
onMounted(fetchRecords);
</script>

<style scoped>
.spl-list-view { animation:fadeIn 0.3s ease-in-out; }
.filter-select { border:1px solid var(--border-color); border-radius:6px; padding:7px 10px; font-size:0.825rem; background:#fff; outline:none; cursor:pointer; min-width:140px; font-family:var(--font-family); color:var(--text-color); }
.badge-tipe-unit { display:inline-flex; align-items:center; padding:3px 8px; font-size:0.7rem; font-weight:600; border-radius:12px; white-space:nowrap; }
.tipe-rumah     { background:#ede9fe; color:#6d28d9; }
.tipe-ruko      { background:#fef9c3; color:#854d0e; }
.tipe-kavling   { background:#f0fdf4; color:#166534; }
.tipe-apartemen { background:#cffafe; color:#0e7490; }
.tipe-gudang    { background:#fee2e2; color:#991b1b; }
.tipe-kios      { background:#fce7f3; color:#9d174d; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
