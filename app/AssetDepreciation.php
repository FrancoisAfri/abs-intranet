<?php

namespace App;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class AssetDepreciation extends Model
{
    use Uuids;

    public $table = 'asset_depreciations';
    public $timestamps = true;

    protected $fillable = [
        'notes', 'user_id', 'months',
        'asset_id', 'years', 'amount_monthly', 'initial_amount'
		, 'balance_amount'
    ];


    public function AssetsList()
    {
        return $this->belongsTo(Assets::class, 'asset_id')->orderBy('id');
    }
	
	public function HrPeople(): BelongsTo
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }
	
	public static function getAssetsDepreciations($id)
    {
        return AssetDepreciation::with(
            [
                'AssetsList',
                'HrPeople'
            ]
        )
            ->where('asset_id', $id)
            ->OrderBy('id', 'asc')
            ->get();
    }
}
