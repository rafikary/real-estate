<template>
  <div class="workflow-view">
    <div class="card">
      <!-- Section Header -->
      <div class="workflow-header">
        <div class="workflow-badge" :style="{ background: sectionData.color + '18', color: sectionData.color }">
          <span class="workflow-icon" v-html="sectionData.icon"></span>
          <span style="font-weight: 700; font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">
            {{ sectionData.category }}
          </span>
        </div>
        <h2 class="card-title workflow-title">{{ sectionData.title }}</h2>
        <p class="workflow-desc">{{ sectionData.description }}</p>
      </div>

      <!-- Process Flow Map (Flowchart context bar) -->
      <div class="flow-context-bar">
        <div class="flow-step" v-for="(step, idx) in sectionData.pipeline" :key="step" :class="{ active: idx === sectionData.currentStepIdx }">
          <span class="step-num">{{ idx + 1 }}</span>
          <span class="step-label">{{ step }}</span>
          <span class="step-chevron" v-if="idx < sectionData.pipeline.length - 1">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
          </span>
        </div>
      </div>

      <!-- Main Layout Columns -->
      <div class="workflow-grid-layout">
        <!-- Left: Interactive Mock Table / Dashboard -->
        <div class="workflow-main-panel">
          <div class="panel-header">
            <h3 class="panel-title">{{ sectionData.listTitle }}</h3>
            <div class="panel-actions">
              <button class="btn-primary-custom" @click="addNewRecord">
                <span>+ Buat {{ sectionData.recordLabel }}</span>
              </button>
            </div>
          </div>

          <!-- Dynamic Mock Data Table -->
          <div class="table-wrapper">
            <table class="data-table">
              <thead>
                <tr>
                  <th style="width: 50px;">No</th>
                  <th v-for="col in sectionData.cols" :key="col.field">{{ col.label }}</th>
                  <th style="width: 130px; text-align: center;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(row, idx) in mockRecords" :key="row.id || idx">
                  <td>{{ idx + 1 }}</td>
                  <td v-for="col in sectionData.cols" :key="col.field">
                    <!-- Custom badge rendering for status -->
                    <template v-if="col.field === 'status'">
                      <span class="badge-status" :class="getStatusClass(row[col.field])">
                        {{ row[col.field] }}
                      </span>
                    </template>
                    <template v-else-if="col.field.includes('nilai') || col.field.includes('harga') || col.field.includes('budget')">
                      {{ formatCurrency(row[col.field]) }}
                    </template>
                    <template v-else-if="col.field.includes('progress')">
                      <div class="progress-bar-container">
                        <div class="progress-bar-fill" :style="{ width: row[col.field] + '%' }"></div>
                        <span class="progress-bar-text">{{ row[col.field] }}%</span>
                      </div>
                    </template>
                    <template v-else>
                      {{ row[col.field] || '-' }}
                    </template>
                  </td>
                  <td style="text-align: center;">
                    <button class="table-action-btn" @click="handleAction(row, idx)">
                      {{ sectionData.actionBtnLabel || 'Verifikasi' }}
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Right: Business Flow Checklist & Meta Info -->
        <div class="workflow-side-panel">
          <!-- Flowchart Verification Checklists -->
          <div class="checklist-card">
            <h4 class="side-title">📝 Checklist Prosedur Flowchart</h4>
            <p class="side-sub">Langkah wajib sebelum melanjutkan ke tahap berikutnya:</p>
            <div class="checklist-items">
              <label class="check-item" v-for="(item, idx) in checklistItems" :key="idx" :class="{ checked: item.checked }">
                <input type="checkbox" v-model="item.checked" />
                <span class="check-box-custom"></span>
                <span class="check-label-text">{{ item.label }}</span>
              </label>
            </div>
            <div class="checklist-progress">
              <div class="checklist-bar">
                <div class="checklist-bar-fill" :style="{ width: checklistProgressPercent + '%' }"></div>
              </div>
              <span class="progress-pct">{{ Math.round(checklistProgressPercent) }}% Selesai</span>
            </div>
          </div>

          <!-- Flowchart Next Actions Details -->
          <div class="info-card-custom">
            <h4 class="side-title">🔗 Keterkaitan Flowchart</h4>
            <div class="flow-connect-box">
              <div class="flow-node prev">
                <div class="node-label">Tahap Sebelumnya</div>
                <div class="node-value">{{ sectionData.prevNode }}</div>
              </div>
              <div class="flow-arrow">⬇️</div>
              <div class="flow-node active-node" :style="{ borderColor: sectionData.color }">
                <div class="node-label">Tahap Aktif</div>
                <div class="node-value" :style="{ color: sectionData.color }">{{ sectionData.title }}</div>
              </div>
              <div class="flow-arrow">⬇️</div>
              <div class="flow-node next">
                <div class="node-label">Tahap Selanjutnya</div>
                <div class="node-value">{{ sectionData.nextNode }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
  section: {
    type: String,
    required: true
  }
});

// Mock records reactive store
const mockRecords = ref([]);
const checklistItems = ref([]);

