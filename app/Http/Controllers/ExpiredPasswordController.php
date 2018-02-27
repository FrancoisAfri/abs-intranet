<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpiredPasswordController extends Controller
{
    public function expired()
    {
        return view('auth.passwords.expired');
    }
}
