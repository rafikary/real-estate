<template>
  <div class="dashboard-container">

    <!-- Welcome Banner -->
    <div class="welcome-banner">
      <div class="welcome-text">
        <div class="welcome-greeting">{{ greeting }}, Admin 👋</div>
        <h2 class="welcome-title">Dashboard Real Estate Management</h2>
        <p class="welcome-sub">Ringkasan data terkini dari semua modul Master. Klik kartu untuk navigasi langsung.</p>
      </div>
      <div class="welcome-illustration">
        <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
          <rect width="80" height="80" rx="16" fill="rgba(255,255,255,0.08)"/>
          <path d="M15 55V35l25-15 25 15v20" stroke="rgba(255,255,255,0.5)" stroke-width="2.5" fill="none"/>
          <rect x="30" y="40" width="20" height="15" rx="2" fill="rgba(255,255,255,0.2)"/>
          <path d="M30 40h20M37 40v15M43 40v15" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"/>
          <path d="M15 35h50" stroke="rgba(255,255,255,0.3)" stroke-width="1"/>
        </svg>
      </div>
    </div>

    <!-- Stat Cards Row 1: Master Modules -->
    <div class="section-label">📦 Ringkasan Modul Master</div>
    <div class="stats-grid-5">

      <!-- Land Bank -->
      <router-link to="/master/land-bank" class="stat-card" style="--accent:#c97b3a">
        <div class="stat-card-icon" style="background:rgba(201,123,58,0.12);color:#c97b3a">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div class="stat-card-info">
          <div class="stat-card-label">Total Land Bank</div>
          <div class="stat-card-value">{{ loading ? '...' : stats.landBank.total }}</div>
        </div>
        <div class="stat-card-sub">
          <span class="mini-badge green">{{ stats.landBank.approved }} Approved</span>
          <span class="mini-badge orange">{{ stats.landBank.pending }} Pending</span>
        </div>
      </router-link>

      <!-- Master Proyek -->
      <router-link to="/master/project" class="stat-card" style="--accent:#6366f1">
        <div class="stat-card-icon" style="background:rgba(99,102,241,0.12);color:#6366f1">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="stat-card-info">
          <div class="stat-card-label">Master Proyek</div>
          <div class="stat-card-value">{{ loading ? '...' : stats.project.total }}</div>
        </div>
        <div class="stat-card-sub">
          <span class="mini-badge green">{{ stats.project.approved }} Approved</span>
          <span class="mini-badge orange">{{ stats.project.pending }} Pending</span>
        </div>
      </router-link>

      <!-- Update Sertifikat -->
      <router-link to="/master/update-sertifikat" class="stat-card" style="--accent:#10b981">
        <div class="stat-card-icon" style="background:rgba(16,185,129,0.12);color:#10b981">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <div class="stat-card-info">
          <div class="stat-card-label">Update Sertifikat</div>
          <div class="stat-card-value">{{ loading ? '...' : stats.updateSert.total }}</div>
        </div>
        <div class="stat-card-sub">
          <span class="mini-badge green">{{ stats.updateSert.approved }} Approved</span>
          <span class="mini-badge gray">{{ stats.updateSert.draft }} Draft</span>
        </div>
      </router-link>

      <!-- Siteplan -->
      <router-link to="/master/siteplan" class="stat-card" style="--accent:#0ea5e9">
        <div class="stat-card-icon" style="background:rgba(14,165,233,0.12);color:#0ea5e9">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
        </div>
        <div class="stat-card-info">
          <div class="stat-card-label">Siteplan</div>
          <div class="stat-card-value">{{ loading ? '...' : stats.siteplan.total }}</div>
        </div>
        <div class="stat-card-sub">
          <span class="mini-badge blue">{{ stats.siteplan.totalUnit }} Total Unit</span>
        </div>
      </router-link>

      <!-- Split / Grouping -->
      <router-link to="/master/split-grouping" class="stat-card" style="--accent:#ef4444">
        <div class="stat-card-icon" style="background:rgba(239,68,68,0.10);color:#ef4444">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <div class="stat-card-info">
          <div class="stat-card-label">Split / Grouping</div>
          <div class="stat-card-value">{{ loading ? '...' : stats.splitGroup.total }}</div>
        </div>
        <div class="stat-card-sub">
          <span class="mini-badge red">{{ stats.splitGroup.split }} Split</span>
          <span class="mini-badge green">{{ stats.splitGroup.grouping }} Grouping</span>
        </div>
      </router-link>

    </div>

    <!-- Row 2: Detail & Quick Actions -->
    <div class="dashboard-row-2">

      <!-- Land Bank Luas Total -->
      <div class="card detail-card">
        <div class="detail-card-header">
          <h4 class="detail-card-title">🏞️ Total Aset Lahan</h4>
        </div>
        <div class="luas-stats">
          <div class="luas-item">
            <div class="luas-label">Total Luas Sertifikat</div>
            <div class="luas-value">{{ formatNumber(stats.landBank.totalLuas) }} <span class="luas-unit">m²</span></div>
          </div>
          <div class="luas-divider"></div>
          <div class="luas-item">
            <div class="luas-label">Nilai Investasi Proyek</div>
            <div class="luas-value inv-value">{{ formatCurrency(stats.project.totalInvestasi) }}</div>
          </div>
        </div>
        <!-- Tipe breakdown -->
        <div class="tipe-breakdown">
          <div v-for="tipe in stats.project.byTipe" :key="tipe.name" class="tipe-row">
            <span class="tipe-name">{{ tipe.name }}</span>
            <div class="tipe-bar-bg">
              <div class="tipe-bar-fill" :style="{ width: tipe.pct + '%', background: tipe.color }"></div>
            </div>
            <span class="tipe-count">{{ tipe.count }}</span>
          </div>
        </div>
      </div>

      <!-- Quick Access -->
      <div class="card detail-card">
        <h4 class="detail-card-title">⚡ Quick Access</h4>
        <div class="quick-links">
          <router-link to="/master/land-bank/create" class="quick-link-item">
            <div class="quick-link-icon" style="background:#fff8f0;color:#c97b3a">+</div>
            <div>
              <div class="quick-link-label">Tambah Land Bank</div>
              <div class="quick-link-sub">Buat data lahan baru</div>
            </div>
          </router-link>
          <router-link to="/master/project/create" class="quick-link-item">
            <div class="quick-link-icon" style="background:#f0f0ff;color:#6366f1">+</div>
            <div>
              <div class="quick-link-label">Tambah Proyek</div>
              <div class="quick-link-sub">Buat master proyek baru</div>
            </div>
          </router-link>
          <router-link to="/master/update-sertifikat/create" class="quick-link-item">
            <div class="quick-link-icon" style="background:#f0fdf4;color:#10b981">+</div>
            <div>
              <div class="quick-link-label">Update Sertifikat</div>
              <div class="quick-link-sub">Balik nama, perpanjang, dll.</div>
            </div>
          </router-link>
          <router-link to="/master/siteplan/create" class="quick-link-item">
            <div class="quick-link-icon" style="background:#f0faff;color:#0ea5e9">+</div>
            <div>
              <div class="quick-link-label">Tambah Siteplan</div>
              <div class="quick-link-sub">Cluster / tipe unit proyek</div>
            </div>
          </router-link>
          <router-link to="/master/split-grouping/create" class="quick-link-item">
            <div class="quick-link-icon" style="background:#fff5f5;color:#ef4444">+</div>
            <div>
              <div class="quick-link-label">Split / Grouping</div>
              <div class="quick-link-sub">Pecah atau gabung lahan</div>
            </div>
          </router-link>
        </div>
      </div>

    </div>

    <!-- Status doc breakdown -->
    <div class="card">
      <h4 class="detail-card-title" style="margin-bottom:16px">📊 Status Dokumen — Land Bank</h4>
      <div class="status-bar-grid">
        <div v-for="s in statusBreakdown" :key="s.label" class="status-bar-item">
          <div class="status-bar-header">
            <span class="status-bar-label" :class="s.cls">{{ s.label }}</span>
            <span class="status-bar-count">{{ s.count }}</span>
          </div>
          <div class="status-bar-bg">
            <div class="status-bar-fill" :style="{ width: s.pct+'%', background: s.color }"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../utils/api';

