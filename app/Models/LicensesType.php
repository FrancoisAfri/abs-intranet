<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LicensesType extends Model
{
    public $table = 'licence_type';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function licence(): HasMany
    {
        return $this->hasMany(AssetType::class, 'assetType_id', 'id');
    }
}