// Configuration mapper mapping flowchart sections to specific attributes, icons, columns, pipelines
const getSectionConfig = (sectionName) => {
  const configs = {
    // ── Setup ──
    'users': {
      category: 'SETUP',
      title: 'User Management',
      description: 'Manajemen hak akses, autentikasi, serta profil user di Real Estate System.',
      icon: '👥',
      color: '#3b82f6',
      listTitle: 'Daftar Pengguna',
      recordLabel: 'Pengguna',
      prevNode: 'System Config',
      nextNode: 'Access Logs',
      actionBtnLabel: 'Edit',
      pipeline: ['Identitas', 'Hak Akses', 'Aktivasi'],
      currentStepIdx: 1,
      cols: [
        { field: 'name', label: 'Nama Pengguna' },
        { field: 'email', label: 'Email' },
        { field: 'role', label: 'Peran / Hak Akses' },
        { field: 'status', label: 'Status' }
      ],
      items: [
        { name: 'Admin Real Estate', email: 'admin@realestate.com', role: 'Superadmin', status: 'Aktif' },
        { name: 'Notaris Budiman, SH', email: 'budiman@notaris.com', role: 'Legal Partner', status: 'Aktif' },
        { name: 'Siti Rahayu (PIC)', email: 'siti@developer.com', role: 'Project Manager', status: 'Aktif' },
        { name: 'Hendra Kusuma', email: 'hendra@site.com', role: 'Site Manager', status: 'Aktif' }
      ],
      checklists: [
        { label: 'Verifikasi kecocokan email perusahaan', checked: true },
        { label: 'Set peran berdasarkan jobdesk', checked: true },
        { label: 'Kirim link setup password awal', checked: false }
      ]
    },
    'settings': {
      category: 'SETUP',
      title: 'App Settings',
      description: 'Konfigurasi global parameter sistem, mata uang, logo, dan penomoran dokumen.',
      icon: '⚙️',
      color: '#64748b',
      listTitle: 'Konfigurasi Parameter',
      recordLabel: 'Parameter',
      prevNode: 'Database Setup',
      nextNode: 'User Access Control',
      actionBtnLabel: 'Ubah',
      pipeline: ['Profil Aset', 'Konfigurasi Notifikasi', 'Penomoran Dokumen'],
      currentStepIdx: 0,
      cols: [
        { field: 'key', label: 'Nama Parameter' },
        { field: 'val', label: 'Nilai Konfigurasi' },
        { field: 'desc', label: 'Keterangan' }
      ],
      items: [
        { key: 'NAMA_APLIKASI', val: 'Real Estate Management System', desc: 'Nama brand yang muncul di sidebar dan header' },
        { key: 'FORMAT_KODE_PROYEK', val: 'PRJ-{SEQ}', desc: 'Format penomoran kode master proyek otomatis' },
        { key: 'FORMAT_KODE_LANDBANK', val: 'LAND-{SEQ}', desc: 'Format penomoran kode master land bank otomatis' },
        { key: 'BATAS_UPLOAD_LAMPIRAN', val: '10 MB', desc: 'Ukuran berkas maksimum lampiran PDF/Gambar' }
      ],
      checklists: [
        { label: 'Verifikasi nama instansi / developer', checked: true },
        { label: 'Set zona waktu GMT+7 (WIB)', checked: true },
        { label: 'Set default format nilai desimal mata uang', checked: false }
      ]
    },

    // ── Master Placeholders ──
    'cost-project': {
      category: 'MASTER',
      title: 'Master Cost Project',
      description: 'Daftar kode biaya proyek standar (RAB Code Catalog) untuk estimasi dan budgeting.',
      icon: '📁',
      color: '#c97b3a',
      listTitle: 'Katalog Kode Biaya RAB',
      recordLabel: 'Kode Biaya',
      prevNode: 'Master Proyek',
      nextNode: 'Penyusunan RAB',
      actionBtnLabel: 'Edit',
      pipeline: ['Pengelompokan COA', 'Standardisasi Tarif', 'Pemetaan Proyek'],
      currentStepIdx: 0,
      cols: [
        { field: 'code', label: 'Kode Cost' },
        { field: 'name', label: 'Nama Pekerjaan' },
        { field: 'unit', label: 'Satuan' },
        { field: 'base_price', label: 'Harga Satuan Standar' }
      ],
      items: [
        { code: 'A.1.1', name: 'Pekerjaan Persiapan & Pagar Proyek', unit: 'm1', base_price: 350000 },
        { code: 'A.1.2', name: 'Pekerjaan Galian Tanah & Pondasi', unit: 'm3', base_price: 980000 },
        { code: 'B.2.1', name: 'Pekerjaan Struktur Beton Bertulang Dinding', unit: 'm3', base_price: 3800000 },
        { code: 'C.3.1', name: 'Pemasangan Keramik 60x60 Standard', unit: 'm2', base_price: 185000 }
      ],
      checklists: [
        { label: 'Sesuaikan kode cost dengan chart of accounts (COA) keuangan', checked: true },
        { label: 'Lakukan pembaruan harga pasar berkala per wilayah', checked: true },
        { label: 'Set persetujuan dari divisi estimasi proyek', checked: false }
      ]
    },
    'material': {
      category: 'MASTER',
      title: 'Master Material',
      description: 'Katalog spesifikasi bahan material konstruksi standar untuk perencanaan RAB dan RFQ Purchasing.',
      icon: '🪵',
      color: '#c97b3a',
      listTitle: 'Daftar Bahan / Material Konstruksi',
      recordLabel: 'Material',
      prevNode: 'Katalog Cost',
      nextNode: 'RAB & Permintaan Pembelian',
      actionBtnLabel: 'Detail',
      pipeline: ['Klasifikasi Bahan', 'Daftar Supplier Utama', 'Standar Mutu'],
      currentStepIdx: 1,
      cols: [
        { field: 'code', label: 'Kode Barang' },
        { field: 'name', label: 'Nama Material' },
        { field: 'spec', label: 'Spesifikasi Teknik' },
        { field: 'unit', label: 'Satuan' },
        { field: 'price', label: 'Estimasi Harga' }
      ],
      items: [
        { code: 'MAT-001', name: 'Semen PC Portland', spec: 'Gresik / Holcim 50kg', unit: 'Zak', price: 68000 },
        { code: 'MAT-002', name: 'Besi Beton Ulir D10', spec: 'SNI BJTS 420 Panjang 12m', unit: 'Batang', price: 92000 },
        { code: 'MAT-003', name: 'Pasir Pasang Super', spec: 'Pasir Lumajang Grade A', unit: 'm3', price: 280000 },
        { code: 'MAT-004', name: 'Bata Ringan / Celcon', spec: 'Tebal 10cm Standard', unit: 'm3', price: 620000 }
      ],
      checklists: [
        { label: 'Definisikan unit terkecil dan unit order pergudangan', checked: true },
        { label: 'Hubungkan dengan kode COA Inventory', checked: true },
        { label: 'Set minimum stok alert per lokasi proyek', checked: false }
      ]
    },

    // ── Real Estate Transactions ──
    'rab': {
      category: 'REAL ESTATE',
      title: 'Penyusunan RAB',
      description: 'Rencana Anggaran Biaya (RAB) konstruksi per unit, klaster, atau total proyek sebelum penentuan harga.',
      icon: '📝',
      color: '#6366f1',
      listTitle: 'Daftar Dokumen RAB Proyek',
      recordLabel: 'RAB',
      prevNode: 'Master Proyek & Siteplan',
      nextNode: 'Penentuan Harga Proyek',
      actionBtnLabel: 'Persetujuan',
      pipeline: ['Siteplan', 'Penyusunan RAB', 'Penentuan Proyek Harga'],
      currentStepIdx: 1,
      cols: [
        { field: 'code', label: 'No. RAB' },
        { field: 'project', label: 'Nama Proyek / Klaster' },
        { field: 'budget', label: 'Total Estimasi Biaya (RAB)' },
        { field: 'date', label: 'Tgl Pengajuan' },
        { field: 'status', label: 'Status Dokumen' }
      ],
      items: [
        { code: 'RAB-26-001', project: 'Graha Rungkut Indah - Cluster A', budget: 14500000000, date: '2026-06-10', status: 'Approved' },
        { code: 'RAB-26-002', project: 'Ruko Genteng Center - Blok B', budget: 9800000000, date: '2026-06-12', status: 'In Approval' },
        { code: 'RAB-26-003', project: 'Keputih Industrial - Gudang A', budget: 42000000000, date: '2026-06-15', status: 'Draft' }
      ],
      checklists: [
        { label: 'Sesuaikan volume pekerjaan dengan gambar denah Siteplan', checked: true },
        { label: 'Gunakan katalog harga satuan dari Master Cost', checked: true },
        { label: 'Verifikasi total margin contingency proyek', checked: false }
      ]
    },
    'pricing': {
      category: 'REAL ESTATE',
      title: 'Penentuan Harga Proyek',
      description: 'Penetapan harga jual unit proyek, skema cicilan (KPR/Tunai), serta perhitungan margin profit.',
      icon: '🏷️',
      color: '#6366f1',
      listTitle: 'Daftar Harga Jual Unit Real Estate',
      recordLabel: 'Skema Harga',
      prevNode: 'RAB Proyek',
      nextNode: 'Budgeting & Sales Launch',
      actionBtnLabel: 'Setujui',
      pipeline: ['Penyusunan RAB', 'Penentuan Proyek Harga', 'Budgeting'],
      currentStepIdx: 1,
      cols: [
        { field: 'cluster', label: 'Cluster / Blok' },
        { field: 'type', label: 'Tipe Unit' },
        { field: 'cost_rab', label: 'Harga Pokok (RAB)' },
        { field: 'sell_price', label: 'Harga Jual Standar' },
        { field: 'status', label: 'Status Pricing' }
      ],
      items: [
        { cluster: 'Cluster Melati', type: 'Rumah Tipe 36/60', cost_rab: 280000000, sell_price: 450000000, status: 'Approved' },
        { cluster: 'Cluster Mawar', type: 'Rumah Tipe 54/90', cost_rab: 450000000, sell_price: 750000000, status: 'Approved' },
        { cluster: 'Ruko Genteng', type: 'Ruko 3 Lantai Tipe A', cost_rab: 1600000000, sell_price: 2500000000, status: 'Draft' }
      ],
      checklists: [
        { label: 'Analisis harga kompetitor sekitar wilayah proyek', checked: true },
        { label: 'Hitung target margin kotor minimal 35%', checked: true },
        { label: 'Dapatkan tanda tangan persetujuan Direksi Keuangan', checked: false }
      ]
    },
    'budgeting': {
      category: 'REAL ESTATE',
      title: 'Budgeting (COA, Material, Kontraktor)',
      description: 'Alokasi anggaran resmi proyek ke dalam COA keuangan, daftar pembelian material utama, serta kontrak kontraktor.',
      icon: '💵',
      color: '#6366f1',
      listTitle: 'Alokasi Anggaran Proyek Aktif',
      recordLabel: 'Alokasi Budget',
      prevNode: 'Penentuan Proyek Harga',
      nextNode: 'Permintaan Pembelian & SPK',
      actionBtnLabel: 'Alokasikan',
      pipeline: ['Penentuan Proyek Harga', 'Budgeting', 'Permintaan Pembelian'],
      currentStepIdx: 1,
      cols: [
        { field: 'project', label: 'Nama Proyek' },
        { field: 'budget_coa', label: 'Budget Material (COA)' },
        { field: 'budget_spk', label: 'Budget Kontraktor' },
        { field: 'allocated', label: 'Telah Dialokasikan' },
        { field: 'status', label: 'Status Anggaran' }
      ],
      items: [
        { project: 'Perumahan Graha Rungkut Indah', budget_coa: 12000000000, budget_spk: 8000000000, allocated: 'Rp 20.000.000.000', status: 'Approved' },
        { project: 'Ruko Genteng Business Center', budget_coa: 8000000000, budget_spk: 6000000000, allocated: 'Rp 14.000.000.000', status: 'In Approval' }
      ],
      checklists: [
        { label: 'Petakan kode pengeluaran ke nomor COA Akuntansi', checked: true },
        { label: 'Set porsi pengerjaan kontraktor vs swakelola material', checked: true },
        { label: 'Validasi agar tidak melebihi total RAB yang disetujui', checked: false }
      ]
    },
    'progress': {
      category: 'REAL ESTATE',
      title: 'Input & Checklist Progress',
      description: 'Pencatatan perkembangan fisik di lapangan (checklists pembangunan per blok/unit) untuk validasi termin pembayaran kontraktor.',
      icon: '🏗️',
      color: '#6366f1',
      listTitle: 'Monitoring Progress Pembangunan Unit',
      recordLabel: 'Progress',
      prevNode: 'SPK Kontraktor',
      nextNode: 'Sertifikat Pembayaran',
      actionBtnLabel: 'Opname Fisik',
      pipeline: ['Purchase Order Kontraktor', 'Input & Checklist Progress', 'Sertifikat Pembayaran'],
      currentStepIdx: 1,
      cols: [
        { field: 'unit_code', label: 'Nomor Unit / Blok' },
        { field: 'contractor', label: 'Kontraktor Pelaksana' },
        { field: 'progress', label: 'Progress Fisik' },
        { field: 'last_update', label: 'Tgl Opname Terakhir' },
        { field: 'status', label: 'Status Lapangan' }
      ],
      items: [
        { unit_code: 'GRI-A01 (Cluster Melati)', contractor: 'CV. Maju Bangun Nusantara', progress: 100, last_update: '2026-06-18', status: 'Selesai' },
        { unit_code: 'GRI-A02 (Cluster Melati)', contractor: 'CV. Maju Bangun Nusantara', progress: 45, last_update: '2026-06-20', status: 'Konstruksi' },
        { unit_code: 'RTG-B05 (Ruko Genteng)', contractor: 'PT. Prima Struktur Indotama', progress: 12, last_update: '2026-06-19', status: 'Pondasi' }
      ],
      checklists: [
        { label: 'Unggah foto bukti fisik konstruksi lapangan terbaru', checked: true },
        { label: 'Tanda tangan verifikasi dari Pengawas/Site Manager', checked: true },
        { label: 'Kirim notifikasi ke divisi legal jika fisik mencapai progress tertentu', checked: false }
      ]
    },
    'realization': {
      category: 'REAL ESTATE',
      title: 'Project Realisasi',
      description: 'Tahap akhir penyelesaian proyek, serah terima kunci unit ke pembeli (BAST), serta penutupan buku proyek.',
      icon: '🔑',
      color: '#6366f1',
      listTitle: 'Realisasi Serah Terima Kunci Unit (BAST)',
      recordLabel: 'Serah Terima',
      prevNode: 'Berita Acara & Pembayaran',
      nextNode: 'Garansi Unit / Pemeliharaan',
      actionBtnLabel: 'BAST',
      pipeline: ['Sertifikat Pembayaran', 'Berita Acara', 'Project Realisasi'],
      currentStepIdx: 2,
      cols: [
        { field: 'unit', label: 'Unit Real Estate' },
        { field: 'owner', label: 'Nama Pembeli' },
        { field: 'handover_date', label: 'Tanggal Serah Terima' },
        { field: 'doc_no', label: 'Nomor BAST' },
        { field: 'status', label: 'Status Unit' }
      ],
      items: [
        { unit: 'Graha Rungkut Indah - Melati A-01', owner: 'Pak Slamet', handover_date: '2026-06-15', doc_no: 'BAST-2026-0001', status: 'Diserahkan' },
        { unit: 'Graha Rungkut Indah - Melati A-02', owner: 'Ibu Ani', handover_date: '-', doc_no: '-', status: 'Siap Serah Terima' }
      ],
      checklists: [
        { label: 'Lakukan checklist serah terima bersama pembeli (zero defect)', checked: true },
        { label: 'Siapkan kunci unit dan dokumen garansi retensi (pemeliharaan)', checked: true },
        { label: 'Pencatatan realisasi profit & margin akhir proyek di sistem keuangan', checked: false }
      ]
    },

    // ── Purchasing Flow ──
    'requisition': {
      category: 'PURCHASING',
      title: 'Permintaan Pembelian',
      description: 'Form Pengajuan Kebutuhan Barang/Material (Purchase Requisition) dari lapangan ke divisi logistik.',
      icon: '📥',
      color: '#10b981',
      listTitle: 'Daftar Purchase Requisition (PR)',
      recordLabel: 'PR',
      prevNode: 'Budgeting Proyek',
      nextNode: 'RFQ (Request For Quotation)',
      actionBtnLabel: 'Proses RFQ',
      pipeline: ['Budgeting', 'Permintaan Pembelian', 'RFQ'],
      currentStepIdx: 1,
      cols: [
        { field: 'pr_no', label: 'No. PR' },
        { field: 'project', label: 'Untuk Proyek' },
        { field: 'items', label: 'Rincian Material' },
        { field: 'date', label: 'Tgl Pengajuan' },
        { field: 'status', label: 'Status PR' }
      ],
      items: [
        { pr_no: 'PR-26-001', project: 'Graha Rungkut Indah', items: 'Semen PC Portland Gresik (200 Zak)', date: '2026-06-18', status: 'Approved' },
        { pr_no: 'PR-26-002', project: 'Ruko Genteng Center', items: 'Besi Beton D10 SNI (150 Batang)', date: '2026-06-20', status: 'In Approval' }
      ],
      checklists: [
        { label: 'Verifikasi ketersediaan budget material di klaster terkait', checked: true },
        { label: 'Periksa spesifikasi minimum barang yang diminta', checked: true },
        { label: 'Mendapat persetujuan Site Manager pelaksana', checked: false }
      ]
    },
    'rfq': {
      category: 'PURCHASING',
      title: 'RFQ (Request For Quotation)',
      description: 'Pengiriman Request For Quotation (permintaan penawaran harga) ke beberapa supplier mitra.',
      icon: '✉️',
      color: '#10b981',
      listTitle: 'Daftar Request for Quotation (RFQ)',
      recordLabel: 'RFQ',
      prevNode: 'Permintaan Pembelian',
      nextNode: 'Supplier Quotation',
      actionBtnLabel: 'Kirim',
      pipeline: ['Permintaan Pembelian', 'RFQ', 'Supplier Quotation'],
      currentStepIdx: 1,
      cols: [
        { field: 'rfq_no', label: 'No. RFQ' },
        { field: 'material', label: 'Kebutuhan Material' },
        { field: 'suppliers', label: 'Supplier yang Diundang' },
        { field: 'deadline', label: 'Batas Penawaran' },
        { field: 'status', label: 'Status RFQ' }
      ],
      items: [
        { rfq_no: 'RFQ-26-001', material: 'Semen PC Portland (200 Zak)', suppliers: 'CV. Semen Jaya, PT. Cahaya Bangun, Toko Sumber Mas', deadline: '2026-06-23', status: 'Dikirim' },
        { rfq_no: 'RFQ-26-002', material: 'Besi Beton D10 (150 Batang)', suppliers: 'PT. Baja Utama, CV. Logam Abadi', deadline: '2026-06-25', status: 'Draft' }
      ],
      checklists: [
        { label: 'Pilih minimal 3 supplier mitra untuk persaingan harga sehat', checked: true },
        { label: 'Lampirkan detail spesifikasi teknis material dengan jelas', checked: true },
        { label: 'Tentukan tanggal batas akhir pengiriman quotation', checked: false }
      ]
    },
    'quotation': {
      category: 'PURCHASING',
      title: 'Supplier Quotation',
      description: 'Penerimaan penawaran harga dari supplier, komparasi harga/kualitas, serta pemilihan pemenang oleh atasan.',
      icon: '📊',
      color: '#10b981',
      listTitle: 'Perbandingan Penawaran Supplier (Quotation)',
      recordLabel: 'Evaluasi',
      prevNode: 'RFQ Supplier',
      nextNode: 'Persetujuan PO / MOU',
      actionBtnLabel: 'Pilih',
      pipeline: ['RFQ', 'Supplier Quotation', 'Approve Atasan'],
      currentStepIdx: 1,
      cols: [
        { field: 'supplier', label: 'Nama Supplier' },
        { field: 'item', label: 'Material Ditawarkan' },
        { field: 'price_unit', label: 'Harga Satuan' },
        { field: 'delivery_time', label: 'Waktu Pengiriman' },
        { field: 'status', label: 'Status Evaluasi' }
      ],
      items: [
        { supplier: 'CV. Semen Jaya', item: 'Semen Gresik 50kg (200 Zak)', price_unit: 65000, delivery_time: '2 Hari', status: 'Pemenang (Terpilih)' },
        { supplier: 'PT. Cahaya Bangun', item: 'Semen Gresik 50kg (200 Zak)', price_unit: 67000, delivery_time: '1 Hari', status: 'Ditolak' },
        { supplier: 'Toko Sumber Mas', item: 'Semen Gresik 50kg (200 Zak)', price_unit: 66500, delivery_time: '3 Hari', status: 'Ditolak' }
      ],
      checklists: [
        { label: 'Periksa kelengkapan administrasi supplier (NPWP, SIUP)', checked: true },
        { label: 'Bandingkan total harga penawaran terendah', checked: true },
        { label: 'Konfirmasi ketersediaan armada pengiriman material ke site', checked: false }
      ]
    },
    'mou': {
      category: 'PURCHASING',
      title: 'MOU (untuk material tertentu)',
      description: 'Nota Kesepahaman / Kontrak Payung dengan supplier utama untuk mengamankan harga dan stok material berjangka panjang.',
      icon: '🤝',
      color: '#10b981',
      listTitle: 'Daftar Kontrak Payung / MOU Aktif',
      recordLabel: 'MOU',
      prevNode: 'Approve Atasan',
      nextNode: 'Purchase Order',
      actionBtnLabel: 'Detail',
      pipeline: ['Supplier Quotation', 'MOU', 'Purchase Order'],
      currentStepIdx: 1,
      cols: [
        { field: 'mou_no', label: 'No. MOU' },
        { field: 'vendor', label: 'Supplier / Vendor Mitra' },
        { field: 'material_group', label: 'Kelompok Material' },
        { field: 'valid_to', label: 'Berlaku Sampai' },
        { field: 'status', label: 'Status Kontrak' }
      ],
      items: [
        { mou_no: 'MOU-2026-001', vendor: 'PT. Semen Indonesia Tbk', material_group: 'Semen Portland Gresik bulk', valid_to: '2027-06-20', status: 'Aktif' },
        { mou_no: 'MOU-2026-002', vendor: 'PT. Krakatau Steel Tbk', material_group: 'Besi Beton & Wiremesh', valid_to: '2026-12-31', status: 'Aktif' }
      ],
      checklists: [
        { label: 'Sepakati harga tetap untuk periode kontrak minimal 6 bulan', checked: true },
        { label: 'Tentukan penalti / ganti rugi keterlambatan stok di site', checked: true },
        { label: 'Legalitas disahkan oleh Direktur Operasional & Supplier', checked: false }
      ]
    },
    'po': {
      category: 'PURCHASING',
      title: 'Purchase Order',
      description: 'Penerbitan dokumen pemesanan resmi (Purchase Order) kepada supplier terpilih untuk pengiriman material.',
      icon: '📦',
      color: '#10b981',
      listTitle: 'Daftar Purchase Order (PO) Material',
      recordLabel: 'PO',
      prevNode: 'Persetujuan / MOU',
      nextNode: 'Tanda Terima Material',
      actionBtnLabel: 'Cetak PO',
      pipeline: ['MOU', 'Purchase Order', 'Tanda Terima Material'],
      currentStepIdx: 1,
      cols: [
        { field: 'po_no', label: 'No. PO' },
        { field: 'vendor', label: 'Vendor / Supplier' },
        { field: 'total_amount', label: 'Total Pembelian' },
        { field: 'date', label: 'Tanggal PO' },
        { field: 'status', label: 'Status PO' }
      ],
      items: [
        { po_no: 'PO-26-0001', vendor: 'CV. Semen Jaya', total_amount: 13000000, date: '2026-06-20', status: 'Disetujui' },
        { po_no: 'PO-26-0002', vendor: 'PT. Baja Utama', total_amount: 13800000, date: '2026-06-20', status: 'Menunggu' }
      ],
      checklists: [
        { label: 'Pastikan spesifikasi item sesuai dengan Quotation terpilih', checked: true },
        { label: 'Verifikasi alamat proyek pengiriman material di PO', checked: true },
        { label: 'Validasi total nominal agar tidak melebihi sisa budgeting', checked: false }
      ]
    },
    'receipt': {
      category: 'PURCHASING',
      title: 'Tanda Terima Material',
      description: 'Pencatatan penerimaan material di lapangan (surat jalan), pemeriksaan kuantitas dan kualitas barang.',
      icon: '🚚',
      color: '#10b981',
      listTitle: 'Catatan Penerimaan Barang / Material',
      recordLabel: 'Penerimaan',
      prevNode: 'Purchase Order',
      nextNode: 'Tanda Terima Tagihan',
      actionBtnLabel: 'Periksa',
      pipeline: ['Purchase Order', 'Tanda Terima Material', 'Tanda Terima Tagihan'],
      currentStepIdx: 1,
      cols: [
        { field: 'sj_no', label: 'No. Surat Jalan' },
        { field: 'po_ref', label: 'Referensi PO' },
        { field: 'vendor', label: 'Supplier Pengirim' },
        { field: 'received_date', label: 'Tgl Diterima' },
        { field: 'status', label: 'Status Mutu' }
      ],
      items: [
        { sj_no: 'SJ-019283', po_ref: 'PO-26-0001', vendor: 'CV. Semen Jaya', received_date: '2026-06-20', status: 'Diterima Penuh' }
      ],
      checklists: [
        { label: 'Periksa kuantitas fisik datang vs kuantitas di surat jalan & PO', checked: true },
        { label: 'Periksa keutuhan kemasan material (misal zak semen tidak pecah)', checked: true },
        { label: 'Tanda tangani berita acara serah terima barang (sj) oleh logistik site', checked: false }
      ]
    },
    'payment-cert': {
      category: 'PURCHASING',
      title: 'Sertifikat Pembayaran',
      description: 'Penerbitan Payment Certificate (sertifikat kemajuan pekerjaan) untuk pembayaran termin sub-kontraktor / pemborong.',
      icon: '🎗️',
      color: '#10b981',
      listTitle: 'Daftar Sertifikat Pembayaran Kontraktor',
      recordLabel: 'Sertifikat',
      prevNode: 'Input & Checklist Progress',
      nextNode: 'Berita Acara Serah Terima',
      actionBtnLabel: 'Rilis Cert',
      pipeline: ['Input & Checklist Progress', 'Sertifikat Pembayaran', 'Berita Acara'],
      currentStepIdx: 1,
      cols: [
        { field: 'cert_no', label: 'No. Sertifikat' },
        { field: 'contractor', label: 'Nama Kontraktor' },
        { field: 'target_termin', label: 'Termin Ke' },
        { field: 'val_amount', label: 'Nominal Sertifikat' },
        { field: 'status', label: 'Status' }
      ],
      items: [
        { cert_no: 'CERT-2026-0001', contractor: 'CV. Maju Bangun Nusantara', target_termin: 'Termin 1 (Fisik 30%)', val_amount: 2400000000, status: 'Approved' }
      ],
      checklists: [
        { label: 'Cocokkan progress fisik lapangan dengan laporan checklist progress', checked: true },
        { label: 'Hitung potongan uang jaminan (retensi 5%)', checked: true },
        { label: 'Dapatkan persetujuan Site Manager & QS Manager', checked: false }
      ]
    },
    'handover': {
      category: 'PURCHASING',
      title: 'Berita Acara',
      description: 'Penyusunan Berita Acara Serah Terima (BAST) hasil pengerjaan kontraktor atau berita acara penerimaan material.',
      icon: '📝',
      color: '#10b981',
      listTitle: 'Berita Acara Serah Terima Pekerjaan (BAST)',
      recordLabel: 'BAST',
      prevNode: 'Sertifikat Pembayaran',
      nextNode: 'Tanda Terima Tagihan',
      actionBtnLabel: 'Lihat BAST',
      pipeline: ['Sertifikat Pembayaran', 'Berita Acara', 'Tanda Terima Tagihan'],
      currentStepIdx: 1,
      cols: [
        { field: 'bast_no', label: 'No. BAST' },
        { field: 'contractor', label: 'Kontraktor' },
        { field: 'description', label: 'Pekerjaan yang Diserahkan' },
        { field: 'date', label: 'Tgl BAST' },
        { field: 'status', label: 'Status BAST' }
      ],
      items: [
        { bast_no: 'BAST-2026-0012', contractor: 'CV. Maju Bangun Nusantara', description: 'Pekerjaan Pondasi Klaster Melati A-01 s/d A-10', date: '2026-06-18', status: 'Disetujui' }
      ],
      checklists: [
        { label: 'Lakukan audit lapangan bersama tim perencana & kontraktor', checked: true },
        { label: 'Catat defect list (daftar kerusakan minor yang perlu diperbaiki)', checked: true },
        { label: 'Tanda tangan bersama di atas meterai 10000 oleh kedua belah pihak', checked: false }
      ]
    },
    'invoice': {
      category: 'PURCHASING',
      title: 'Tanda Terima Tagihan',
      description: 'Pengarsipan dan verifikasi invoice supplier / sub-kontraktor beserta kelengkapan berkas tagihan (Faktur Pajak, PO, Kuitansi).',
      icon: '💵',
      color: '#10b981',
      listTitle: 'Tagihan / Invoice Diterima (Finance)',
      recordLabel: 'Tagihan',
      prevNode: 'Berita Acara / Surat Jalan',
      nextNode: 'Pembayaran Hutang',
      actionBtnLabel: 'Verifikasi',
      pipeline: ['Berita Acara', 'Tanda Terima Tagihan', 'Pembayaran Hutang'],
      currentStepIdx: 1,
      cols: [
        { field: 'inv_no', label: 'No. Invoice' },
        { field: 'vendor', label: 'Nama Supplier / Kontraktor' },
        { field: 'amount', label: 'Nominal Tagihan' },
        { field: 'due_date', label: 'Jatuh Tempo' },
        { field: 'status', label: 'Verifikasi' }
      ],
      items: [
        { inv_no: 'INV-2026-0928', vendor: 'CV. Semen Jaya', amount: 13000000, due_date: '2026-07-20', status: 'Terverifikasi' },
        { inv_no: 'INV-CONT-1102', vendor: 'CV. Maju Bangun Nusantara', amount: 2400000000, due_date: '2026-07-18', status: 'Draft' }
      ],
      checklists: [
        { label: 'Pastikan nominal invoice sesuai kuitansi dan Purchase Order', checked: true },
        { label: 'Periksa lampiran BAST / Surat Jalan yang ditandatangani logistik', checked: true },
        { label: 'Periksa kelengkapan Faktur Pajak dari PKP vendor', checked: false }
      ]
    },
    'payment': {
      category: 'PURCHASING',
      title: 'Pembayaran Hutang',
      description: 'Proses pencairan kas/bank untuk pelunasan tagihan supplier/kontraktor (Accounts Payable Payment).',
      icon: '💰',
      color: '#10b981',
      listTitle: 'Daftar Realisasi Pembayaran Kas & Bank',
      recordLabel: 'Voucher Bayar',
      prevNode: 'Tanda Terima Tagihan',
      nextNode: 'Realisasi & Laporan Keuangan',
      actionBtnLabel: 'Cairkan',
      pipeline: ['Tanda Terima Tagihan', 'Pembayaran Hutang', 'Selesai'],
      currentStepIdx: 1,
      cols: [
        { field: 'voucher_no', label: 'No. Voucher' },
        { field: 'vendor', label: 'Dibayarkan Kepada' },
        { field: 'amount', label: 'Jumlah Dibayar' },
        { field: 'bank', label: 'Kas / Bank Sumber' },
        { field: 'status', label: 'Status Realisasi' }
      ],
      items: [
        { voucher_no: 'OP-2606-0001', vendor: 'CV. Semen Jaya', amount: 13000000, bank: 'Bank Mandiri (IDR)', status: 'Lunas (Transfer)' }
      ],
      checklists: [
        { label: 'Dapatkan persetujuan penandatanganan cek / otorisasi token bank', checked: true },
        { label: 'Keluarkan Bukti Kas Keluar resmi dari sistem keuangan', checked: true },
        { label: 'Kirim notifikasi bukti transfer ke supplier terkait', checked: false }
      ]
    }
  };

  return configs[sectionName] || {
    category: 'PROCESS',
    title: 'Business Step',
    description: 'Overview of this business step.',
    icon: '📊',
    color: '#0284c7',
    listTitle: 'Records',
    recordLabel: 'Record',
    prevNode: 'Start',
    nextNode: 'End',
    pipeline: ['Step 1', 'Step 2'],
    currentStepIdx: 0,
    cols: [{ field: 'name', label: 'Name' }],
    items: [],
    checklists: []
  };
};

