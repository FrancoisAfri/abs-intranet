<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DmsSetup extends Model
{
     //Specify the table name
    public $table = 'dms_setups';

    //Mass assignable fields
    protected $fillable = [
        'use_remote_server', 'root_directory', 'default_image'
		, 'use_remote_ftp_url', 'use_remote_ftp_username'
		,'use_remote_ftp_password'
    ];
}
