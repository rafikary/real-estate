<template>
  <div class="ups-form-view">
    <div class="card">
      <h2 class="card-title">{{ mode === 'edit' ? 'Edit Update Sertifikat' : 'Tambah Update Sertifikat' }}</h2>
      <form @submit.prevent="saveRecord">

        <!-- === SECTION 1: Info Perubahan === -->
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Kode Update</label>
            <input type="text" class="form-control" :value="formData.kode_update || 'Auto Generate'" disabled/>
          </div>
          <div class="form-group col-9">
            <label class="form-label">Land Bank <span class="required">*</span></label>
            <select class="form-control" v-model="formData.land_bank_id" @change="onLandBankChange" required>
              <option value="">-- Pilih Land Bank --</option>
              <option v-for="lb in landBankOptions" :key="lb.id" :value="lb.id">
                {{ lb.code }} — {{ lb.name }}
              </option>
            </select>
            <span v-if="errors.land_bank_id" class="error-text">{{ errors.land_bank_id[0] }}</span>
          </div>

          <!-- Info sertifikat saat ini (read only) -->
          <div v-if="selectedLandBank" class="form-group col-12">
            <div class="info-box">
              <div class="info-box-title">📋 Info Sertifikat Saat Ini</div>
              <div class="info-grid">
                <div><span class="info-label">Jenis Sertifikat</span><span class="info-value">{{ selectedLandBank.cert_type || '-' }}</span></div>
                <div><span class="info-label">No. Sertifikat</span><span class="info-value">{{ selectedLandBank.cert_no || '-' }}</span></div>
                <div><span class="info-label">Pemilik Terdaftar</span><span class="info-value">{{ selectedLandBank.owner || '-' }}</span></div>
                <div><span class="info-label">Luas Sertifikat</span><span class="info-value">{{ formatNumber(selectedLandBank.area_cert) }} m²</span></div>
              </div>
            </div>
          </div>

          <div class="form-group col-4">
            <label class="form-label">Jenis Perubahan <span class="required">*</span></label>
            <select class="form-control" v-model="formData.jenis_perubahan" required>
              <option value="">Pilih Jenis</option>
              <option value="Balik Nama">Balik Nama</option>
              <option value="Perubahan Jenis Hak">Perubahan Jenis Hak</option>
              <option value="Perpanjang Masa Sertifikat">Perpanjang Masa Sertifikat</option>
              <option value="Penggabungan Tanah">Penggabungan Tanah</option>
              <option value="Pemisahan Tanah">Pemisahan Tanah</option>
            </select>
            <span v-if="errors.jenis_perubahan" class="error-text">{{ errors.jenis_perubahan[0] }}</span>
          </div>
          <div class="form-group col-4">
            <label class="form-label">No. Perubahan / Akta</label>
            <input type="text" class="form-control" v-model="formData.no_perubahan" placeholder="E.g. US-260101-0001"/>
          </div>
          <div class="form-group col-4">
            <label class="form-label">Tanggal Perubahan</label>
            <input type="date" class="form-control" v-model="formData.tgl_perubahan"/>
          </div>
          <div class="form-group col-12">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" v-model="formData.keterangan" rows="2" placeholder="Keterangan tambahan..."></textarea>
          </div>
        </div>

        <!-- === SECTION 2: Data Sertifikat Baru === -->
        <h3 class="section-heading">📄 Data Sertifikat Baru (Hasil Perubahan)</h3>
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Jenis Sertifikat Baru</label>
            <select class="form-control" v-model="formData.jenis_sertifikat_baru">
              <option value="">-- Tidak Berubah --</option>
              <option value="SHM">SHM</option>
              <option value="HGB">HGB</option>
              <option value="Petok D">Petok D</option>
              <option value="HP">HP (Hak Pakai)</option>
              <option value="HGU">HGU (Hak Guna Usaha)</option>
            </select>
          </div>
          <div class="form-group col-3">
            <label class="form-label">No. Sertifikat Baru</label>
            <input type="text" class="form-control" v-model="formData.no_sertifikat_baru" placeholder="Nomor sertifikat baru"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">NIB Baru</label>
            <input type="text" class="form-control" v-model="formData.nib_baru" placeholder="NIB baru"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Luas Sertifikat Baru (m²)</label>
            <input type="number" step="0.01" class="form-control" v-model.number="formData.luas_sertifikat_baru"/>
          </div>
          <div class="form-group col-6">
            <label class="form-label">Pemilik Baru</label>
            <input type="text" class="form-control" v-model="formData.pemilik_baru" placeholder="Nama pemilik terdaftar baru"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Tanggal Terbit Baru</label>
            <input type="date" class="form-control" v-model="formData.tanggal_terbit_baru"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Masa Berlaku Baru</label>
            <input type="date" class="form-control" v-model="formData.masa_berlaku_baru"/>
          </div>
        </div>

        <!-- === SECTION 3: Notaris & Lampiran === -->
        <h3 class="section-heading">🏛️ Notaris & Lampiran</h3>
        <div class="form-grid">
          <div class="form-group col-4">
            <label class="form-label">Nama Notaris</label>
            <input type="text" class="form-control" v-model="formData.nama_notaris" placeholder="E.g. Notaris Slamet, SH"/>
          </div>
          <div class="form-group col-4">
            <label class="form-label">No. Akta Notaris</label>
            <input type="text" class="form-control" v-model="formData.no_akta" placeholder="E.g. AKT-2026-001"/>
          </div>
          <div class="form-group col-4">
            <label class="form-label">Lampiran</label>
            <div class="file-upload-wrapper">
              <input type="file" ref="fileInput" style="display:none" @change="onFileChange" accept=".pdf,image/*"/>
              <button type="button" class="btn-upload" @click="$refs.fileInput.click()">{{ selectedFile ? 'Ganti File' : 'Unggah File' }}</button>
              <span style="font-size:0.8rem;color:var(--text-muted)">{{ selectedFile ? selectedFile.name : (formData.lampiran_path ? 'File ada' : 'Belum ada') }}</span>
              <a v-if="formData.lampiran_path" :href="'http://localhost:8000'+formData.lampiran_path" target="_blank" class="btn-preview">Preview</a>
            </div>
          </div>
          <div class="form-group col-6">
            <label class="form-label">Status Dokumen</label>
            <select class="form-control" v-model="formData.status_doc">
              <option value="Draft">Draft</option>
              <option value="In Approval">In Approval</option>
              <option value="Approved">Approved</option>
              <option value="Reject">Reject</option>
              <option value="Revisi">Revisi</option>
            </select>
          </div>
          <div class="form-group col-6">
            <label class="form-label">Status</label>
            <select class="form-control" v-model="formData.status">
              <option value="Aktif">Aktif</option>
              <option value="Non-Aktif">Non-Aktif</option>
            </select>
          </div>
        </div>

        <div v-if="formData.status_doc === 'Approved'" class="approved-notice">
          ✅ Saat disimpan dengan status <strong>Approved</strong>, data sertifikat pada Land Bank akan diperbarui otomatis.
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" @click="$router.push('/master/update-sertifikat')">Batal</button>
          <button type="submit" class="btn-submit" :disabled="submitting">{{ submitting ? 'Menyimpan...' : 'Simpan' }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../utils/api';

const props = defineProps({ id: { type: String, default: null }, mode: { type: String, default: 'create' } });
const router = useRouter();
const submitting = ref(false);
const selectedFile = ref(null);
const errors = ref({});
const landBankOptions = ref([]);
const selectedLandBank = ref(null);

const formData = ref({
  kode_update: '', land_bank_id: '', jenis_perubahan: '', no_perubahan: '', tgl_perubahan: '', keterangan: '',
  jenis_sertifikat_baru: '', no_sertifikat_baru: '', nib_baru: '', pemilik_baru: '', luas_sertifikat_baru: null,
  tanggal_terbit_baru: '', masa_berlaku_baru: '', nama_notaris: '', no_akta: '', lampiran_path: null,
  status_doc: 'Draft', status: 'Aktif',
});

const onLandBankChange = () => {
  const lb = landBankOptions.value.find(l => l.id == formData.value.land_bank_id);
  selectedLandBank.value = lb || null;
};

const formatNumber = (n) => n ? Number(n).toLocaleString('id-ID') : '0';
const onFileChange = (e) => { if (e.target.files[0]) selectedFile.value = e.target.files[0]; };

const saveRecord = async () => {
  submitting.value = true; errors.value = {};
  try {
    const payload = new FormData();
    Object.keys(formData.value).forEach(k => {
      if (k !== 'lampiran_path' && formData.value[k] !== null && formData.value[k] !== undefined && formData.value[k] !== '')
        payload.append(k, formData.value[k]);
    });
    if (selectedFile.value) payload.append('lampiran', selectedFile.value);
    if (props.mode === 'edit') { payload.append('_method', 'PUT'); await api.post(`/update-sertifikats/${props.id}`, payload, { headers: { 'Content-Type': 'multipart/form-data' } }); }
    else { await api.post('/update-sertifikats', payload, { headers: { 'Content-Type': 'multipart/form-data' } }); }
    alert(props.mode === 'edit' ? 'Data berhasil diupdate!' : 'Data berhasil dibuat!');
    router.push('/master/update-sertifikat');
  } catch (e) {
    if (e.response?.status === 422) { errors.value = e.response.data.errors || {}; alert('Validasi gagal.'); }
    else alert('Error: ' + (e.response?.data?.message || e.message));
  } finally { submitting.value = false; }
};

const loadRecord = async () => {
  try {
    const res = await api.get(`/update-sertifikats/${props.id}`);
    const d = res.data;
    Object.keys(formData.value).forEach(k => { if (d[k] !== undefined) formData.value[k] = d[k] ?? ''; });
    if (d.land_bank_id) onLandBankChange();
  } catch (e) { alert('Gagal memuat data'); router.push('/master/update-sertifikat'); }
};

const loadLandBankOptions = async () => {
  try { const res = await api.get('/update-sertifikats/land-bank-options'); landBankOptions.value = res.data || []; }
  catch (e) { console.warn(e); }
};

onMounted(async () => {
  await loadLandBankOptions();
  if (props.mode === 'edit' && props.id) await loadRecord();
});
</script>

<style scoped>
.ups-form-view { animation: fadeIn 0.3s ease-in-out; }
.section-heading { font-size:0.9rem; font-weight:600; color:var(--text-color); margin:8px 0 16px; padding-bottom:10px; border-bottom:1px dashed var(--border-color); }
.error-text { font-size:0.75rem; color:var(--danger-color); margin-top:2px; }
.info-box { background:#f8fafc; border:1px solid var(--border-color); border-radius:8px; padding:14px 18px; }
.info-box-title { font-size:0.82rem; font-weight:600; color:var(--text-color); margin-bottom:10px; }
.info-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.info-label { display:block; font-size:0.7rem; color:var(--text-muted); margin-bottom:2px; }
.info-value { font-size:0.82rem; font-weight:500; color:var(--text-color); }
.approved-notice { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:6px; padding:10px 14px; font-size:0.82rem; color:#166534; margin-top:8px; margin-bottom:8px; }
.btn-upload { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:0.8rem; padding:6px 14px; font-weight:600; border-radius:4px; cursor:pointer; white-space:nowrap; }
.btn-upload:hover { background:#dcfce7; }
.btn-preview { font-size:0.8rem; color:var(--primary-color); text-decoration:none; padding:4px 10px; border:1px solid var(--primary-color); border-radius:4px; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
