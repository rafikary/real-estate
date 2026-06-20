<template>
  <div class="spg-list-view">
    <div class="card">
      <h2 class="card-title" style="border:none;margin-bottom:10px;padding:0;">Split / Grouping Lahan</h2>

      <div class="tab-filters">
        <button v-for="tab in docStatuses" :key="tab" @click="changeStatusFilter(tab)"
          class="tab-btn" :class="{ active: activeStatusFilter === tab }">{{ tab }}</button>
      </div>

      <div class="action-bar">
        <div class="action-left">
          <div class="input-search-group">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" placeholder="Cari kode transaksi, notaris, land bank..." v-model="searchQuery" @input="onSearchInput"/>
          </div>
          <button class="btn-icon" @click="fetchRecords" title="Refresh">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
          </button>
          <div class="tipe-toggle">
            <button class="tipe-btn" :class="{ active: tipeFilter === '' }" @click="setTipeFilter('')">Semua</button>
            <button class="tipe-btn split" :class="{ active: tipeFilter === 'Split' }" @click="setTipeFilter('Split')">✂️ Split</button>
            <button class="tipe-btn group" :class="{ active: tipeFilter === 'Grouping' }" @click="setTipeFilter('Grouping')">🔗 Grouping</button>
          </div>
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
          <router-link to="/master/split-grouping/create" class="btn-create">
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
              <th>Kode Transaksi</th>
              <th>Tipe</th>
              <th>Land Bank Sumber</th>
              <th>Tanggal Transaksi</th>
              <th>Notaris</th>
              <th>No. Akta</th>
              <th>Status Doc</th>
              <th style="width:130px;text-align:center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="9" style="text-align:center;padding:30px;color:var(--text-muted)">Loading data...</td>
            </tr>
            <tr v-else-if="records.length === 0">
              <td colspan="9" style="text-align:center;padding:40px;color:var(--text-muted)">
                <div style="font-size:2rem;margin-bottom:8px">✂️</div>
                Tidak ada data split/grouping lahan.
              </td>
            </tr>
            <tr v-else v-for="(row, idx) in records" :key="row.id">
              <td>{{ (pagination.current_page - 1) * pagination.per_page + idx + 1 }}</td>
              <td style="font-weight:600;color:var(--primary-color)">{{ row.kode_transaksi }}</td>
              <td>
                <span class="badge-tipe-spg" :class="row.tipe === 'Split' ? 'tipe-split' : 'tipe-group'">
                  {{ row.tipe === 'Split' ? '✂️ Split' : '🔗 Grouping' }}
                </span>
              </td>
              <td>
                <div v-if="row.land_bank_sumber">
                  <span style="font-size:0.72rem;color:var(--text-muted)">{{ row.land_bank_sumber.code }}</span><br/>
                  {{ row.land_bank_sumber.name }}
                </div>
                <span v-else>-</span>
              </td>
              <td>{{ formatDate(row.tgl_transaksi) }}</td>
              <td>{{ row.nama_notaris || '-' }}</td>
              <td>{{ row.no_akta || '-' }}</td>
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
const activeStatusFilter = ref('All');
const tipeFilter = ref('');
const searchQuery = ref('');
const records = ref([]);
const loading = ref(false);
const pagination = ref({ current_page:1, last_page:1, per_page:10, total:0, from:0, to:0 });

let searchTimeout = null;
const onSearchInput = () => { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => { pagination.value.current_page=1; fetchRecords(); }, 350); };
const changeStatusFilter = (s) => { activeStatusFilter.value=s; pagination.value.current_page=1; fetchRecords(); };
const setTipeFilter = (t) => { tipeFilter.value=t; pagination.value.current_page=1; fetchRecords(); };

const fetchRecords = async () => {
  loading.value = true;
  try {
    const res = await api.get('/split-grouping-lands', { params: { page:pagination.value.current_page, status_doc:activeStatusFilter.value, search:searchQuery.value, tipe:tipeFilter.value||undefined } });
    records.value = res.data.data||[];
    const d=res.data;
    pagination.value = { current_page:d.current_page||1, last_page:d.last_page||1, per_page:d.per_page||10, total:d.total||0, from:d.from||0, to:d.to||0 };
  } catch(e){ console.error(e); } finally{ loading.value=false; }
};

const formatDate = (d) => d ? new Date(d).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'}) : '-';
const getStatusDocClass = (s) => { if(!s) return 'draft'; const v=s.toLowerCase(); if(v==='in approval') return 'in-approval'; return v; };
const editRecord = (id) => router.push(`/master/split-grouping/${id}/edit`);
const deleteRecord = async (id) => { if(confirm('Hapus transaksi ini?')) { try{ await api.delete(`/split-grouping-lands/${id}`); alert('Berhasil dihapus'); fetchRecords(); } catch(e){ alert('Gagal: '+(e.response?.data?.message||e.message)); } } };
const goToPage = (p) => { if(p>=1&&p<=pagination.value.last_page) { pagination.value.current_page=p; fetchRecords(); } };
const mockExport = (f) => alert(`Ekspor ${f} sedang disiapkan!`);
onMounted(fetchRecords);
</script>

<style scoped>
.spg-list-view { animation:fadeIn 0.3s ease-in-out; }
.tipe-toggle { display:flex; gap:4px; }
.tipe-btn { padding:6px 12px; border:1px solid var(--border-color); background:#fff; border-radius:6px; font-size:0.78rem; font-weight:500; cursor:pointer; transition:all 0.2s; color:var(--text-muted); }
.tipe-btn.active { border-color:var(--primary-color); color:var(--primary-color); background:#eff6ff; font-weight:600; }
.tipe-btn.split.active { border-color:#ef4444; color:#ef4444; background:#fef2f2; }
.tipe-btn.group.active { border-color:#10b981; color:#065f46; background:#f0fdf4; }
.badge-tipe-spg { display:inline-flex; align-items:center; padding:3px 10px; font-size:0.72rem; font-weight:600; border-radius:12px; }
.tipe-split { background:#fee2e2; color:#991b1b; }
.tipe-group { background:#dcfce7; color:#166534; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