const sectionData = computed(() => getSectionConfig(props.section));

// Calculate checklist completion percent
const checklistProgressPercent = computed(() => {
  if (checklistItems.value.length === 0) return 0;
  const checked = checklistItems.value.filter(c => c.checked).length;
  return (checked / checklistItems.value.length) * 100;
});

// Build initial values based on section
const initData = () => {
  const cfg = getSectionConfig(props.section);
  // Clone mock items to prevent state bleeding
  mockRecords.value = JSON.parse(JSON.stringify(cfg.items));
  checklistItems.value = JSON.parse(JSON.stringify(cfg.checklists));
};

// Re-initialize when section changes
watch(() => props.section, () => {
  initData();
}, { immediate: true });

// Actions
const addNewRecord = () => {
  const newRow = {};
  sectionData.value.cols.forEach(col => {
    if (col.field === 'status') {
      newRow[col.field] = 'Draft';
    } else if (col.field.includes('nilai') || col.field.includes('harga') || col.field.includes('budget')) {
      newRow[col.field] = 5000000;
    } else if (col.field.includes('progress')) {
      newRow[col.field] = 0;
    } else if (col.field === 'date' || col.field.includes('date')) {
      newRow[col.field] = new Date().toISOString().split('T')[0];
    } else {
      newRow[col.field] = 'MOCK BARU';
    }
  });
  mockRecords.value.push(newRow);
  alert(`Berhasil membuat draft ${sectionData.value.recordLabel} baru!`);
};

