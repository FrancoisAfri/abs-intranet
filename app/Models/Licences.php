<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Licences extends Model
{
    use Uuids;

    /**
     * @var string
     */
    public $table = 'asset_license';


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
        'name', 'details', 'serial', 'purchase_date', 'purchase_cost',
        'order_number', 'total', 'notes', 'user_id', 'asset_type_id',
        'expiration_date','status', 'licence_status'
    ];


    public function LicensesType(): BelongsTo
    {
        return $this->belongsTo(LicensesType::class, 'asset_type_id')->orderBy('id');
    }

    /**
     * @param string $uuid
     * @return Licences|Model|Builder|null
     */
    public static function findByUuid(string $uuid)
    {
        return Licences::with('LicensesType')
            ->where(
                [
                    'uuid' => $uuid,
                ]
            )
            ->first();
    }

    public static function getLicencesByStatus($status){

        $query = Licences::with('LicensesType');

        if ($status !== 'All' && $status !== null){

            $query->where('status', $status);
        }

        return $query->get();
    }
}
