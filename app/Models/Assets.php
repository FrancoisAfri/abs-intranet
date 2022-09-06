<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assets extends Model
{

    public $table = 'assets';

    protected $fillable = [
        'name', 'description', 'model_number', 'make_number','asset_type_id',
        'user_id', 'serial_number', 'asset_tag', 'type','license_type_id',
        'picture', 'price', 'status','asset_status','serial_number'
    ];


    /**
     * status constants
     */
    const STATUS_SELECT = [
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

}
