<?php

namespace Database\Seeders;

use App\Models\LandBank;
use App\Models\MasterProject;
use App\Models\UpdateSertifikat;
use App\Models\Siteplan;
use App\Models\SplitGroupingLand;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ───────────────────────────────────────────────────────
        User::factory()->create([
            'name'  => 'Admin Real Estate',
            'email' => 'admin@realestate.com',
        ]);

        // ── Land Banks ───────────────────────────────────────────────────────
        $landBankData = [
            [
                'code' => 'LAND-001',
                'name' => 'Lahan Genteng Mas',
                'loc' => 'Jl. Mas No. 12',
                'prov_id' => 1,
                'city_id' => 11,
                'district_id' => 111,
                'postcode' => '60272',
                'longitude' => '112.7483',
                'latitiude' => '-7.2639',
                'limit_n' => 'Tanah Pak Budi',
                'limit_s' => 'Jalan Mas',
                'limit_e' => 'Tanah Bu Ani',
                'limit_w' => 'Ruko Sentosa',
                'cert_type' => 'SHM',
                'cert_no' => '01.02.03.04.05.78',
                'nib' => '012345678910',
                'owner' => 'Slamet',
                'area_cert' => 100,
                'area_real' => 100,
                'cert_date' => '2026-01-10',
                'cert_date_to' => null,
                'reference' => 'REF-001',
                'status_doc' => 'In Approval',
                'status' => 'Aktif',
                'created_by' => 'admin'
            ],
            [
                'code' => 'LAND-002',
                'name' => 'Lahan Wonorejo Indah',
                'loc' => 'Jl. Wonorejo Indah No. 4',
                'prov_id' => 1,
                'city_id' => 11,
                'district_id' => 112,
                'postcode' => '60293',
                'longitude' => '112.7842',
                'latitiude' => '-7.3194',
                'limit_n' => 'Lahan Kosong',
                'limit_s' => 'Jl. Wonorejo',
                'limit_e' => 'Tanah Pak Joko',
                'limit_w' => 'Tanah Pak Ahmad',
                'cert_type' => 'SHM',
                'cert_no' => '01.02.03.04.05.79',
                'nib' => '012345678911',
                'owner' => 'Bambang',
                'area_cert' => 250,
                'area_real' => 250,
                'cert_date' => '2026-02-15',
                'cert_date_to' => null,
                'reference' => 'REF-002',
                'status_doc' => 'Approved',
                'status' => 'Aktif',
                'created_by' => 'admin'
            ],
            [
                'code' => 'LAND-003',
                'name' => 'Lahan Srengseng Raya',
                'loc' => 'Jl. Srengseng Raya No. 45',
                'prov_id' => 1,
                'city_id' => 11,
                'district_id' => 113,
                'postcode' => '60217',
                'longitude' => '112.6582',
                'latitiude' => '-7.2847',
                'limit_n' => 'Perumahan Graha',
                'limit_s' => 'Sawah Pak Tarmo',
                'limit_e' => 'Jl. Srengseng',
                'limit_w' => 'Saluran Air',
                'cert_type' => 'SHM',
                'cert_no' => '01.02.03.04.05.80',
                'nib' => '012345678912',
                'owner' => 'Joko Widodo',
                'area_cert' => 300,
                'area_real' => 300,
                'cert_date' => '2026-03-20',
                'cert_date_to' => null,
                'reference' => 'REF-003',
                'status_doc' => 'Approved',
                'status' => 'Aktif',
                'created_by' => 'admin'
            ],
            [
                'code' => 'LAND-004',
                'name' => 'Lahan Keputih ITS',
                'loc' => 'Jl. Kaboen Kulon',
                'prov_id' => 1,
                'city_id' => 11,
                'district_id' => 113,
                'postcode' => '60111',
                'longitude' => '112.8012',
                'latitiude' => '-7.2910',
                'limit_n' => 'Tanah Wakaf',
                'limit_s' => 'Jalan Kampus',
                'limit_e' => 'Rawa',
                'limit_w' => 'Kos-kosan',
                'cert_type' => 'SHM',
                'cert_no' => '01.02.03.04.05.81',
                'nib' => '012345678913',
                'owner' => 'Hasan',
                'area_cert' => 250,
                'area_real' => 245,
                'cert_date' => '2026-04-10',
                'cert_date_to' => null,
                'reference' => 'REF-004',
                'status_doc' => 'Approved',
                'status' => 'Aktif',
                'created_by' => 'admin'
            ],
            [
                'code' => 'LAND-005',
                'name' => 'Lahan Gula Pabean',
                'loc' => 'Jl. Gula No. 9',
                'prov_id' => 1,
                'city_id' => 11,
                'district_id' => 111,
                'postcode' => '60161',
                'longitude' => '112.7410',
                'latitiude' => '-7.2341',
                'limit_n' => 'Gudang Lama',
                'limit_s' => 'Jl. Gula',
                'limit_e' => 'Toko Kelontong',
                'limit_w' => 'Rumah Warga',
                'cert_type' => 'SHM',
                'cert_no' => '01.02.03.04.05.82',
                'nib' => '012345678914',
                'owner' => 'Suwondo',
                'area_cert' => 500,
                'area_real' => 500,
                'cert_date' => '2026-05-05',
                'cert_date_to' => null,
                'reference' => 'REF-005',
                'status_doc' => 'Approved',
                'status' => 'Aktif',
                'created_by' => 'admin'
            ],
        ];

        foreach ($landBankData as $item) {
            LandBank::create($item);
        }

        $landBanks = LandBank::all()->keyBy('code');

        // ── Master Projects ──────────────────────────────────────────────────
        $projects = [
            ['kode_proyek'=>'PRJ-001','nama_proyek'=>'Perumahan Graha Rungkut Indah','deskripsi'=>'Perumahan cluster modern di kawasan Rungkut, Surabaya Timur.','land_bank_id'=>$landBanks['LAND-002']?->id,'provinsi'=>'Jawa Timur','kota_kabupaten'=>'Surabaya','kecamatan'=>'Rungkut','kelurahan'=>'Wonorejo','alamat_lengkap'=>'Jl. Wonorejo Indah No. 4, Rungkut, Surabaya','tipe_proyek'=>'Perumahan','luas_total'=>5000,'nilai_investasi'=>25000000000,'tanggal_mulai'=>'2026-07-01','tanggal_selesai_estimasi'=>'2028-12-31','nama_developer'=>'PT Graha Nusantara','nama_pic'=>'Budi Santoso','no_telepon_pic'=>'081234567890','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
            ['kode_proyek'=>'PRJ-002','nama_proyek'=>'Ruko Genteng Business Center','deskripsi'=>'Kawasan ruko 3 lantai strategis di pusat kota Surabaya.','land_bank_id'=>$landBanks['LAND-001']?->id,'provinsi'=>'Jawa Timur','kota_kabupaten'=>'Surabaya','kecamatan'=>'Genteng','kelurahan'=>'Ketabang','alamat_lengkap'=>'Jl. Mas No. 12, Genteng, Surabaya','tipe_proyek'=>'Komersial','luas_total'=>1200,'nilai_investasi'=>18000000000,'tanggal_mulai'=>'2026-09-01','tanggal_selesai_estimasi'=>'2027-12-31','nama_developer'=>'PT Mas Properti','nama_pic'=>'Siti Rahayu','no_telepon_pic'=>'082345678901','status_doc'=>'In Approval','status'=>'Aktif','created_by'=>'admin'],
            ['kode_proyek'=>'PRJ-003','nama_proyek'=>'Sambikerep Mixed-Use Tower','deskripsi'=>'Tower mixed-use hunian dan SOHO di Sambikerep.','land_bank_id'=>$landBanks['LAND-003']?->id,'provinsi'=>'Jawa Timur','kota_kabupaten'=>'Surabaya','kecamatan'=>'Sambikerep','kelurahan'=>'Lontar','alamat_lengkap'=>'Jl. Srengseng Raya No. 45, Sambikerep, Surabaya','tipe_proyek'=>'Mixed-Use','luas_total'=>8000,'nilai_investasi'=>75000000000,'tanggal_mulai'=>'2027-01-01','tanggal_selesai_estimasi'=>'2030-06-30','nama_developer'=>'PT Sambikerep Jaya','nama_pic'=>'Andi Wijaya','no_telepon_pic'=>'083456789012','status_doc'=>'Draft','status'=>'Aktif','created_by'=>'admin'],
            ['kode_proyek'=>'PRJ-004','nama_proyek'=>'Keputih Industrial Park','deskripsi'=>'Kawasan industri modern di Sukolilo dekat ITS Surabaya.','land_bank_id'=>$landBanks['LAND-004']?->id,'provinsi'=>'Jawa Timur','kota_kabupaten'=>'Surabaya','kecamatan'=>'Sukolilo','kelurahan'=>'Keputih','alamat_lengkap'=>'Jl. Kaboen Kulon, Sukolilo, Surabaya','tipe_proyek'=>'Industri','luas_total'=>15000,'nilai_investasi'=>120000000000,'tanggal_mulai'=>'2027-03-01','tanggal_selesai_estimasi'=>'2031-12-31','nama_developer'=>'PT Keputih Industrial','nama_pic'=>'Hendra Kusuma','no_telepon_pic'=>'084567890123','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
        ];

        foreach ($projects as $proj) {
            MasterProject::create($proj);
        }

        $masterProjects = MasterProject::all()->keyBy('kode_proyek');

        // ── Update Sertifikat ────────────────────────────────────────────────
        $updateSerts = [
            ['kode_update'=>'UPS-001','land_bank_id'=>$landBanks['LAND-001']?->id,'jenis_perubahan'=>'Balik Nama','no_perubahan'=>'AKT-2026-001','tgl_perubahan'=>'2026-03-15','keterangan'=>'Balik nama dari Slamet ke PT Mas Properti','jenis_sertifikat_baru'=>'SHM','no_sertifikat_baru'=>'01.02.03.04.05.78A','pemilik_baru'=>'PT Mas Properti','luas_sertifikat_baru'=>100,'nama_notaris'=>'Notaris Budiman, SH','no_akta'=>'AKT-2026-001','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
            ['kode_update'=>'UPS-002','land_bank_id'=>$landBanks['LAND-002']?->id,'jenis_perubahan'=>'Perpanjang Masa Sertifikat','no_perubahan'=>'AKT-2026-002','tgl_perubahan'=>'2026-04-01','keterangan'=>'Perpanjang masa berlaku HGB','masa_berlaku_baru'=>'2056-04-01','nama_notaris'=>'Notaris Sari, SH','no_akta'=>'AKT-2026-002','status_doc'=>'In Approval','status'=>'Aktif','created_by'=>'admin'],
            ['kode_update'=>'UPS-003','land_bank_id'=>$landBanks['LAND-003']?->id,'jenis_perubahan'=>'Perubahan Jenis Hak','no_perubahan'=>'AKT-2026-003','tgl_perubahan'=>'2026-05-10','keterangan'=>'Dari HGB menjadi SHM','jenis_sertifikat_baru'=>'SHM','status_doc'=>'Draft','status'=>'Aktif','created_by'=>'admin'],
        ];

        foreach ($updateSerts as $us) {
            UpdateSertifikat::create($us);
        }

        // Seeding detail riwayat legalitas histories
        foreach ($updateSerts as $idx => $us) {
            $lb = LandBank::find($us['land_bank_id']);
            if ($lb && $us['status_doc'] === 'Approved') {
                $lb->histories()->create([
                    'seq' => 1,
                    'type' => $us['jenis_perubahan'],
                    'cert_type' => $us['jenis_sertifikat_baru'] ?: $lb->cert_type,
                    'cert_no' => $us['no_sertifikat_baru'] ?: $lb->cert_no,
                    'nib' => $lb->nib,
                    'owner' => $us['pemilik_baru'] ?: $lb->owner,
                    'area_cert' => $us['luas_sertifikat_baru'] ?: $lb->area_cert,
                    'area_real' => $lb->area_real,
                    'cert_date' => $us['tgl_perubahan'],
                    'cert_date_to' => null,
                    'reference' => $us['kode_update'],
                    'image' => null,
                ]);
            }
        }

        // ── Siteplan ─────────────────────────────────────────────────────────
        $siteplans = [
            ['kode_siteplan'=>'SPL-001','master_project_id'=>$masterProjects['PRJ-001']?->id,'nama_siteplan'=>'Cluster Melati Type 36/60','tipe_unit'=>'Rumah','cluster'=>'Cluster A','luas_tanah'=>60,'luas_bangunan'=>36,'jumlah_lantai'=>1,'jumlah_unit'=>40,'harga_dasar'=>450000000,'kamar_tidur'=>2,'kamar_mandi'=>1,'orientasi'=>'Utara','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
            ['kode_siteplan'=>'SPL-002','master_project_id'=>$masterProjects['PRJ-001']?->id,'nama_siteplan'=>'Cluster Mawar Type 54/90','tipe_unit'=>'Rumah','cluster'=>'Cluster B','luas_tanah'=>90,'luas_bangunan'=>54,'jumlah_lantai'=>2,'jumlah_unit'=>25,'harga_dasar'=>750000000,'kamar_tidur'=>3,'kamar_mandi'=>2,'orientasi'=>'Timur','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
            ['kode_siteplan'=>'SPL-003','master_project_id'=>$masterProjects['PRJ-002']?->id,'nama_siteplan'=>'Ruko 3 Lantai Tipe A','tipe_unit'=>'Ruko','cluster'=>'Blok A','luas_tanah'=>120,'luas_bangunan'=>360,'jumlah_lantai'=>3,'jumlah_unit'=>10,'harga_dasar'=>2500000000,'status_doc'=>'In Approval','status'=>'Aktif','created_by'=>'admin'],
            ['kode_siteplan'=>'SPL-004','master_project_id'=>$masterProjects['PRJ-003']?->id,'nama_siteplan'=>'Studio Unit Tower A','tipe_unit'=>'Apartemen','cluster'=>'Tower A','luas_tanah'=>28,'luas_bangunan'=>28,'jumlah_lantai'=>1,'jumlah_unit'=>120,'harga_dasar'=>350000000,'kamar_tidur'=>1,'kamar_mandi'=>1,'status_doc'=>'Draft','status'=>'Aktif','created_by'=>'admin'],
        ];

        foreach ($siteplans as $sp) {
            Siteplan::create($sp);
        }

        // ── Split / Grouping Land ────────────────────────────────────────────
        $splitGroupings = [
            ['kode_transaksi'=>'SPG-001','tipe'=>'Split','tgl_transaksi'=>'2026-05-01','land_bank_id_sumber'=>$landBanks['LAND-005']?->id,'keterangan'=>'Pecah lahan 500m² menjadi 2 bidang @250m²','nama_notaris'=>'Notaris Rahardjo, SH','no_akta'=>'AKT-2026-004','status_doc'=>'Approved','status'=>'Aktif','created_by'=>'admin'],
            ['kode_transaksi'=>'SPG-002','tipe'=>'Grouping','tgl_transaksi'=>'2026-06-01','land_bank_id_sumber'=>$landBanks['LAND-003']?->id,'keterangan'=>'Gabung lahan Srengseng dengan lahan tetangga','nama_notaris'=>'Notaris Slamet, SH','no_akta'=>'AKT-2026-005','status_doc'=>'In Approval','status'=>'Aktif','created_by'=>'admin'],
        ];

        foreach ($splitGroupings as $sg) {
            SplitGroupingLand::create($sg);
        }
    }
}
