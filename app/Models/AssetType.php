<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    public $table = 'asset_type';

    protected $fillable = [
        'name',
        'description',
        'status',
    ];
}
