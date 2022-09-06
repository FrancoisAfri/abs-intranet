<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

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

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

}
