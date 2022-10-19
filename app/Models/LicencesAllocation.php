<?php

namespace App\Models;

use App\HRPerson;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicencesAllocation extends Model
{
    use Uuids;

    public $table = 'license_allocation';


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
        'name', 'name', 'user_id', 'licence_id',
        'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4',
        'division_level_5',
    ];

    public function Licenses(): BelongsTo
    {
        return $this->belongsTo(Licences::class, 'licence_id')->orderBy('id');
    }

    public function Hrpersons(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'user_id')->orderBy('id');
    }

    public static function getLicenceAllocation($id)
    {
        return LicencesAllocation::with(
            'Licenses', 'Hrpersons'
        )->where(
            'user_id',
            $id
        )->get();
    }


}
