<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ticket extends Model
{
   
    public $table = 'ticket';

    protected $fillable = ['name','email','subject','message','admin_id','status','ticket_date'];

    //ticket - helpdesk relationship

     public function helpdesk(){
    	# code...
    	 return $this->belongsTo(HelpDesk::class, 'helpdesk_id');
    }

     public function helpdeskAdmin(){
    	# code...
    	 return $this->belongsTo(helpdesk_Admin::class, 'admin_id');
    }

     public function hrPeople() {
        return $this->hasMany(HRPerson::class, 'id');
    }
}
