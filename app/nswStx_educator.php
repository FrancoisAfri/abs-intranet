<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class nswStx_educator extends Model
{
    protected $table = 'nsw_stx_educators';
    
    //Mass assignable fields
    protected $fillable = [
        'educators_number', 'educator_id_number', 'educator_first_name', 'educator_surname', 'nsw_id'
    ];
    public function nswEducators() {
        return $this->belongsTo(nswStx::class, 'nsw_id');
    }
}
