<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doc_type extends Model
{
    //
      protected $table = 'doc_type';

    protected $fillable = ['name','description','active' ];

    public function docType() {
        return $this->belongsTo(doctype_category::class, 'category_id');
    }
}
  
      


