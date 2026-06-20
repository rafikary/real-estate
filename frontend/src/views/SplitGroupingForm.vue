<template>
  <div class="spg-form-view">
    <div class="card">
      <h2 class="card-title">{{ mode === 'edit' ? 'Edit Transaksi Land' : 'Tambah Split / Grouping Lahan' }}</h2>
      <form @submit.prevent="saveRecord">

        <!-- === SECTION 1: Info Transaksi === -->
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Kode Transaksi</label>
            <input type="text" class="form-control" :value="formData.kode_transaksi || 'Auto Generate'" disabled/>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Tipe <span class="required">*</span></label>
            <div class="tipe-radio-group">
              <label class="tipe-radio" :class="{ active: formData.tipe === 'Split' }">
                <input type="radio" v-model="formData.tipe" value="Split" required/> ✂️ Split Lahan
              </label>
              <label class="tipe-radio" :class="{ active: formData.tipe === 'Grouping' }">
                <input type="radio" v-model="formData.tipe" value="Grouping"/> 🔗 Grouping Lahan
              </label>
            </div>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Tanggal Transaksi <span class="required">*</span></label>
            <input type="date" class="form-control" v-model="formData.tgl_transaksi" required/>
            <span v-if="errors.tgl_transaksi" class="error-text">{{ errors.tgl_transaksi[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Status Dokumen</label>
            <select class="form-control" v-model="formData.status_doc">
              <option value="Draft">Draft</option>
              <option value="In Approval">In Approval</option>
              <option value="Approved">Approved</option>
              <option value="Reject">Reject</option>
              <option value="Revisi">Revisi</option>
            </select>
          </div>
        </div>

        <!-- === SECTION 2: Land Bank === -->
        <h3 class="section-heading">🏞️ Land Bank</h3>
        <div class="form-grid">
          <div class="form-group col-12">
            <label class="form-label">Land Bank Sumber <span class="required">*</span></label>
            <select class="form-control" v-model="formData.land_bank_id_sumber" @change="onSourceChange" required>
              <option value="">-- Pilih Land Bank Sumber --</option>
              <option v-for="lb in landBankOptions" :key="lb.id" :value="lb.id">
                {{ lb.code }} — {{ lb.name }} ({{ formatNumber(lb.area_cert) }} m² · {{ lb.cert_type }})
              </option>
            </select>
            <span v-if="errors.land_bank_id_sumber" class="error-text">{{ errors.land_bank_id_sumber[0] }}</span>
          </div>

          <!-- Info source land bank -->
          <div v-if="selectedSource" class="form-group col-12">
            <div class="info-box">
              <div class="info-box-title">📋 Info Land Bank Sumber</div>
              <div class="info-grid">
                <div><span class="info-label">Kode</span><span class="info-value">{{ selectedSource.code }}</span></div>
                <div><span class="info-label">Nama Lahan</span><span class="info-value">{{ selectedSource.name }}</span></div>
                <div><span class="info-label">Luas Sertifikat</span><span class="info-value">{{ formatNumber(selectedSource.area_cert) }} m²</span></div>
                <div><span class="info-label">Jenis Sertifikat</span><span class="info-value">{{ selectedSource.cert_type }}</span></div>
              </div>
            </div>
          </div>

          <!-- Tipe info -->
          <div class="form-group col-12" v-if="formData.tipe">
            <div class="tipe-info-box" :class="formData.tipe === 'Split' ? 'split-info' : 'group-info'">
              <template v-if="formData.tipe === 'Split'">
                ✂️ <strong>Split Lahan</strong>: Satu land bank akan dipecah menjadi beberapa bidang baru. Land bank baru akan dibuat secara manual setelah transaksi ini disetujui.
              </template>
              <template v-else>
                🔗 <strong>Grouping Lahan</strong>: Beberapa land bank akan digabung menjadi satu bidang baru. Land bank baru akan dibuat secara manual setelah transaksi ini disetujui.
              </template>
            </div>
          </div>
        </div>

        <!-- === SECTION 3: Notaris & Lampiran === -->
        <h3 class="section-heading">🏛️ Notaris, Lampiran & Keterangan</h3>
        <div class="form-grid">
          <div class="form-group col-4">
            <label class="form-label">Nama Notaris</label>
            <input type="text" class="form-control" v-model="formData.nama_notaris" placeholder="E.g. Notaris Budi, SH"/>
          </div>
          <div class="form-group col-4">
            <label class="form-label">No. Akta</label>
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
          <div class="form-group col-12">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" v-model="formData.keterangan" rows="3" placeholder="Jelaskan detail proses split/grouping..."></textarea>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" @click="$router.push('/master/split-grouping')">Batal</button>
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
const selectedSource = ref(null);

const formData = ref({
  kode_transaksi:'', tipe:'Split', tgl_transaksi:'', land_bank_id_sumber:'',
  keterangan:'', nama_notaris:'', no_akta:'', lampiran_path:null, status_doc:'Draft', status:'Aktif',
});

const formatNumber = (n) => n ? Number(n).toLocaleString('id-ID') : '0';
const onFileChange = (e) => { if(e.target.files[0]) selectedFile.value=e.target.files[0]; };
const onSourceChange = () => { selectedSource.value = landBankOptions.value.find(l=>l.id==formData.value.land_bank_id_sumber)||null; };

const saveRecord = async () => {
  submitting.value = true; errors.value = {};
  try {
    const payload = new FormData();
    Object.keys(formData.value).forEach(k => {
      if(k!=='lampiran_path' && formData.value[k]!==null && formData.value[k]!==undefined)
        payload.append(k, formData.value[k]);
    });
    if(selectedFile.value) payload.append('lampiran', selectedFile.value);
    if(props.mode==='edit') { payload.append('_method','PUT'); await api.post(`/split-grouping-lands/${props.id}`, payload, {headers:{'Content-Type':'multipart/form-data'}}); }
    else { await api.post('/split-grouping-lands', payload, {headers:{'Content-Type':'multipart/form-data'}}); }
    alert('Data berhasil disimpan!'); router.push('/master/split-grouping');
  } catch(e) {
    if(e.response?.status===422) { errors.value=e.response.data.errors||{}; alert('Validasi gagal.'); }
    else alert('Error: '+(e.response?.data?.message||e.message));
  } finally { submitting.value=false; }
};

const loadRecord = async () => {
  try {
    const res = await api.get(`/split-grouping-lands/${props.id}`);
    Object.keys(formData.value).forEach(k => { if(res.data[k]!==undefined) formData.value[k]=res.data[k]??''; });
    if(formData.value.land_bank_id_sumber) onSourceChange();
  } catch(e) { alert('Gagal memuat data'); router.push('/master/split-grouping'); }
};

onMounted(async () => {
  try { const res = await api.get('/split-grouping-lands/land-bank-options'); landBankOptions.value=res.data||[]; } catch(e) {}
  if(props.mode==='edit'&&props.id) await loadRecord();
});
</script>

<style scoped>
.spg-form-view { animation:fadeIn 0.3s ease-in-out; }
.section-heading { font-size:0.9rem; font-weight:600; color:var(--text-color); margin:8px 0 16px; padding-bottom:10px; border-bottom:1px dashed var(--border-color); }
.error-text { font-size:0.75rem; color:var(--danger-color); }
.tipe-radio-group { display:flex; flex-direction:column; gap:8px; }
.tipe-radio { display:flex; align-items:center; gap:8px; padding:10px 14px; border:1.5px solid var(--border-color); border-radius:8px; cursor:pointer; font-size:0.85rem; font-weight:500; transition:all 0.2s; }
.tipe-radio input { display:none; }
.tipe-radio.active { border-color:var(--primary-color); background:#eff6ff; color:var(--primary-color); }
.tipe-info-box { padding:12px 16px; border-radius:8px; font-size:0.82rem; }
.split-info { background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; }
.group-info { background:#f0fdf4; border:1px solid #86efac; color:#166534; }
.info-box { background:#f8fafc; border:1px solid var(--border-color); border-radius:8px; padding:14px 18px; }
.info-box-title { font-size:0.82rem; font-weight:600; color:var(--text-color); margin-bottom:10px; }
.info-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; }
.info-label { display:block; font-size:0.7rem; color:var(--text-muted); margin-bottom:2px; }
.info-value { font-size:0.82rem; font-weight:500; color:var(--text-color); }
.btn-upload { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:0.8rem; padding:6px 14px; font-weight:600; border-radius:4px; cursor:pointer; white-space:nowrap; }
.btn-upload:hover { background:#dcfce7; }
.btn-preview { font-size:0.8rem; color:var(--primary-color); text-decoration:none; padding:4px 10px; border:1px solid var(--primary-color); border-radius:4px; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
