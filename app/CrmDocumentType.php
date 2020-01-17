<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrmDocumentType extends Model
{
    protected $table = 'crm_document_types';

    protected $fillable = ['name', 'description', 'status'];
}