const handleAction = (row, idx) => {
  if (row.status) {
    if (row.status === 'Draft' || row.status === 'In Approval') {
      row.status = 'Approved';
      alert(`Dokumen disetujui! Status berubah menjadi Approved.`);
    } else {
      alert(`Dokumen ini sudah dalam status final: ${row.status}`);
    }
  } else {
    alert(`Aksi simulasi berhasil dieksekusi pada baris #${idx + 1}`);
  }
};

const formatCurrency = (num) => {
  if (!num || Number(num) === 0) return '-';
  const val = Number(num);
  return 'Rp ' + val.toLocaleString('id-ID');
};

const getStatusClass = (s) => {
  if (!s) return 'draft';
  const v = s.toLowerCase();
  if (v === 'aktif' || v === 'approved' || v.includes('lunas') || v.includes('terpilih') || v.includes('selesai') || v.includes('disetujui')) return 'approved';
  if (v === 'non-aktif' || v.includes('ditolak')) return 'reject';
  if (v.includes('approval') || v.includes('menunggu') || v.includes('dikirim') || v.includes('konstruksi') || v.includes('pondasi')) return 'in-approval';
  return 'draft';
};
</script>

<style scoped>
.workflow-view {
  animation: fadeIn 0.3s ease-in-out;
}

