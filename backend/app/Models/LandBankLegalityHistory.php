<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandBankLegalityHistory extends Model
{
    use HasFactory;

    protected $table = 'm_landbank_d_hist';

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $fillable = [
        'm_landbank_id',
        'seq',
        'type',
        'cert_type',
        'cert_no',
        'nib',
        'owner',
        'area_cert',
        'area_real',
        'cert_date',
        'cert_date_to',
        'reference',
        'image',
    ];

    protected $casts = [
        'seq' => 'integer',
        'area_cert' => 'decimal:2',
        'area_real' => 'decimal:2',
        'cert_date' => 'date:Y-m-d',
        'cert_date_to' => 'date:Y-m-d',
    ];

    public function landBank()
    {
        return $this->belongsTo(LandBank::class, 'm_landbank_id');
    }
}
