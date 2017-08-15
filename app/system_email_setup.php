<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class system_email_setup extends Model
{

	 public $table = 'system_email_setup';
    //chema::dropIfExists('system');
     protected $fillable = [ 'responder_messages','response_emails','ticket_completion_req','ticket_completed' ,
           					'auto_processemails','anly_processreplies','email_address' , 'server_name','preferred_communication_method','server_port', 'username' , 'password' ,'Signature_start'  ];

        
           
}
