<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class AssetImagesTransfers extends Model
{
    //assets_transfer_images

    use Uuids;

    public $table = 'assets_transfer_images';

    public $timestamps = true;

    protected $fillable = [
        'picture', 'asset_id', 'status'
    ];

    public static function getImagesById($asset_id, $date)
    {
        return AssetImagesTransfers::where(
            [
                'asset_id' => $asset_id,
                'created_at' => $date,
            ])->get();


    }


}
