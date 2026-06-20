<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProject extends Model
{
    use HasFactory;

    protected $table = 'master_projects';

    protected $fillable = [
        'kode_proyek',
        'nama_proyek',
        'deskripsi',
        'land_bank_id',
        'provinsi',
        'kota_kabupaten',
        'kecamatan',
        'kelurahan',
        'alamat_lengkap',
        'tipe_proyek',
        'luas_total',
        'nilai_investasi',
        'tanggal_mulai',
        'tanggal_selesai_estimasi',
        'nama_developer',
        'nama_pic',
        'no_telepon_pic',
        'lampiran_path',
        'status_doc',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'luas_total'              => 'decimal:2',
        'nilai_investasi'         => 'decimal:2',
        'tanggal_mulai'           => 'date:Y-m-d',
        'tanggal_selesai_estimasi'=> 'date:Y-m-d',
    ];

    /** Proyek punya satu land bank (optional) */
    public function landBank()
    {
        return $this->belongsTo(LandBank::class, 'land_bank_id');
    }
}
