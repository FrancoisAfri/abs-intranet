<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteConfiguration extends Model
{
    //Specify the table name
    public $table = 'quote_configurations';

    // Mass assignable fields
    protected $fillable = [
        'allow_client_email_quote'];
}
