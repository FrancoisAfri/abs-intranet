<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class helpdesk_Admin extends Model
{
      public $table = 'helpdesk_Admin';

    protected $fillable = ['helpdesk_id','admin_id', 'status'];

    public function ticket() {
        return $this->hasMany(ticket::class, 'admin_id');
    }
}
