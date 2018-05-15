<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Users;
use App\permits_licence;
use App\servicetype;
use App\HRPerson;
use App\vehicle;
use App\vehicle_config;
use App\jobcard_order_parts;
use App\jobcart_parts;
use App\AuditTrail;
use App\jobcard_category_parts;
use App\jobcard_maintanance;
use App\ContactCompany;
use App\processflow;
use App\jobcardnote;
use App\jobcards_config;
use App\CompanyIdentity;
use App\module_access;
use App\module_ribbons;
use App\modules;
use App\Mail\NextjobstepNotification;
use App\Mail\DeclinejobstepNotification;
use Illuminate\Http\Request;
use App\Mail\confirm_collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
class StockController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function mystock(){
        
    }
    
}
