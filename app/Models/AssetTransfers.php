<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class AssetTransfers extends Model
{
    use Uuids;

    public $table = 'asset_transfer';

    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'user_id',
        'asset_id', 'transfer_to', 'store_id',
        'picture_before', 'picture_after', 'document', 'asset_image_transfer_id',
        'transaction_date', 'transfer_date', 'asset_status', 'current_value'
    ];


    /**
     *
     * @return Relation
     */
    public function AssetTransfers(): Relation
    {
        return $this->belongsTo(Assets::class, 'asset_id');
    }

    /**
     *
     * @return Relation
     */
    public function AssetImages(): Relation
    {
        return $this->belongsTo(AssetImagesTransfers::class, 'asset_image_transfer_id');
    }

    /**
     * @return BelongsTo
     */
    public function HrPeople(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }

    public function store()
    {
        return $this->belongsTo(StoreRoom::class, 'store_id');
    }

    /**
     * @param $id
     * @return AssetTransfers[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getAssetsTransfares($id)
    {
        return AssetTransfers::with(
            [
                'AssetTransfers',
                'AssetImages',
                'HrPeople',
                'store'
            ]
        )
            ->where('asset_id', $id)
            ->OrderBy('id', 'asc')
            ->get();
    }


    public static function getAssetLocation($person, $assetType, $location)
    {
        $query = $assetTransfer = AssetTransfers::with(
            'AssetTransfers',
            'AssetImages',
            'HrPeople',
            'store')
            ->orderBy('id', 'asc');
        if ($person !== 'all') {
            $query->where('user_id', $person);
        }

        if ($assetType !== 'All') {
            $query->where('asset_id', $assetType);
        }

        if ($location !== 's_all') {
            $query->where('store_id', $location);
        }

        return $query->get();
    }


    public static function getAssetTransfer($person, $assetType, $location)
    {
        $query = $assetTransfer = AssetTransfers::with(
            'AssetTransfers',
            'AssetImages',
            'HrPeople',
            'store')
            ->orderBy('id', 'asc');
        if ($person !== 'all') {
            $query->where('user_id', $person);
        }

        if ($assetType !== 'All') {
            $query->where('asset_id', $assetType);
        }

        if ($location !== 's_all') {
            $query->where('store_id', $location);
        }

        return $query->get();
    }

    /**
     * @param string $uuid
     * @return Assets|Builder|Model
     */
    public static function findByUuid(string $uuid)
    {
        return AssetTransfers::with([
            'AssetTransfers',
            'AssetImages'
        ])
            ->where(
                [
                    'uuid' => $uuid,
                ]
            )
            ->first();
    }

    public static function getAssetByUser($user)
    {
        return AssetTransfers::with(
            'AssetTransfers'
        )->where([
                'user_id' => $user,
                'current_value' => 1
            ]
        )->get();
    }

}
