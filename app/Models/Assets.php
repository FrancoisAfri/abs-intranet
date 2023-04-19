<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;

class Assets extends Model
{
    use Uuids;

    /**
     * @var string
     */
    public $table = 'assets';


    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];

    /**
     * @var string[]
     */
    protected $fillable = [
        'name', 'description', 'model_number', 'make_number', 'asset_type_id',
        'user_id', 'serial_number', 'asset_tag', 'type', 'license_type_id',
        'picture', 'price', 'status', 'asset_status', 'serial_number', ''
    ];


    /**
     * status constants
     */
    const STATUS_SELECT = [
        'In Use' => 'In Use',
        'Un Allocated' => 'Un Allocated',
        'Discarded' => 'Discarded',
        'Missing' => 'Missing',
        'In Store' => 'In Store',
        'Sold' => 'Sold',
    ];

    const ADMIN_STATUS_SELECT = [
        'Discarded' => 'Discarded',
        'Missing' => 'Missing',
        'Sold' => 'Sold',
    ];

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return BelongsTo
     */
    public function AssetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id')->orderBy('id');
    }

    public function HrPeople(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'user_id')->orderBy('id');
    }

    /**
     * @return BelongsTo
     */
    public function LicenseType(): BelongsTo
    {
        return $this->belongsTo(LicensesType::class, 'license_type_id')->orderBy('id');
    }

    /**
     *
     * @return Relation
     */
    public function store()
    {
        return $this->belongsTo(StoreRoom::class, 'store_id');
    }


    /**
     *
     * @return Relation
     */
    public function AssetTransfare(): Relation
    {
        return $this->hasMany(AssetTransfers::class, 'asset_id');
    }


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * @return Assets[]|\Illuminate\Database\Eloquent\Builder[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getAssetsTypes()
    {
        return Assets::with('AssetType')
            ->where('status', 1)
            ->get();
    }

    /**
     * @param $status
     * @return Assets[]|\Illuminate\Database\Eloquent\Builder[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getAssetsByStatus($status = 'In Use', $asset_type)
    {
        $query = Assets::with('AssetType')
            ->where([
                'asset_status' => $status
            ]);
        // return only from asset type table if  selection from asset type
        if ($asset_type > 0) {
            $query->where('asset_type_id', $asset_type);
        }

        return $query->get();
    }

    public static function getAllAssetsByStatus($status, $type)
    {
        $query = Assets::with('AssetType')
            ->orderBy('id', 'asc');

        if ($status !== 'all') {
            $query->where('asset_status', $status);
        }

        if ($type !== 'All') {
            $query->where('asset_type_id', $type);
        }

        return $query->get();

    }

    /**
     * @param string $uuid
     * @return Assets|Builder|Model
     */
    public static function findByUuid(string $uuid)
    {
        return Assets::with('AssetType')
            ->where(
                [
                    'uuid' => $uuid,
                ]
            )
            ->first();
    }


    /**
     * @param $id
     * @return Assets|Model|Builder|null
     */
    public static function getAssetById($id)
    {
        return (new Assets)->where('id', $id)->first();
    }
	
	public static function getAssets()
    {
        $query = Assets::with('AssetType')
            ->whereIn('asset_status', array('In Use', 'Un Allocated', 'In Store'));

        return $query->get();
    }

}
