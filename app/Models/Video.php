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


}
