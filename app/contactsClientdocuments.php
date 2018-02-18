<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class contactsClientdocuments extends Model
{
    //Specify the table name
    public $table = 'client_documents';

    // Mass assignable fields
    protected $fillable = ['name', 'description', 'date_from', 'expirydate', 'supporting_docs', 'client_id', 'status','document_name'];
}
