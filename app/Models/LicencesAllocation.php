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
        'name', 'status', 'user_id', 'licence_id',
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
	// get all license allocation
	
	public static function getAlllicenses($status ,$type, $licenseID)
    {
        $query = LicencesAllocation::select('license_allocation.*')
			->leftJoin('asset_license', 'license_allocation.licence_id', '=', 'asset_license.id')
			->where(function ($q) use ($status) {
                if (!empty($status)) {
					if ($status == 1)
						$q->where('asset_license.status', '=', $status); // '=' is optional
					elseif ($status == 2)
						$q->where('asset_license.status', '=', 0);
						//die('dddd');//$q->whereNull('asset_license.status'); // '=' is optional
				}
            })
			->where(function ($q) use ($licenseID) {
                if (!empty($licenseID)) {
					$q->where('asset_license.id', '=', $licenseID); // '=' is optional
				}
            })
			->where(function ($q) use ($type) {
                if (!empty($type)) {
					$q->where('asset_license.asset_type_id', '=', $type); // '=' is optional
				}
            })
			->with('Hrpersons','Licenses.LicensesType')
            ->orderBy('license_allocation.licence_id', 'asc')
            ->orderBy('license_allocation.user_id', 'asc');
        return $query->get();
    }
}
