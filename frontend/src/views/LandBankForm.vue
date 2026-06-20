<template>
  <div class="land-bank-form-view">
    <div class="card">
      <h2 class="card-title">{{ mode === 'edit' ? 'Edit Land Bank' : 'Tambah Land Bank' }}</h2>

      <form @submit.prevent="saveRecord">
        <!-- 1. Tambah Land Bank Main Form -->
        <div class="form-grid">
          
          <div class="form-group col-4">
            <label class="form-label">Kode Land Bank</label>
            <input 
              type="text" 
              class="form-control" 
              :value="formData.code || 'Auto Generate'" 
              disabled 
            />
          </div>

          <div class="form-group col-8">
            <label class="form-label">Nama Lahan <span class="required">*</span></label>
            <input 
              type="text" 
              class="form-control" 
              v-model="formData.name" 
              placeholder="Masukkan nama lahan"
              required
            />
            <span v-if="errors.name" class="error-text">{{ errors.name[0] }}</span>
          </div>

          <div class="form-group col-12">
            <label class="form-label">Lokasi Lahan Detail <span class="required">*</span></label>
            <textarea 
              class="form-control" 
              v-model="formData.loc" 
              placeholder="Tulis lokasi lahan detail"
              rows="2"
              required
            ></textarea>
            <span v-if="errors.loc" class="error-text">{{ errors.loc[0] }}</span>
          </div>

          <!-- Cascading Dropdowns using integer IDs -->
          <div class="form-group col-3">
            <label class="form-label">Provinsi <span class="required">*</span></label>
            <select class="form-control" v-model.number="formData.prov_id" @change="onProvinsiChange" required>
              <option value="">Pilih Provinsi</option>
              <option v-for="prov in provincesList" :key="prov.id" :value="prov.id">{{ prov.name }}</option>
            </select>
            <span v-if="errors.prov_id" class="error-text">{{ errors.prov_id[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kota/Kabupaten <span class="required">*</span></label>
            <select class="form-control" v-model.number="formData.city_id" @change="onKotaChange" :disabled="!formData.prov_id" required>
              <option value="">Pilih Kota</option>
              <option v-for="city in filteredCities" :key="city.id" :value="city.id">{{ city.name }}</option>
            </select>
            <span v-if="errors.city_id" class="error-text">{{ errors.city_id[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kecamatan <span class="required">*</span></label>
            <select class="form-control" v-model.number="formData.district_id" :disabled="!formData.city_id" required>
              <option value="">Pilih Kecamatan</option>
              <option v-for="kec in filteredDistricts" :key="kec.id" :value="kec.id">{{ kec.name }}</option>
            </select>
            <span v-if="errors.district_id" class="error-text">{{ errors.district_id[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Kode Pos</label>
            <input type="text" class="form-control" v-model="formData.postcode" placeholder="E.g. 60272" />
            <span v-if="errors.postcode" class="error-text">{{ errors.postcode[0] }}</span>
          </div>

          <!-- Map Coordinates -->
          <div class="form-group col-4">
            <label class="form-label">Longitude</label>
            <input 
              type="text" 
              class="form-control" 
              v-model="formData.longitude" 
              placeholder="112.7508"
            />
          </div>

          <div class="form-group col-4">
            <label class="form-label">Latitude</label>
            <input 
              type="text" 
              class="form-control" 
              v-model="formData.latitiude" 
              placeholder="-7.2504"
            />
          </div>

          <div class="form-group col-4">
            <button type="button" class="btn-pinpoint" @click="generatePinpoint">
              📍 + Pin Point (Auto-Fill)
            </button>
          </div>

          <!-- Boundaries -->
          <div class="form-group col-3">
            <label class="form-label">Batas Wilayah Utara</label>
            <input type="text" class="form-control" v-model="formData.limit_n" placeholder="Batas Utara" />
          </div>
          <div class="form-group col-3">
            <label class="form-label">Batas Wilayah Timur</label>
            <input type="text" class="form-control" v-model="formData.limit_e" placeholder="Batas Timur" />
          </div>
          <div class="form-group col-3">
            <label class="form-label">Batas Wilayah Selatan</label>
            <input type="text" class="form-control" v-model="formData.limit_s" placeholder="Batas Selatan" />
          </div>
          <div class="form-group col-3">
            <label class="form-label">Batas Wilayah Barat</label>
            <input type="text" class="form-control" v-model="formData.limit_w" placeholder="Batas Barat" />
          </div>
        </div>

        <!-- 2. Detail Sertifikat Awal Section -->
        <h3 class="card-title" style="margin-top: 30px; font-size: 0.95rem;">Detail Sertifikat Awal</h3>
        <div class="form-grid">
          <div class="form-group col-3">
            <label class="form-label">Jenis Sertifikat <span class="required">*</span></label>
            <select class="form-control" v-model="formData.cert_type" required>
              <option value="">Pilih Sertifikat</option>
              <option value="SHM">SHM</option>
              <option value="HGB">HGB</option>
              <option value="Petok D">Petok D</option>
              <option value="HP">HP (Hak Pakai)</option>
              <option value="HGU">HGU (Hak Guna Usaha)</option>
            </select>
            <span v-if="errors.cert_type" class="error-text">{{ errors.cert_type[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">No. Sertifikat <span class="required">*</span></label>
            <input type="text" class="form-control" v-model="formData.cert_no" placeholder="E.g. 01.02.03.04.05.78" required />
            <span v-if="errors.cert_no" class="error-text">{{ errors.cert_no[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">NIB</label>
            <input type="text" class="form-control" v-model="formData.nib" placeholder="Masukkan NIB" />
          </div>

          <div class="form-group col-3">
            <label class="form-label">Nama Pemilik</label>
            <input type="text" class="form-control" v-model="formData.owner" placeholder="E.g. Slamet" />
          </div>

          <div class="form-group col-3">
            <label class="form-label">Luas Sertifikat (m²) <span class="required">*</span></label>
            <input type="number" step="0.01" class="form-control" v-model.number="formData.area_cert" required />
            <span v-if="errors.area_cert" class="error-text">{{ errors.area_cert[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Luas Fisik (m²) <span class="required">*</span></label>
            <input type="number" step="0.01" class="form-control" v-model.number="formData.area_real" required />
            <span v-if="errors.area_real" class="error-text">{{ errors.area_real[0] }}</span>
          </div>

          <div class="form-group col-3">
            <label class="form-label">Tanggal Sertifikat</label>
            <input type="date" class="form-control" v-model="formData.cert_date" />
          </div>

          <div class="form-group col-3">
            <label class="form-label">Berlaku Sampai</label>
            <input type="date" class="form-control" v-model="formData.cert_date_to" />
          </div>

          <div class="form-group col-6">
            <label class="form-label">Referensi</label>
            <input type="text" class="form-control" v-model="formData.reference" placeholder="Masukkan nomor referensi" />
          </div>

          <div class="form-group col-6">
            <label class="form-label">Lampiran (PDF/Gambar)</label>
            <div class="file-upload-wrapper">
              <input type="file" ref="fileInput" style="display: none;" @change="onFileChange" accept=".pdf,image/*" />
              <button type="button" class="btn-upload" @click="$refs.fileInput.click()">
                {{ selectedFile ? 'Ganti File' : 'Unggah File' }}
              </button>
              <span class="file-name" style="font-size: 0.8rem; color: var(--text-muted);">
                {{ selectedFile ? selectedFile.name : (formData.image ? 'Attachment exists' : 'Belum ada berkas dipilih') }}
              </span>
              <a 
                v-if="formData.image" 
                :href="'http://localhost:8000' + formData.image" 
                target="_blank" 
                class="btn-preview"
              >
                Preview
              </a>
            </div>
          </div>
        </div>

        <!-- 3. Detail Riwayat Legalitas (READ-ONLY Display Table) -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-bottom: 16px;">
          <h3 style="font-size: 0.95rem; font-weight: 600;">Detail Riwayat Legalitas (Read-Only)</h3>
        </div>

        <div class="table-wrapper" style="margin-bottom: 24px;">
          <table class="data-table">
            <thead>
              <tr>
                <th style="width: 40px;">No</th>
                <th>Jenis Perubahan</th>
                <th>No Perubahan (Ref)</th>
                <th>Jenis Sertifikat</th>
                <th>No Sertifikat</th>
                <th>Nama Pemilik</th>
                <th>Luas Cert (m²)</th>
                <th>Luas Real (m²)</th>
                <th>Tgl Sertifikat</th>
                <th>Berlaku Sampai</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="formData.histories.length === 0">
                <td colspan="10" style="text-align: center; padding: 20px; color: var(--text-muted);">
                  Belum ada riwayat legalitas. Riwayat akan dicatat otomatis dari transaksi perubahan sertifikat.
                </td>
              </tr>
              <tr v-else v-for="(hist, index) in formData.histories" :key="hist.id || index">
                <td>{{ hist.seq || (index + 1) }}</td>
                <td style="font-weight: 600; color: var(--primary-color);">{{ hist.type }}</td>
                <td>{{ hist.reference || '-' }}</td>
                <td>{{ hist.cert_type }}</td>
                <td>{{ hist.cert_no }}</td>
                <td>{{ hist.owner || '-' }}</td>
                <td>{{ formatNumber(hist.area_cert) }}</td>
                <td>{{ formatNumber(hist.area_real) }}</td>
                <td>{{ formatDate(hist.cert_date) }}</td>
                <td>{{ formatDate(hist.cert_date_to) }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Document Status and Publication Status inside Form -->
        <div class="form-grid">
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

        <!-- Form Actions Footer -->
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
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import api from '../utils/api';

const props = defineProps({
  id: {
    type: String,
    default: null
  },
  mode: {
    type: String,
    default: 'create'
  }
});

const router = useRouter();
const submitting = ref(false);
const selectedFile = ref(null);
const fileInput = ref(null);
const errors = ref({});

// Form data defaults
const formData = ref({
  code: '',
  name: '',
  loc: '',
  prov_id: '',
  city_id: '',
  district_id: '',
  postcode: '',
  longitude: '',
  latitiude: '',
  limit_s: '',
  limit_w: '',
  limit_n: '',
  limit_e: '',
  cert_type: '',
  cert_no: '',
  nib: '',
  owner: '',
  area_cert: 0,
  area_real: 0,
  cert_date: '',
  cert_date_to: '',
  reference: '',
  status_doc: 'Draft',
  status: 'Aktif',
  image: null,
  histories: []
});

// Mock regional location structure with integer keys
const provincesList = [
  { id: 1, name: 'Jawa Timur' },
  { id: 2, name: 'DKI Jakarta' }
];

const citiesList = [
  { id: 11, prov_id: 1, name: 'Surabaya' },
  { id: 12, prov_id: 1, name: 'Kota Malang' },
  { id: 21, prov_id: 2, name: 'Jakarta Pusat' },
  { id: 22, prov_id: 2, name: 'Jakarta Selatan' }
];

const districtsList = [
  { id: 111, city_id: 11, name: 'Genteng' },
  { id: 112, city_id: 11, name: 'Rungkut' },
  { id: 113, city_id: 11, name: 'Sukolilo' },
  { id: 121, city_id: 12, name: 'Blimbing' },
  { id: 122, city_id: 12, name: 'Klojen' },
  { id: 211, city_id: 21, name: 'Menteng' },
  { id: 212, city_id: 21, name: 'Gambir' },
  { id: 221, city_id: 22, name: 'Tebet' },
  { id: 222, city_id: 22, name: 'Cilandak' }
];

const filteredCities = computed(() => {
  if (!formData.value.prov_id) return [];
  return citiesList.filter(c => c.prov_id === formData.value.prov_id);
});

const filteredDistricts = computed(() => {
  if (!formData.value.city_id) return [];
  return districtsList.filter(d => d.city_id === formData.value.city_id);
});

const onProvinsiChange = () => {
  formData.value.city_id = '';
  formData.value.district_id = '';
};

const onKotaChange = () => {
  formData.value.district_id = '';
};

const generatePinpoint = () => {
  // Generate slightly realistic Indonesia coordinate points
  const baseLat = -7.2504 + (Math.random() - 0.5) * 0.1;
  const baseLng = 112.7508 + (Math.random() - 0.5) * 0.1;
  formData.value.latitiude = baseLat.toFixed(8);
  formData.value.longitude = baseLng.toFixed(8);
};

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    selectedFile.value = file;
  }
};

const formatTimestamp = (ts) => {
  if (!ts) return '-';
  const d = new Date(ts);
  return d.toLocaleString('id-ID');
};

const formatDate = (d) => {
  if (!d) return '-';
  return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const formatNumber = (num) => {
  if (!num) return '0';
  return Number(num).toLocaleString('id-ID');
};

const cancelEdit = () => {
  router.push('/master/land-bank');
};

const saveRecord = async () => {
  submitting.value = true;
  errors.value = {};

  try {
    // We use FormData to support file upload
    const payload = new FormData();
    
    // Append standard fields
    Object.keys(formData.value).forEach(key => {
      if (key !== 'histories' && key !== 'image' && formData.value[key] !== null && formData.value[key] !== '') {
        payload.append(key, formData.value[key]);
      }
    });

    // Append file attachment
    if (selectedFile.value) {
      payload.append('lampiran', selectedFile.value);
    }

    let response;
    if (props.mode === 'edit') {
      payload.append('_method', 'PUT');
      response = await api.post(`/land-banks/${props.id}`, payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    } else {
      response = await api.post('/land-banks', payload, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
    }

    alert(props.mode === 'edit' ? 'Data Land Bank berhasil diupdate' : 'Data Land Bank berhasil dibuat');
    router.push('/master/land-bank');
  } catch (error) {
    console.error('Submission failed:', error);
    if (error.response && error.response.status === 422) {
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
    const res = await api.get(`/land-banks/${props.id}`);
    const data = res.data;
    
    formData.value = {
      code: data.code || '',
      name: data.name || '',
      loc: data.loc || '',
      prov_id: Number(data.prov_id) || '',
      city_id: Number(data.city_id) || '',
      district_id: Number(data.district_id) || '',
      postcode: data.postcode || '',
      longitude: data.longitude || '',
      latitiude: data.latitiude || '',
      limit_s: data.limit_s || '',
      limit_w: data.limit_w || '',
      limit_n: data.limit_n || '',
      limit_e: data.limit_e || '',
      cert_type: data.cert_type || '',
      cert_no: data.cert_no || '',
      nib: data.nib || '',
      owner: data.owner || '',
      area_cert: Number(data.area_cert) || 0,
      area_real: Number(data.area_real) || 0,
      cert_date: data.cert_date || '',
      cert_date_to: data.cert_date_to || '',
      reference: data.reference || '',
      status_doc: data.status_doc || 'Draft',
      status: data.status || 'Aktif',
      image: data.image || null,
      updated_at: data.updated_at || '',
      updated_by: data.updated_by || 'admin',
      histories: data.histories || []
    };
  } catch (error) {
    console.error('Failed to load land bank record:', error);
    alert('Gagal memuat detail data.');
    router.push('/master/land-bank');
  }
};

onMounted(() => {
  if (props.mode === 'edit' && props.id) {
    loadRecord();
  }
});
</script>

<style scoped>
.land-bank-form-view {
  animation: fadeIn 0.3s ease-in-out;
}

.error-text {
  font-size: 0.75rem;
  color: var(--danger-color);
  margin-top: 2px;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
