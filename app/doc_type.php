<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doc_type extends Model
{
    //
      protected $table = 'doc_type';

    protected $fillable = ['name','description','active' ];

    public function docType() {
        return $this->belongsTo(doc_type_category::class, 'category_id');
    }
}
  
      


