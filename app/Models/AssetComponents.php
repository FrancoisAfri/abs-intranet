<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class AssetComponents extends Model
{
    use Uuids;

    public $table = 'asset_components';
    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'user_id',
        'asset_id', 'size', 'status'
    ];


    public static function getAssetComponents($id)
    {
        return AssetComponents::where(
            'asset_id', $id
        )->get();
    }
}
