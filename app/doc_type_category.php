<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class doc_type_category extends Model
{
    //
    //
    protected $table = 'doc_type_category';

    protected $fillable = ['name', 'description', 'active'];


    public function doctypeCategory()
    {
        return $this->hasmany(doc_type::class, 'category_id');
    }

    //add a function to add a document type from the relationship
    public function update($doctypeCategory)
    {
        return $this->doctypeCategory()->save($doctypeCategory);
    }
}
