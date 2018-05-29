<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jobcardnote extends Model
{
    protected $table = 'jobcard_notes';

    protected $fillable = ['vehicle_id', 'jobcard_id', 'note_details', 'user_id', 'date_default'];
}
