<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class LicencesDates extends Model
{
    use Uuids;

    public $table = 'asset_license_dates';


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
        'purchase_date', 'expiration_date', 'renewal_date',
        'license_id', 'user_id', 'status',
    ];


}
