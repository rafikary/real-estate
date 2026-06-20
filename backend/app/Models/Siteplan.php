<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siteplan extends Model
{
    use HasFactory;

    protected $table = 'siteplans';

    protected $fillable = [
        'kode_siteplan',
        'master_project_id',
        'nama_siteplan',
        'deskripsi',
        'tipe_unit',
        'cluster',
        'luas_tanah',
        'luas_bangunan',
        'jumlah_lantai',
        'jumlah_unit',
        'harga_dasar',
        'kamar_tidur',
        'kamar_mandi',
        'orientasi',
        'lampiran_path',
        'status_doc',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'luas_tanah'    => 'decimal:2',
        'luas_bangunan' => 'decimal:2',
        'harga_dasar'   => 'decimal:2',
    ];

    public function masterProject()
    {
        return $this->belongsTo(MasterProject::class, 'master_project_id');
    }
}
