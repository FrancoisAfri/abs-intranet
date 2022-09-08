<?php

namespace App\Models;

use App\Traits\Uuids;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Assets extends Model
{
    use Uuids;

    public $table = 'assets';

    protected $hidden = [
        'id'
    ];

    protected $fillable = [
        'name', 'description', 'model_number', 'make_number', 'asset_type_id',
        'user_id', 'serial_number', 'asset_tag', 'type', 'license_type_id',
        'picture', 'price', 'status', 'asset_status', 'serial_number',''
    ];


    /**
     * status constants
     */
    const STATUS_SELECT = [
        'Un Allocated' => 'Un Allocated',
        'In Use' => 'In Use',
        'Discarded' => 'Discarded',
        'Missing' => 'Missing',
        'In Store' => 'In Store',
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

    /**
     * @return BelongsTo
     */
    public function LicenseType(): BelongsTo
    {
        return $this->belongsTo(LicensesType::class, 'license_type_id')->orderBy('id');
    }

    /**
     * Establishes the asset -> location relationship
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v2.0]
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function store()
    {
        return $this->belongsTo(StoreRoom::class, 'store_id');
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
     * @param string $uuid
     * @return Assets|Builder|Model
     */
    public static function findByUuid(string $uuid)
    {
        return (new Assets)->where('uuid', $uuid)->first();
    }
}
