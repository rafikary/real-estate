<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateSertifikat extends Model
{
    use HasFactory;

    protected $table = 'update_sertifikats';

    protected $fillable = [
        'kode_update',
        'land_bank_id',
        'jenis_perubahan',
        'no_perubahan',
        'tgl_perubahan',
        'keterangan',
        'jenis_sertifikat_baru',
        'no_sertifikat_baru',
        'nib_baru',
        'pemilik_baru',
        'luas_sertifikat_baru',
        'tanggal_terbit_baru',
        'masa_berlaku_baru',
        'nama_notaris',
        'no_akta',
        'lampiran_path',
        'status_doc',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_perubahan'       => 'date:Y-m-d',
        'tanggal_terbit_baru' => 'date:Y-m-d',
        'masa_berlaku_baru'   => 'date:Y-m-d',
        'luas_sertifikat_baru'=> 'decimal:2',
    ];

    public function landBank()
    {
        return $this->belongsTo(LandBank::class, 'land_bank_id');
    }
}
