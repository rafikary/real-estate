<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplitGroupingLand extends Model
{
    use HasFactory;

    protected $table = 'split_grouping_lands';

    protected $fillable = [
        'kode_transaksi',
        'tipe',
        'tgl_transaksi',
        'land_bank_id_sumber',
        'land_bank_ids_hasil',
        'keterangan',
        'nama_notaris',
        'no_akta',
        'lampiran_path',
        'status_doc',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_transaksi'      => 'date:Y-m-d',
        'land_bank_ids_hasil' => 'array',
    ];

    public function landBankSumber()
    {
        return $this->belongsTo(LandBank::class, 'land_bank_id_sumber');
    }
}
