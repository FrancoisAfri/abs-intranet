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

}
