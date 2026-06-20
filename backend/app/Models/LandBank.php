<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandBank extends Model
{
    use HasFactory;

    protected $table = 'm_landbank';

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
        'code',
        'name',
        'loc',
        'prov_id',
        'city_id',
        'district_id',
        'postcode',
        'latitiude',
        'longitude',
        'limit_n',
        'limit_e',
        'limit_s',
        'limit_w',
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
        'status_doc',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'prov_id' => 'integer',
        'city_id' => 'integer',
        'district_id' => 'integer',
        'area_cert' => 'decimal:2',
        'area_real' => 'decimal:2',
        'cert_date' => 'date:Y-m-d',
        'cert_date_to' => 'date:Y-m-d',
    ];

    public function histories()
    {
        return $this->hasMany(LandBankLegalityHistory::class, 'm_landbank_id');
    }
}