.workflow-header {
  margin-bottom: 20px;
}

.workflow-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: 12px;
  margin-bottom: 12px;
}

.workflow-icon {
  font-size: 1rem;
}

.workflow-title {
  border: none;
  margin-bottom: 6px;
  padding: 0;
  font-size: 1.4rem;
}

.workflow-desc {
  font-size: 0.85rem;
  color: var(--text-muted);
  line-height: 1.5;
}

/* Pipeline Context Map */
.flow-context-bar {
  display: flex;
  background-color: #f8fafc;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 12px 18px;
  margin-bottom: 24px;
  overflow-x: auto;
  gap: 12px;
}

.flow-step {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.78rem;
  font-weight: 500;
  color: var(--text-muted);
  white-space: nowrap;
}

.flow-step.active {
  color: var(--primary-color);
  font-weight: 700;
}

.flow-step.active .step-num {
  background-color: var(--primary-color);
  color: #fff;
  border-color: var(--primary-color);
}

.step-num {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 1.5px solid var(--text-muted);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 600;
}

.step-chevron {
  color: #94a3b8;
  display: flex;
  align-items: center;
}

/* Layout Columns */
.workflow-grid-layout {
  display: grid;
  grid-template-columns: 1fr;
  gap: 24px;
}

@media (min-width: 1024px) {
  .workflow-grid-layout {
    grid-template-columns: 1fr 320px;
  }
}

