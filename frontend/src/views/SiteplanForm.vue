<template>
  <div class="spl-form-view">
    <div class="card">
      <h2 class="card-title">{{ mode === 'edit' ? 'Edit Siteplan' : 'Tambah Siteplan' }}</h2>
      <form @submit.prevent="saveRecord">

        <!-- === SECTION 1: Info Dasar === -->
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Kode Siteplan</label>
            <input type="text" class="form-control" :value="formData.kode_siteplan || 'Auto Generate'" disabled/>
          </div>
          <div class="form-group col-9">
            <label class="form-label">Nama Siteplan <span class="required">*</span></label>
            <input type="text" class="form-control" v-model="formData.nama_siteplan" placeholder="E.g. Cluster Melati Type 36/60" required/>
            <span v-if="errors.nama_siteplan" class="error-text">{{ errors.nama_siteplan[0] }}</span>
          </div>

          <div class="form-group col-6">
            <label class="form-label">Proyek <span class="required">*</span></label>
            <select class="form-control" v-model="formData.master_project_id" required>
              <option value="">-- Pilih Proyek --</option>
              <option v-for="p in projectOptions" :key="p.id" :value="p.id">{{ p.kode_proyek }} — {{ p.nama_proyek }}</option>
            </select>
            <span v-if="errors.master_project_id" class="error-text">{{ errors.master_project_id[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Tipe Unit <span class="required">*</span></label>
            <select class="form-control" v-model="formData.tipe_unit" required>
              <option value="">Pilih Tipe</option>
              <option value="Rumah">Rumah</option>
              <option value="Ruko">Ruko</option>
              <option value="Kavling">Kavling</option>
              <option value="Apartemen">Apartemen</option>
              <option value="Gudang">Gudang</option>
              <option value="Kios">Kios</option>
            </select>
            <span v-if="errors.tipe_unit" class="error-text">{{ errors.tipe_unit[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Cluster / Blok</label>
            <input type="text" class="form-control" v-model="formData.cluster" placeholder="E.g. Cluster A"/>
          </div>

          <div class="form-group col-12">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" v-model="formData.deskripsi" rows="2" placeholder="Deskripsi singkat siteplan..."></textarea>
          </div>
        </div>

        <!-- === SECTION 2: Dimensi & Spesifikasi === -->
        <h3 class="section-heading">📐 Dimensi & Spesifikasi Unit</h3>
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Luas Tanah (m²) <span class="required">*</span></label>
            <input type="number" step="0.01" class="form-control" v-model.number="formData.luas_tanah" required/>
            <span v-if="errors.luas_tanah" class="error-text">{{ errors.luas_tanah[0] }}</span>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Luas Bangunan (m²)</label>
            <input type="number" step="0.01" class="form-control" v-model.number="formData.luas_bangunan"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Jumlah Lantai</label>
            <input type="number" class="form-control" v-model.number="formData.jumlah_lantai" min="1"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Orientasi</label>
            <select class="form-control" v-model="formData.orientasi">
              <option value="">-- Pilih --</option>
              <option value="Utara">Utara</option>
              <option value="Selatan">Selatan</option>
              <option value="Timur">Timur</option>
              <option value="Barat">Barat</option>
            </select>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Kamar Tidur</label>
            <input type="number" class="form-control" v-model.number="formData.kamar_tidur" min="0"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Kamar Mandi</label>
            <input type="number" class="form-control" v-model.number="formData.kamar_mandi" min="0"/>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Jumlah Unit <span class="required">*</span></label>
            <input type="number" class="form-control" v-model.number="formData.jumlah_unit" min="0" required/>
            <span v-if="errors.jumlah_unit" class="error-text">{{ errors.jumlah_unit[0] }}</span>
          </div>
          <div class="form-group col-3">
            <label class="form-label">Harga Dasar (Rp)</label>
            <div style="position:relative">
              <span class="input-prefix">Rp</span>
              <input type="number" class="form-control" style="padding-left:38px" v-model.number="formData.harga_dasar"/>
            </div>
            <span v-if="formData.harga_dasar > 0" class="input-hint">{{ formatCurrencyFull(formData.harga_dasar) }}</span>
          </div>
        </div>

        <!-- === SECTION 3: Lampiran & Status === -->
        <h3 class="section-heading">📎 Lampiran & Status</h3>
        <div class="form-grid">
          <div class="form-group col-6">
            <label class="form-label">Lampiran Denah (PDF/Gambar)</label>
            <div class="file-upload-wrapper">
              <input type="file" ref="fileInput" style="display:none" @change="onFileChange" accept=".pdf,image/*"/>
              <button type="button" class="btn-upload" @click="$refs.fileInput.click()">{{ selectedFile ? 'Ganti File' : 'Unggah File' }}</button>
              <span style="font-size:0.8rem;color:var(--text-muted)">{{ selectedFile ? selectedFile.name : (formData.lampiran_path ? 'File ada' : 'Belum ada') }}</span>
              <a v-if="formData.lampiran_path" :href="'http://localhost:8000'+formData.lampiran_path" target="_blank" class="btn-preview">Preview</a>
            </div>
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
          <div class="form-group col-3">
            <label class="form-label">Status</label>
            <select class="form-control" v-model="formData.status">
              <option value="Aktif">Aktif</option>
              <option value="Non-Aktif">Non-Aktif</option>
            </select>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-cancel" @click="$router.push('/master/siteplan')">Batal</button>
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
const projectOptions = ref([]);

const formData = ref({
  kode_siteplan:'', master_project_id:'', nama_siteplan:'', deskripsi:'', tipe_unit:'', cluster:'',
  luas_tanah:0, luas_bangunan:0, jumlah_lantai:1, jumlah_unit:0, harga_dasar:0,
  kamar_tidur:null, kamar_mandi:null, orientasi:'', lampiran_path:null, status_doc:'Draft', status:'Aktif',
});

const formatCurrencyFull = (n) => n ? '≈ '+new Intl.NumberFormat('id-ID',{style:'currency',currency:'IDR',maximumFractionDigits:0}).format(n) : '';
const onFileChange = (e) => { if(e.target.files[0]) selectedFile.value = e.target.files[0]; };

const saveRecord = async () => {
  submitting.value = true; errors.value = {};
  try {
    const payload = new FormData();
    Object.keys(formData.value).forEach(k => {
      if(k !== 'lampiran_path' && formData.value[k] !== null && formData.value[k] !== undefined)
        payload.append(k, formData.value[k]);
    });
    if(selectedFile.value) payload.append('lampiran', selectedFile.value);
    if(props.mode === 'edit') { payload.append('_method','PUT'); await api.post(`/siteplans/${props.id}`, payload, {headers:{'Content-Type':'multipart/form-data'}}); }
    else { await api.post('/siteplans', payload, {headers:{'Content-Type':'multipart/form-data'}}); }
    alert(props.mode==='edit' ? 'Siteplan berhasil diupdate!' : 'Siteplan berhasil dibuat!');
    router.push('/master/siteplan');
  } catch(e) {
    if(e.response?.status===422) { errors.value=e.response.data.errors||{}; alert('Validasi gagal.'); }
    else alert('Error: '+(e.response?.data?.message||e.message));
  } finally { submitting.value = false; }
};

const loadRecord = async () => {
  try {
    const res = await api.get(`/siteplans/${props.id}`);
    Object.keys(formData.value).forEach(k => { if(res.data[k] !== undefined) formData.value[k] = res.data[k] ?? ''; });
  } catch(e) { alert('Gagal memuat data'); router.push('/master/siteplan'); }
};

onMounted(async () => {
  try { const res = await api.get('/siteplans/project-options'); projectOptions.value = res.data||[]; } catch(e) {}
  if(props.mode==='edit' && props.id) await loadRecord();
});
</script>

<style scoped>
.spl-form-view { animation:fadeIn 0.3s ease-in-out; }
.section-heading { font-size:0.9rem; font-weight:600; color:var(--text-color); margin:8px 0 16px; padding-bottom:10px; border-bottom:1px dashed var(--border-color); }
.error-text { font-size:0.75rem; color:var(--danger-color); }
.input-prefix { position:absolute; left:10px; top:50%; transform:translateY(-50%); font-size:0.85rem; color:var(--text-muted); font-weight:600; }
.input-hint { font-size:0.72rem; color:var(--text-muted); margin-top:2px; }
.btn-upload { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; font-size:0.8rem; padding:6px 14px; font-weight:600; border-radius:4px; cursor:pointer; white-space:nowrap; }
.btn-upload:hover { background:#dcfce7; }
.btn-preview { font-size:0.8rem; color:var(--primary-color); text-decoration:none; padding:4px 10px; border:1px solid var(--primary-color); border-radius:4px; }
@keyframes fadeIn { from{opacity:0;transform:translateY(10px)} to{opacity:1;transform:translateY(0)} }
</style>
