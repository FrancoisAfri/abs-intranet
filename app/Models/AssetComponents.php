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


    public function AssetsList(){
        return $this->belongsTo(Assets::class, 'asset_id')->orderBy('id');
    }


    public static function getAssetComponents($id)
    {
        return AssetComponents::where(
            'asset_id', $id
        )->get();
    }
}
