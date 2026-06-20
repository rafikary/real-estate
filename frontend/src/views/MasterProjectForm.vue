<template>
  <div class="project-form-view">
    <div class="card">
      <h2 class="card-title">{{ mode === 'edit' ? 'Edit Proyek' : 'Tambah Proyek' }}</h2>

      <form @submit.prevent="saveRecord">
        <!-- === SECTION 1: Info Utama Proyek === -->
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Kode Proyek</label>
            <input
              type="text"
              class="form-control"
              :value="formData.kode_proyek || 'Auto Generate'"
              disabled
            />
          </div>

          <div class="form-group col-9">
            <label class="form-label">Nama Proyek <span class="required">*</span></label>
            <input
              type="text"
              class="form-control"
              v-model="formData.nama_proyek"
              placeholder="Masukkan nama proyek"
              required
            />
            <span v-if="errors.nama_proyek" class="error-text">{{ errors.nama_proyek[0] }}</span>
          </div>

          <div class="form-group col-4">
            <label class="form-label">Tipe Proyek <span class="required">*</span></label>
            <select class="form-control" v-model="formData.tipe_proyek" required>
              <option value="">Pilih Tipe Proyek</option>
              <option value="Perumahan">Perumahan</option>
              <option value="Komersial">Komersial</option>
              <option value="Mixed-Use">Mixed-Use</option>
              <option value="Industri">Industri</option>
              <option value="Apartemen">Apartemen</option>
            </select>
            <span v-if="errors.tipe_proyek" class="error-text">{{ errors.tipe_proyek[0] }}</span>
          </div>

          <div class="form-group col-4">
            <label class="form-label">Land Bank (Opsional)</label>
            <select class="form-control" v-model="formData.land_bank_id">
              <option value="">-- Tidak Terikat Land Bank --</option>
              <option v-for="lb in landBankOptions" :key="lb.id" :value="lb.id">
                {{ lb.code }} — {{ lb.name }}
              </option>
            </select>
          </div>

          <div class="form-group col-4">
            <label class="form-label">Luas Total (m²) <span class="required">*</span></label>
            <input
              type="number"
              step="0.01"
              class="form-control"
              v-model.number="formData.luas_total"
              placeholder="0.00"
              required
            />
            <span v-if="errors.luas_total" class="error-text">{{ errors.luas_total[0] }}</span>
          </div>

          <div class="form-group col-12">
            <label class="form-label">Deskripsi</label>
            <textarea
              class="form-control"
              v-model="formData.deskripsi"
              placeholder="Tulis deskripsi proyek secara singkat..."
              rows="2"
            ></textarea>
          </div>
        </div>

        <!-- === SECTION 2: Lokasi === -->
        <h3 class="section-heading">📍 Lokasi Proyek</h3>
        <div class="form-grid">
          <!-- Cascading Dropdowns -->
          <div class="form-group col-3">
            <label class="form-label">Provinsi <span class="required">*</span></label>
            <select class="form-control" v-model="formData.provinsi" @change="onProvinsiChange" required>
              <option value="">Pilih Provinsi</option>
              <option v-for="prov in provinces" :key="prov" :value="prov">{{ prov }}</option>
            </select>
            <span v-if="errors.provinsi" class="error-text">{{ errors.provinsi[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kota/Kabupaten <span class="required">*</span></label>
            <select class="form-control" v-model="formData.kota_kabupaten" @change="onKotaChange" :disabled="!formData.provinsi" required>
              <option value="">Pilih Kota</option>
              <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
            </select>
            <span v-if="errors.kota_kabupaten" class="error-text">{{ errors.kota_kabupaten[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kecamatan <span class="required">*</span></label>
            <select class="form-control" v-model="formData.kecamatan" @change="onKecamatanChange" :disabled="!formData.kota_kabupaten" required>
              <option value="">Pilih Kecamatan</option>
              <option v-for="kec in districts" :key="kec" :value="kec">{{ kec }}</option>
            </select>
            <span v-if="errors.kecamatan" class="error-text">{{ errors.kecamatan[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kelurahan/Desa <span class="required">*</span></label>
            <select class="form-control" v-model="formData.kelurahan" :disabled="!formData.kecamatan" required>
              <option value="">Pilih Kelurahan</option>
              <option v-for="kel in subdistricts" :key="kel" :value="kel">{{ kel }}</option>
            </select>
            <span v-if="errors.kelurahan" class="error-text">{{ errors.kelurahan[0] }}</span>
          </div>

          <div class="form-group col-12">
            <label class="form-label">Alamat Lengkap</label>
            <textarea
              class="form-control"
              v-model="formData.alamat_lengkap"
              placeholder="Tulis alamat lengkap proyek..."
              rows="2"
            ></textarea>
          </div>
        </div>

        <!-- === SECTION 3: Waktu & Investasi === -->
        <h3 class="section-heading">💰 Timeline & Investasi</h3>
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" v-model="formData.tanggal_mulai" />
          </div>

          <div class="form-group col-3">
            <label class="form-label">Estimasi Selesai</label>
            <input type="date" class="form-control" v-model="formData.tanggal_selesai_estimasi" />
          </div>

          <div class="form-group col-6">
            <label class="form-label">Nilai Investasi (Rp)</label>
            <div style="position: relative;">
              <span class="input-prefix">Rp</span>
              <input
                type="number"
                step="1"
                class="form-control"
                style="padding-left: 38px;"
                v-model.number="formData.nilai_investasi"
                placeholder="0"
              />
            </div>
            <span v-if="formData.nilai_investasi > 0" class="input-hint">
              {{ formatCurrencyFull(formData.nilai_investasi) }}
            </span>
          </div>
        </div>

        <!-- === SECTION 4: Developer / PIC === -->
        <h3 class="section-heading">👤 Developer & PIC</h3>
        <div class="form-grid">
          <div class="form-group col-4">
            <label class="form-label">Nama Developer</label>
            <input type="text" class="form-control" v-model="formData.nama_developer" placeholder="E.g. PT. Maju Sejahtera" />
          </div>

          <div class="form-group col-4">
            <label class="form-label">Nama PIC</label>
            <input type="text" class="form-control" v-model="formData.nama_pic" placeholder="Nama penanggung jawab" />
          </div>

          <div class="form-group col-4">
            <label class="form-label">No. Telepon PIC</label>
            <input type="text" class="form-control" v-model="formData.no_telepon_pic" placeholder="08xxxxxxxxx" />
          </div>
        </div>

        <!-- === SECTION 5: Lampiran & Status === -->
        <h3 class="section-heading">📎 Lampiran & Status</h3>
        <div class="form-grid">
          <div class="form-group col-6">
            <label class="form-label">Lampiran (PDF/Gambar)</label>
            <div class="file-upload-wrapper">
              <input type="file" ref="fileInput" style="display: none;" @change="onFileChange" accept=".pdf,image/*" />
              <button type="button" class="btn-upload" @click="$refs.fileInput.click()">
                {{ selectedFile ? 'Ganti File' : 'Unggah File' }}
              </button>
              <span class="file-name" style="font-size: 0.8rem; color: var(--text-muted);">
                {{ selectedFile ? selectedFile.name : (formData.lampiran_path ? 'Attachment exists' : 'Belum ada berkas dipilih') }}
              </span>
              <a
                v-if="formData.lampiran_path"
                :href="'http://localhost:8000' + formData.lampiran_path"
                target="_blank"
                class="btn-preview"
              >
                Preview
              </a>
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
            <label class="form-label">Status Publikasi</label>
            <select class="form-control" v-model="formData.status">
              <option value="Aktif">Aktif</option>
              <option value="Non-Aktif">Non-Aktif</option>
            </select>
          </div>
        </div>

        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 15px;" v-if="mode === 'edit'">
          Terakhir diubah oleh <strong>{{ formData.updated_by || 'admin' }}</strong> pada {{ formatTimestamp(formData.updated_at) }}
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="button" class="btn-cancel" @click="cancelEdit">Batal</button>
          <button type="submit" class="btn-submit" :disabled="submitting">
            {{ submitting ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../utils/api';

const props = defineProps({
  id: { type: String, default: null },
  mode: { type: String, default: 'create' }
});

const router = useRouter();
const submitting = ref(false);
const selectedFile = ref(null);
const fileInput = ref(null);
const errors = ref({});
const landBankOptions = ref([]);

const formData = ref({
  kode_proyek: '',
  nama_proyek: '',
  deskripsi: '',
  land_bank_id: '',
  tipe_proyek: '',
  provinsi: '',
  kota_kabupaten: '',
  kecamatan: '',
  kelurahan: '',
  alamat_lengkap: '',
  luas_total: 0,
  nilai_investasi: 0,
  tanggal_mulai: '',
  tanggal_selesai_estimasi: '',
  nama_developer: '',
  nama_pic: '',
  no_telepon_pic: '',
  lampiran_path: null,
  status_doc: 'Draft',
  status: 'Aktif',
});

// --- Location cascading dropdown ---
const locationLookup = {
  'Jawa Timur': {
    'Surabaya': {
      'Genteng': ['Ketabang', 'Genteng', 'Peneleh'],
      'Rungkut': ['Wonorejo', 'Rungkut Kidul', 'Kedung Baruk'],
      'Sukolilo': ['Keputih', 'Gebang Putih', 'Menur Pumpungan']
    },
    'Kota Malang': {
      'Blimbing': ['Arjosari', 'Blimbing', 'Purwodadi'],
      'Klojen': ['Klojen', 'Penanggungan', 'Gadingkasri']
    },
    'Sidoarjo': {
      'Waru': ['Waru', 'Wedoro', 'Kureksari'],
      'Gedangan': ['Gedangan', 'Sruni', 'Ketajen']
    }
  },
  'DKI Jakarta': {
    'Jakarta Pusat': {
      'Menteng': ['Menteng', 'Pegangsaan', 'Cikini'],
      'Gambir': ['Gambir', 'Petojo Utara', 'Cideng']
    },
    'Jakarta Selatan': {
      'Tebet': ['Tebet Barat', 'Tebet Timur', 'Menteng Dalam'],
      'Cilandak': ['Cilandak Barat', 'Cipete Selatan', 'Gandaria Selatan']
    }
  },
  'Jawa Barat': {
    'Bandung': {
      'Cicendo': ['Pasirkaliki', 'Ciumbuleuit', 'Arjuna'],
      'Coblong': ['Dago', 'Lebakgede', 'Cisitu']
    },
    'Bekasi': {
      'Bekasi Utara': ['Marga Mulya', 'Harapan Mulya', 'Kaliabang Tengah'],
      'Bekasi Selatan': ['Pekayon Jaya', 'Kayuringin Jaya', 'Marga Jaya']
    }
  },
  'Banten': {
    'Tangerang Selatan': {
      'Serpong': ['Lengkong Gudang', 'Rawa Buntu', 'Serpong'],
      'Ciputat': ['Ciputat', 'Cipayung', 'Sarua']
    }
  }
};

const provinces = Object.keys(locationLookup);
const cities = ref([]);
const districts = ref([]);
const subdistricts = ref([]);

const onProvinsiChange = () => {
  cities.value = [];
  districts.value = [];
  subdistricts.value = [];
  formData.value.kota_kabupaten = '';
  formData.value.kecamatan = '';
  formData.value.kelurahan = '';
  if (formData.value.provinsi && locationLookup[formData.value.provinsi]) {
    cities.value = Object.keys(locationLookup[formData.value.provinsi]);
  }
};

const onKotaChange = () => {
  districts.value = [];
  subdistricts.value = [];
  formData.value.kecamatan = '';
  formData.value.kelurahan = '';
  const prov = formData.value.provinsi;
  const city = formData.value.kota_kabupaten;
  if (prov && city && locationLookup[prov]?.[city]) {
    districts.value = Object.keys(locationLookup[prov][city]);
  }
};

const onKecamatanChange = () => {
  subdistricts.value = [];
  formData.value.kelurahan = '';
  const prov = formData.value.provinsi;
  const city = formData.value.kota_kabupaten;
  const kec = formData.value.kecamatan;
  if (prov && city && kec && locationLookup[prov]?.[city]?.[kec]) {
    subdistricts.value = locationLookup[prov][city][kec];
  }
};

// Restore cascading state when editing
const restoreCascade = () => {
  if (formData.value.provinsi && locationLookup[formData.value.provinsi]) {
    cities.value = Object.keys(locationLookup[formData.value.provinsi]);
  }
  const prov = formData.value.provinsi;
  const city = formData.value.kota_kabupaten;
  if (prov && city && locationLookup[prov]?.[city]) {
    districts.value = Object.keys(locationLookup[prov][city]);
  }
  const kec = formData.value.kecamatan;
  if (prov && city && kec && locationLookup[prov]?.[city]?.[kec]) {
    subdistricts.value = locationLookup[prov][city][kec];
  }
};
// --- End location ---

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (file) selectedFile.value = file;
};

const formatTimestamp = (ts) => {
  if (!ts) return '-';
  return new Date(ts).toLocaleString('id-ID');
};

const formatCurrencyFull = (num) => {
  if (!num || Number(num) === 0) return '';
  return '≈ ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(num);
};

const cancelEdit = () => {
  router.push('/master/project');
};

const saveRecord = async () => {
  submitting.value = true;
  errors.value = {};

  try {
    const payload = new FormData();

    Object.keys(formData.value).forEach(key => {
      if (key !== 'lampiran_path' && formData.value[key] !== null && formData.value[key] !== undefined) {
        payload.append(key, formData.value[key]);
      }
    });

    if (selectedFile.value) {
      payload.append('lampiran', selectedFile.value);
    }

    let response;
    if (props.mode === 'edit') {
      payload.append('_method', 'PUT');
      response = await api.post(`/projects/${props.id}`, payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    } else {
      response = await api.post('/projects', payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }

    alert(props.mode === 'edit' ? 'Data proyek berhasil diupdate!' : 'Data proyek berhasil dibuat!');
    router.push('/master/project');
  } catch (error) {
    console.error('Submission failed:', error);
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {};
      alert('Validasi gagal. Silakan periksa kembali input Anda.');
    } else {
      alert('Terjadi kesalahan saat menyimpan data: ' + (error.response?.data?.message || error.message));
    }
  } finally {
    submitting.value = false;
  }
};

const loadRecord = async () => {
  try {
    const res = await api.get(`/projects/${props.id}`);
    const data = res.data;
    formData.value = {
      kode_proyek:               data.kode_proyek              || '',
      nama_proyek:               data.nama_proyek              || '',
      deskripsi:                 data.deskripsi                || '',
      land_bank_id:              data.land_bank_id             || '',
      tipe_proyek:               data.tipe_proyek              || '',
      provinsi:                  data.provinsi                 || '',
      kota_kabupaten:            data.kota_kabupaten           || '',
      kecamatan:                 data.kecamatan                || '',
      kelurahan:                 data.kelurahan                || '',
      alamat_lengkap:            data.alamat_lengkap           || '',
      luas_total:                Number(data.luas_total)       || 0,
      nilai_investasi:           Number(data.nilai_investasi)  || 0,
      tanggal_mulai:             data.tanggal_mulai            || '',
      tanggal_selesai_estimasi:  data.tanggal_selesai_estimasi || '',
      nama_developer:            data.nama_developer           || '',
      nama_pic:                  data.nama_pic                 || '',
      no_telepon_pic:            data.no_telepon_pic           || '',
      lampiran_path:             data.lampiran_path            || null,
      status_doc:                data.status_doc               || 'Draft',
      status:                    data.status                   || 'Aktif',
      updated_at:                data.updated_at               || '',
      updated_by:                data.updated_by               || 'admin',
    };
    restoreCascade();
  } catch (error) {
    console.error('Failed to load project record:', error);
    alert('Gagal memuat detail data proyek.');
    router.push('/master/project');
  }
};

const loadLandBankOptions = async () => {
  try {
    const res = await api.get('/projects/land-bank-options');
    landBankOptions.value = res.data || [];
  } catch (e) {
    console.warn('Could not load land bank options', e);
  }
};

onMounted(() => {
  loadLandBankOptions();
  if (props.mode === 'edit' && props.id) {
    loadRecord();
  }
});
</script>

<style scoped>
.project-form-view {
  animation: fadeIn 0.3s ease-in-out;
}

.section-heading {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--text-color);
  margin: 8px 0 16px;
  padding-bottom: 10px;
  border-bottom: 1px dashed var(--border-color);
}

.error-text {
  font-size: 0.75rem;
  color: var(--danger-color);
  margin-top: 2px;
}

.input-prefix {
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 0.85rem;
  color: var(--text-muted);
  pointer-events: none;
  font-weight: 600;
}

.input-hint {
  font-size: 0.72rem;
  color: var(--text-muted);
  margin-top: 2px;
}

.btn-upload {
  background-color: #f0fdf4;
  color: #166534;
  border: 1px solid #bbf7d0;
  font-size: 0.8rem;
  padding: 6px 14px;
  font-weight: 600;
  border-radius: 4px;
  cursor: pointer;
  white-space: nowrap;
}

.btn-upload:hover {
  background-color: #dcfce7;
}

.btn-preview {
  font-size: 0.8rem;
  color: var(--primary-color);
  text-decoration: none;
  padding: 4px 10px;
  border: 1px solid var(--primary-color);
  border-radius: 4px;
}

.btn-preview:hover {
  background-color: #eff6ff;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
