<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInterface extends Model
{
    use HasFactory;

    protected $table = 'm_interface';

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
        'type',
        'var_id',
        'note',
    ];

    public function details()
    {
        return $this->hasMany(MasterInterfaceDetail::class, 'interface_id');
    }
}
