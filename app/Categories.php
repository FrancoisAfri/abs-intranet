<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    //
    protected $table = 'Categories';

    protected $fillable = ['name','description','active' ];

    public function doctypeCategory() {
        return $this->hasMany(doc_type::class, 'category_id');
    }

   // add a function to add a document type from the relationship
     public function addDocumenttype(doc_type $documentype) {
            return $this->doctypeCategory()->save($documentype);
    }

}


