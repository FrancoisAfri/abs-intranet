<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AppraisalPerk extends Model
{
    //Specify the table name
    public $table = 'appraisal_perks';

    // Mass assignable fields
    protected $fillable = [
        'name', 'description', 'req_percent'
    ];

    //perk image link accessor
    public function getImgUrlAttribute() {
        return (!empty($this->img)) ? Storage::disk('local')->url("perks/$this->img") : 'http://placehold.it/128x128';
    }
}
