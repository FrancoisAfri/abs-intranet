<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ceoNews extends Model
{
  public $table = 'ceo_news';

    //Mass assignable fields
    protected $fillable = [
        'name', 'description', 'date','supporting_docs', 'summary', 'image','user_id','status'
    ];
}
