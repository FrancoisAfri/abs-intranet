<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cmsnews extends Model
{
    //Specify the table name
    public $table = 'cms_news';

    //Mass assignable fields
    protected $fillable = [
        'name', 'description', 'expirydate', 'supporting_docs', 'summary', 'image', 'user_id', 'division_level_1', 'division_level_2', 'division_level_3', 'division_level_4', 'division_level_5', 'status'
    ];

    public function cmsRankings()
    {
        return $this->hasMany(cms_rating::class, 'cms_id');
    }
}
