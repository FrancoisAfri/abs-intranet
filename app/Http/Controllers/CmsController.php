<?php

namespace App\Http\Controllers;

use App\Cmsnews;
use App\ceoNews;
use App\HRPerson;
use App\User;
use App\ContactPerson;
use App\DivisionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function addnews()
    {

        $Cmsnews = Cmsnews::all();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();

        $data['page_title'] = "CMS";
        $data['page_description'] = "CMS Settings";
        $data['breadcrumb'] = [
            ['title' => 'CMS', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Add Company News';
        $data['Cmsnews'] = $Cmsnews;
        $data['division_levels'] = $divisionLevels;
        AuditReportsController::store('CRM', 'CRM Setup Page Accessed', "Accessed By User", 0);


        AuditReportsController::store('Content Management', 'Company News Added', "Company News Content Management Accessed", 0);
        return view('cms.viewcrmnews')->with($data);
    }

    public function addcmsnews(Request $request)
    {
        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required',

        ]);
        $NewsData = $request->all();
        unset($NewsData['_token']);

        $Expdate = $NewsData['exp_date'] = str_replace('/', '-', $NewsData['exp_date']);
        $Expdate = $NewsData['exp_date'] = strtotime($NewsData['exp_date']);

        $crmNews = new Cmsnews();
        $crmNews->name = $NewsData['name'];
        $crmNews->description = $NewsData['description'];
        $crmNews->summary = html_entity_decode($NewsData['term_name']);
        $crmNews->division_level_1 = !empty($NewsData['division_level_1']) ? $NewsData['division_level_1'] : 0;
        $crmNews->division_level_2 = !empty($NewsData['division_level_2']) ? $NewsData['division_level_2'] : 0;
        $crmNews->division_level_3 = !empty($NewsData['division_level_3']) ? $NewsData['division_level_3'] : 0;
        $crmNews->division_level_4 = !empty($NewsData['division_level_4']) ? $NewsData['division_level_4'] : 0;
        $crmNews->division_level_5 = !empty($NewsData['division_level_5']) ? $NewsData['division_level_5'] : 0;
        $crmNews->expirydate = $Expdate;
        $crmNews->status = 1;
        $crmNews->save();

        //Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = $crmNews->id . "image." . $fileExt;
                $request->file('image')->storeAs('CMS/images', $fileName);
                //Update file name in the database
                $crmNews->image = $fileName;
                $crmNews->update();
            }
        }

        AuditReportsController::store('Content Management', 'Company News Added', "Company News Content Management Accessed", 0);
        return response()->json();
    }

    public function viewnews(Cmsnews $news)
    {

        // return $news;
        $hrDetails = HRPerson::where('status', 1)->get();
        $Cmsnews = Cmsnews::where('id', $news->id)->first();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();


        $data['page_title'] = "CMS";
        $data['page_description'] = "CMS Settings";
        $data['breadcrumb'] = [
            ['title' => 'CMS', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $avatar = $Cmsnews->image;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("CMS/images/$avatar") : '';
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Add Company News';
        $data['Cmsnews'] = $Cmsnews;
        $data['division_levels'] = $divisionLevels;
        $data['hrDetails'] = $hrDetails;
        AuditReportsController::store('CRM', 'CRM Setup Page Accessed', "Accessed By User", 0);


        AuditReportsController::store('Content Management', 'Company News Added', "Company News Content Management Accessed", 0);
        return view('cms.edit_crm_news')->with($data);
    }

    public function newsAct(Cmsnews $news)
    {
        if ($news->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $news->status = $stastus;
        $news->update();

        AuditReportsController::store('Content Management', 'Company News Status Changed', "Company News Status  Changed", 0);
        return back();
    }

    public function deleteNews(Cmsnews $news)
    {

        $news->delete();

        AuditReportsController::store('Content Management', 'Content News  Deleted', "Content News Deleted", 0);
        return back();
    }

    public function updatContent(Request $request, Cmsnews $news)
    {

        $this->validate($request, [

        ]);
        $NewsData = $request->all();
        unset($NewsData['_token']);


        $Expdate = $NewsData['exp_date'] = str_replace('/', '-', $NewsData['exp_date']);
        $Expdate = $NewsData['exp_date'] = strtotime($NewsData['exp_date']);

        $news->name = $NewsData['name'];
        $news->description = $NewsData['description'];
        $news->summary = html_entity_decode($NewsData['summary']);
        $news->division_level_1 = !empty($NewsData['division_level_1']) ? $NewsData['division_level_1'] : 0;
        $news->division_level_2 = !empty($NewsData['division_level_2']) ? $NewsData['division_level_2'] : 0;
        $news->division_level_3 = !empty($NewsData['division_level_3']) ? $NewsData['division_level_3'] : 0;
        $news->division_level_4 = !empty($NewsData['division_level_4']) ? $NewsData['division_level_4'] : 0;
        $news->division_level_5 = !empty($NewsData['division_level_5']) ? $NewsData['division_level_5'] : 0;
        $news->expirydate = $Expdate;
        $news->update();

        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = $news->id . "image." . $fileExt;
                $request->file('image')->storeAs('CMS/images', $fileName);
                //Update file name in the database
                $news->image = $fileName;
                $news->update();
            }
        }

        AuditReportsController::store('Content Management', 'Company News Updated', "Company News Content Management Accessed", 0);
        return back()->with('success_application', "Content Update successfully.");

    }

    public function addCeonews()
    {

        $Ceo_news = ceo_news::all();

        $data['page_title'] = "CMS ";
        $data['page_description'] = "Ceo News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Ceo News', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'CEO News';
        $data['Ceo_news'] = $Ceo_news;

        AuditReportsController::store('Content Management', 'Company Ceo News Accessed', "Company News Content Management Accessed", 0);
        return view('cms.viewceonews')->with($data);
    }

    public function addcmsceonews(Request $request)
    {
        $this->validate($request, [
//            'name' => 'required',
//            'description' => 'required',

        ]);
        $NewsData = $request->all();
        unset($NewsData['_token']);


        $crmNews = new ceo_news();
        $crmNews->name = $NewsData['name'];
        $crmNews->description = $NewsData['description'];
        $crmNews->summary = html_entity_decode($NewsData['term_name']);
        $crmNews->date = time();
        $crmNews->status = 1;
        $crmNews->save();


        AuditReportsController::store('Content Management', 'Company Ceo News Added', "Company News Content Management Accessed", 0);
        return response()->json();
    }

    public function ceonewsAct(ceo_news $news)
    {
        if ($news->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $news->status = $stastus;
        $news->update();

        AuditReportsController::store('Content Management', 'Company Ceo News Status Changed', "Company Ceo News Status  Changed", 0);
        return back();
    }

    public function deleteCeoNews(ceo_news $news)
    {

        $news->delete();

        AuditReportsController::store('Content Management', 'Content Ceo News  Deleted', "Content Ceo News Deleted", 0);
        return back();
    }

    public function editCeoNews(ceo_news $news)
    {

        // return $news;
        $Cmsnews = ceo_news::where('id', $news->id)->first();

        $data['page_title'] = "CMS ";
        $data['page_description'] = "Ceo News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Ceo News', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'CEO News';
        $data['Cmsnews'] = $Cmsnews;

        AuditReportsController::store('Content Management', 'Company Ceo News Accessed', "Company Ceo News Content  Accessed", 0);
        return view('cms.edit_ceo_news')->with($data);
    }

    public function updatCeonewsContent(Request $request, ceo_news $news)
    {

        $this->validate($request, [

        ]);
        $NewsData = $request->all();
        unset($NewsData['_token']);

        $news->name = $NewsData['name'];
        $news->description = $NewsData['description'];
        $news->summary = html_entity_decode($NewsData['summary']);
        $news->date = time();
        $news->update();

        AuditReportsController::store('Contacts', 'Company News Content  Updated', "Company News Content  Updated", 0);
        return back()->with('success_application', "Content Update successfully.");

    }

    public function view(Cmsnews $id)
    {
        $newsID = $id->id;
        $Cmsnews = Cmsnews::where('id', $newsID)->first();
//        return $Cmsnews;


        $data['page_title'] = "CMS ";
        $data['page_description'] = "Company News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Ceo News', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'CEO News';
        $data['Cmsnews'] = $Cmsnews;

        AuditReportsController::store('Content Management', 'Company Ceo News Accessed', "Company Ceo News Content  Accessed", 0);
        return view('dashboard.view_news_dashboard')->with($data);
    }

    public function viewceo(ceo_news $viewceo)
    {
        $newsID = $viewceo->id;
        $Cmsnews = ceo_news::where('id', $newsID)->first();
//        return $Cmsnews;


        $data['page_title'] = "CMS ";
        $data['page_description'] = "Company News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Ceo News', 'path' => '/News', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'CEO News';
        $data['Cmsnews'] = $Cmsnews;

        AuditReportsController::store('Content Management', 'Company Ceo News Accessed', "Company Ceo News Content  Accessed", 0);
        return view('cms.view_ceonews_dashboard')->with($data);
    }

}