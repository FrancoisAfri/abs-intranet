<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cms_rating extends Model
{
    public $table = 'cms_news_ratings';

    //Mass assignable fields
    protected $fillable = ['rating_1', 'rating_2', 'rating_3', 'rating_4', 'rating_5', 'user_id', 'cms_id'];

    public function cmsNews()
    {
        return $this->belongsTo(Cmsnews::class, 'cms_id');
    }

    public function ratingUsers()
    {
        return $this->belongsTo(HRPerson::class, 'user_id');
    }



}
