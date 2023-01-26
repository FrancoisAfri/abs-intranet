<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{

    use Uuids;

    public $table = 'videos';

    protected $fillable = [
        'name', 'description', 'path', 'video_type', 'status'
    ];


    public static function getVideoType()
    {

        return $videoTypes = [
            'General' => 'General',
            'Specific_dept' => 'Specific_dept'
        ];
    }

    public static function getNameByUuid($id)
    {
        return Video::where('uuid', $id)->first();
    }

    public static function fhghf($person, $assetType, $location)
    {

        $query = AssetTransfers::with(
            'AssetTransfers',
            'AssetImages',
            'HrPeople',
            'store')
            ->orderBy('id', 'asc');
        if ($person !== 'all') {
            $query->where('user_id', $person);
        }

        if ($assetType !== 'All') {
            $query->where('asset_id', $assetType);
        }

        if ($location !== 's_all') {
            $query->where('store_id', $location);
        }

        return $query->get();
    }


    public static function getAllGeneralVideos()
    {
        return Video::where(
            [
                'status' => 1,
                'video_type' => 1,

            ]
        )->get();


    }

    public static function getVideosByUser($division_level_1, $division_level_2, $division_level_3, $division_level_4, $division_level_5)
    {
       
	   return Video::where('status',1)
				   ->where(function ($query)  use ($division_level_3,$division_level_4,$division_level_5,$division_level_2,$division_level_1)  {
						$query->where('division_level_3', '=', $division_level_3)
							  ->orWhere('division_level_1', '=', $division_level_1)
							  ->orWhere('division_level_2', '=', $division_level_2)
							  ->orWhere('division_level_4', '=', $division_level_4)
							  ->orWhere('division_level_5', '=', $division_level_5);
					})
					->get();
    }
}
