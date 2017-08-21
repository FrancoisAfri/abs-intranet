<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HelpDesk extends Model
{
   public $table = 'help_desk';
    //chema::dropIfExists('system');
     protected $fillable = [ 'name','description','status'];

     public function ticket() {
        return $this->hasMany(ticket::class, 'helpdesk_id');
    }
}