const loading = ref(true);
const now = new Date();
const hour = now.getHours();
const greeting = hour < 11 ? 'Selamat Pagi' : hour < 15 ? 'Selamat Siang' : hour < 18 ? 'Selamat Sore' : 'Selamat Malam';

const stats = ref({
  landBank:   { total:0, approved:0, pending:0, totalLuas:0 },
  project:    { total:0, approved:0, pending:0, totalInvestasi:0, byTipe:[] },
  updateSert: { total:0, approved:0, draft:0 },
  siteplan:   { total:0, totalUnit:0 },
  splitGroup: { total:0, split:0, grouping:0 },
});

const statusBreakdown = computed(() => {
  const total = stats.value.landBank.total || 1;
  const map = [
    { label: 'Approved',    cls: 'approved',   color: '#10b981', count: stats.value.landBank.approved },
    { label: 'In Approval', cls: 'in-approval', color: '#f59e0b', count: stats.value.landBank.pending },
    { label: 'Draft',       cls: 'draft',       color: '#6b7280', count: stats.value.landBank.draft || 0 },
    { label: 'Reject',      cls: 'reject',      color: '#ef4444', count: stats.value.landBank.reject || 0 },
  ];
  return map.map(m => ({ ...m, pct: Math.round((m.count / total) * 100) }));
});

