<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class AssetTransfers extends Model
{
    use Uuids;

    public $table = 'asset_transfer';
    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'user_id',
        'asset_id', 'transfer_to', 'store_id',
        'picture_before', 'picture_after', 'document',
        'transaction_date','transfer_date', 'asset_status'
    ];

    /**
     * @param $id
     * @return AssetTransfers[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getAssetsTransfares($id){
        return AssetTransfers::where('asset_id', $id)->get();
    }


    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function Asset()
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }
}