.workflow-main-panel {
  background: #fff;
  border-radius: 8px;
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.panel-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-color);
}

.btn-primary-custom {
  background-color: var(--primary-color);
  color: #fff;
  border: 1px solid var(--primary-color);
  border-radius: 6px;
  padding: 7px 14px;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: background-color 0.2s;
}

.btn-primary-custom:hover {
  background-color: var(--primary-hover);
  border-color: var(--primary-hover);
}

/* Progress Bar inside Table cell */
.progress-bar-container {
  display: flex;
  align-items: center;
  gap: 8px;
  width: 100%;
}

.progress-bar-fill {
  background-color: var(--primary-color);
  height: 6px;
  border-radius: 3px;
  transition: width 0.3s;
}

.progress-bar-text {
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--text-color);
}

/* Sidebar side cards */
.workflow-side-panel {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.checklist-card, .info-card-custom {
  background: #f8fafc;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 18px;
}

.side-title {
  font-size: 0.85rem;
  font-weight: 700;
  color: var(--text-color);
  margin-bottom: 8px;
}

.side-sub {
  font-size: 0.74rem;
  color: var(--text-muted);
  margin-bottom: 14px;
}

/* Checklist Styling */
.checklist-items {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-bottom: 18px;
}

.check-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.78rem;
  color: #475569;
  cursor: pointer;
  user-select: none;
}

