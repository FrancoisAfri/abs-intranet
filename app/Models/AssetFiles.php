<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class AssetFiles extends Model
{
    use Uuids;

    public $table = 'asset_files';

    public $timestamps = true;

    protected $fillable = [
        'name', 'description', 'user_id',
        'asset_id', 'picture', 'document',
        'status', 'date_added'
    ];


    /**
     * @return BelongsTo
     */
    public function AssetFiles(): BelongsTo
    {
        return $this->belongsTo(Assets::class, 'asset_id')->orderBy('id');
    }

    /**
     * @param $id
     * @return AssetFiles[]|Collection|Builder[]|\Illuminate\Support\Collection
     */
    public static function getAllFiles($id){
      return AssetFiles::where('asset_id', $id)->get();
    }

}