const formatNumber = (n) => n ? Number(n).toLocaleString('id-ID') : '0';
const formatCurrency = (n) => {
  if (!n || Number(n) === 0) return 'Rp 0';
  const v = Number(n);
  if (v >= 1e12) return 'Rp ' + (v/1e12).toFixed(1) + 'T';
  if (v >= 1e9)  return 'Rp ' + (v/1e9).toFixed(1)  + 'M';
  if (v >= 1e6)  return 'Rp ' + (v/1e6).toFixed(1)  + 'jt';
  return 'Rp ' + v.toLocaleString('id-ID');
};

const tipeColors = { 'Perumahan':'#6366f1', 'Komersial':'#f59e0b', 'Mixed-Use':'#0ea5e9', 'Industri':'#ef4444', 'Apartemen':'#10b981' };

onMounted(async () => {
  try {
    const [lbRes, prjRes, usRes, splRes, sgRes] = await Promise.allSettled([
      api.get('/land-banks', { params: { per_page: 200 } }),
      api.get('/projects',   { params: { per_page: 200 } }),
      api.get('/update-sertifikats', { params: { per_page: 200 } }),
      api.get('/siteplans',  { params: { per_page: 200 } }),
      api.get('/split-grouping-lands', { params: { per_page: 200 } }),
    ]);

    if (lbRes.status === 'fulfilled') {
      const lb = lbRes.value.data.data || [];
      stats.value.landBank = {
        total:    lbRes.value.data.total || lb.length,
        approved: lb.filter(i => i.status_doc === 'Approved').length,
        pending:  lb.filter(i => i.status_doc === 'In Approval').length,
        draft:    lb.filter(i => i.status_doc === 'Draft').length,
        reject:   lb.filter(i => i.status_doc === 'Reject').length,
        totalLuas: lb.reduce((s, i) => s + Number(i.luas_sertifikat || 0), 0),
      };
    }

    if (prjRes.status === 'fulfilled') {
      const pr = prjRes.value.data.data || [];
      const tipeCount = {};
      pr.forEach(p => { tipeCount[p.tipe_proyek] = (tipeCount[p.tipe_proyek]||0)+1; });
      const maxT = Math.max(...Object.values(tipeCount), 1);
      stats.value.project = {
        total:    prjRes.value.data.total || pr.length,
        approved: pr.filter(i => i.status_doc === 'Approved').length,
        pending:  pr.filter(i => i.status_doc === 'In Approval').length,
        totalInvestasi: pr.reduce((s,i) => s + Number(i.nilai_investasi||0), 0),
        byTipe: Object.entries(tipeCount).map(([name, count]) => ({
          name, count, color: tipeColors[name]||'#94a3b8', pct: Math.round((count/maxT)*100)
        })),
      };
    }

    if (usRes.status === 'fulfilled') {
      const us = usRes.value.data.data || [];
      stats.value.updateSert = {
        total:    usRes.value.data.total || us.length,
        approved: us.filter(i => i.status_doc === 'Approved').length,
        draft:    us.filter(i => i.status_doc === 'Draft').length,
      };
    }

    if (splRes.status === 'fulfilled') {
      const sp = splRes.value.data.data || [];
      stats.value.siteplan = {
        total:     splRes.value.data.total || sp.length,
        totalUnit: sp.reduce((s,i) => s + Number(i.jumlah_unit||0), 0),
      };
    }

    if (sgRes.status === 'fulfilled') {
      const sg = sgRes.value.data.data || [];
      stats.value.splitGroup = {
        total:    sgRes.value.data.total || sg.length,
        split:    sg.filter(i => i.tipe === 'Split').length,
        grouping: sg.filter(i => i.tipe === 'Grouping').length,
      };
    }
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.dashboard-container { display:flex; flex-direction:column; gap:20px; animation:fadeIn 0.3s ease; }

/* Welcome Banner */
.welcome-banner {
  background: linear-gradient(135deg, #1e130d 0%, #3d2215 60%, #5c3320 100%);
  border-radius: 14px;
  padding: 28px 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  color: white;
  position: relative;
  overflow: hidden;
}
.welcome-banner::before {
  content:''; position:absolute; right:-20px; top:-20px; width:180px; height:180px;
  background:rgba(255,255,255,0.03); border-radius:50%;
}
.welcome-greeting { font-size:0.85rem; color:rgba(255,255,255,0.6); margin-bottom:4px; }
.welcome-title { font-size:1.4rem; font-weight:700; margin:0 0 6px; }
.welcome-sub { font-size:0.85rem; color:rgba(255,255,255,0.55); margin:0; }

.section-label { font-size:0.78rem; font-weight:700; color:var(--text-muted); letter-spacing:1px; text-transform:uppercase; }

/* Stats Grid 5 */
.stats-grid-5 {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 14px;
}
@media (max-width: 1200px) { .stats-grid-5 { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .stats-grid-5 { grid-template-columns: repeat(2, 1fr); } }

.stat-card {
  background: #fff;
  border-radius: 12px;
  padding: 18px;
  border: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  gap: 12px;
  text-decoration: none;
  transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
  cursor: pointer;
  position: relative;
  overflow: hidden;
}
.stat-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0;
  width: 4px;
  height: 100%;
  background: var(--accent);
  opacity: 0.8;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,0.10); border-color:var(--accent); }
.stat-card-icon { width:44px; height:44px; border-radius:10px; display:flex; align-items:center; justify-content:center; }
.stat-card-info { display:flex; flex-direction:column; gap:2px; }
.stat-card-label { font-size:0.75rem; font-weight:500; color:var(--text-muted); }
.stat-card-value { font-size:2rem; font-weight:700; color:var(--text-color); line-height:1; }
.stat-card-sub { display:flex; gap:6px; flex-wrap:wrap; }

/* Mini Badges */
.mini-badge { font-size:0.68rem; font-weight:600; padding:2px 7px; border-radius:10px; }
.mini-badge.green   { background:#dcfce7; color:#166534; }
.mini-badge.orange  { background:#fef9c3; color:#854d0e; }
.mini-badge.gray    { background:#f3f4f6; color:#374151; }
.mini-badge.blue    { background:#dbeafe; color:#1e40af; }
.mini-badge.red     { background:#fee2e2; color:#991b1b; }

/* Row 2 */
.dashboard-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media (max-width:900px) { .dashboard-row-2 { grid-template-columns:1fr; } }

.detail-card { padding:20px 24px; }
.detail-card-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
.detail-card-title { font-size:0.9rem; font-weight:600; color:var(--text-color); margin:0 0 16px; }

/* Luas stats */
.luas-stats { display:flex; align-items:center; gap:16px; margin-bottom:18px; }
.luas-divider { width:1px; height:48px; background:var(--border-color); }
.luas-item { flex:1; }
.luas-label { font-size:0.72rem; color:var(--text-muted); margin-bottom:4px; }
.luas-value { font-size:1.4rem; font-weight:700; color:var(--text-color); }
.luas-value.inv-value { font-size:1.2rem; color:#c97b3a; }
.luas-unit { font-size:0.85rem; color:var(--text-muted); font-weight:400; }

/* Tipe breakdown */
.tipe-breakdown { display:flex; flex-direction:column; gap:8px; }
.tipe-row { display:flex; align-items:center; gap:10px; }
.tipe-name { font-size:0.75rem; color:var(--text-muted); width:80px; flex-shrink:0; }
.tipe-bar-bg { flex:1; height:6px; background:#f3f4f6; border-radius:4px; overflow:hidden; }
.tipe-bar-fill { height:100%; border-radius:4px; transition:width 0.8s ease; }
.tipe-count { font-size:0.75rem; font-weight:600; color:var(--text-color); width:20px; text-align:right; }

/* Quick links */
.quick-links { display:flex; flex-direction:column; gap:2px; }
.quick-link-item { display:flex; align-items:center; gap:12px; padding:10px 12px; border-radius:8px; text-decoration:none; color:var(--text-color); transition:background 0.15s; }
.quick-link-item:hover { background:#f8fafc; }
.quick-link-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:700; flex-shrink:0; }
.quick-link-label { font-size:0.82rem; font-weight:600; }
.quick-link-sub { font-size:0.72rem; color:var(--text-muted); }

/* Status bar */
.status-bar-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px 24px; }
.status-bar-item { display:flex; flex-direction:column; gap:6px; }
.status-bar-header { display:flex; justify-content:space-between; align-items:center; }
.status-bar-label { font-size:0.75rem; font-weight:600; padding:2px 7px; border-radius:10px; }
.status-bar-label.approved   { background:#dcfce7; color:#166534; }
.status-bar-label.in-approval{ background:#fef9c3; color:#854d0e; }
.status-bar-label.draft      { background:#f3f4f6; color:#374151; }
.status-bar-label.reject     { background:#fee2e2; color:#991b1b; }
.status-bar-count { font-size:0.85rem; font-weight:700; color:var(--text-color); }
.status-bar-bg { height:8px; background:#f3f4f6; border-radius:4px; overflow:hidden; }
.status-bar-fill { height:100%; border-radius:4px; transition:width 0.8s ease; }

@keyframes fadeIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:translateY(0)} }
</style>