.check-item input {
  display: none;
}

.check-box-custom {
  width: 16px;
  height: 16px;
  border: 1.5px solid var(--border-color);
  border-radius: 4px;
  background: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
  position: relative;
  transition: all 0.2s;
}

.check-item input:checked + .check-box-custom {
  background-color: var(--success-color);
  border-color: var(--success-color);
}

.check-item input:checked + .check-box-custom::after {
  content: '✓';
  color: #fff;
  font-size: 0.65rem;
  font-weight: 900;
}

.check-item.checked .check-label-text {
  text-decoration: line-through;
  color: var(--text-muted);
}

.checklist-progress {
  display: flex;
  align-items: center;
  gap: 10px;
}

.checklist-bar {
  flex: 1;
  background-color: var(--border-color);
  height: 6px;
  border-radius: 3px;
  overflow: hidden;
}

.checklist-bar-fill {
  background-color: var(--success-color);
  height: 100%;
  transition: width 0.3s;
}

.progress-pct {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--success-hover);
}

/* Flowchart relations flow-connect-box */
.flow-connect-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  margin-top: 12px;
}

.flow-node {
  width: 100%;
  background: #fff;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 8px 12px;
  text-align: center;
}

.flow-node.prev, .flow-node.next {
  opacity: 0.7;
}

.flow-node.active-node {
  border-width: 1.5px;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}

.node-label {
  font-size: 0.65rem;
  color: var(--text-muted);
  text-transform: uppercase;
  margin-bottom: 2px;
}

.node-value {
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--text-color);
}

.flow-arrow {
  font-size: 0.85rem;
  color: var(--text-muted);
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
