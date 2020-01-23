<?php

namespace App\Http\Controllers;

use App\Cmsnews;
use App\ceoNews;
use App\HRPerson;
use App\User;
use App\cms_rating;
use App\DivisionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Return_;

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

        $Expdate = str_replace('/', '-', $NewsData['exp_date']);
        $Expdate =  strtotime($Expdate);

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
                $fileName = time() . "image." . $fileExt;
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
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('CMS/images', $fileName);
                //Update file name in the database
                $news->image = $fileName;
                $news->update();
            }
        }

        AuditReportsController::store('Content Management', 'Company News Updated', "Company News Content Management Accessed", 0);
        return redirect('/cms/viewnews/' . $news->id)->with('success_application', "Content Update successfully.");

    }

    public function addCeonews()
    {

        $Ceo_news = ceoNews::all();

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


        $crmNews = new ceoNews();
        $crmNews->name = $NewsData['name'];
        $crmNews->description = $NewsData['description'];
        $crmNews->summary = html_entity_decode($NewsData['term_name']);
        $crmNews->date = time();
        $crmNews->status = 1;
        $crmNews->save();
		//Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('CMS/images', $fileName);
                //Update file name in the database
                $crmNews->image = $fileName;
                $crmNews->update();
            }
        }


        AuditReportsController::store('Content Management', 'Company Ceo News Added', "Company News Content Management Accessed", 0);
        return response()->json();
    }

    public function ceonewsAct(ceoNews $news)
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

    public function deleteCeoNews(ceoNews $news)
    {

        $news->delete();

        AuditReportsController::store('Content Management', 'Content Ceo News  Deleted', "Content Ceo News Deleted", 0);
        return back();
    }

    public function editCeoNews(ceoNews $news)
    {

        // return $news;
        $Cmsnews = ceoNews::where('id', $news->id)->first();

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

    public function updatCeonewsContent(Request $request, ceoNews $news)
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
		//Upload Image picture
        if ($request->hasFile('image')) {
            $fileExt = $request->file('image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('image')->isValid()) {
                $fileName = time() . "image." . $fileExt;
                $request->file('image')->storeAs('CMS/images', $fileName);
                //Update file name in the database
                $news->image = $fileName;
                $news->update();
            }
        }
        AuditReportsController::store('Content Management', 'Company News Content  Updated', "Company News Content  Updated", 0);
        return back()->with('success_application', "Content Update successfully.");

    }

    public function view(Cmsnews $id)
    {
        $newsID = $id->id;
        $Cmsnews = Cmsnews::where('id', $newsID)->first();

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

    public function viewceo(ceoNews $viewceo)
    {
        $newsID = $viewceo->id;
        $Cmsnews = ceoNews::where('id', $newsID)->first();

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

    public function search()
    {
        $data['page_title'] = "CMS ";
        $data['page_description'] = "Search News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Search News', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Search';


        AuditReportsController::store('Content Management', 'Company Search Accessed', "Company Search Accessed", 0);

        return view('cms.search_news')->with($data);

    }

    public function cmsceonews(Request $request)
    {

        $policyData = $request->all();
        unset($policyData['_token']);


        $actionFrom = $actionTo = 0;
        $name = $policyData['name'];
        $actionDate = $policyData['day'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }
        $ceo_news = ceoNews::where(function ($query) use ($actionFrom, $actionTo) {
            if ($actionFrom > 0 && $actionTo > 0) {
                $query->whereBetween('ceo_news.date', [$actionFrom, $actionTo]);
            }
        })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('ceo_news.name', 'ILIKE', "%$name%");
                }
            })
            ->orderBy('ceo_news.name')
            ->get();

        //  return $ceo_news;

        $data['ceo_news'] = $ceo_news;

        $data['page_title'] = "CMS ";
        $data['page_description'] = "CEO Message ";
        $data['breadcrumb'] = [
            ['title' => 'CMS Search News', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Content Management', 'Company Ceo Messages Accessed', "Company Accessed", 0);
        return view('cms.ceonews_results')->with($data);


    }

    public function CamponyNews(Request $request)
    {
        $policyData = $request->all();
        unset($policyData['_token']);

        $actionFrom = $actionTo = 0;
        $name = $policyData['name'];
        $actionDate = $policyData['day'];
        if (!empty($actionDate)) {
            $startExplode = explode('-', $actionDate);
            $actionFrom = strtotime($startExplode[0]);
            $actionTo = strtotime($startExplode[1]);
        }

        $Cmsnews = DB::table('cms_news')
            ->select('cms_news.*')
            ->where(function ($query) use ($actionFrom, $actionTo) {
                if ($actionFrom > 0 && $actionTo > 0) {
                    $query->whereBetween('cms_news.expirydate', [$actionFrom, $actionTo]);
                }
            })
            ->where(function ($query) use ($name) {
                if (!empty($name)) {
                    $query->where('cms_news.name', 'ILIKE', "%$name%");
                }
            })
            ->limit(100)
            ->orderBy('cms_news.id')
            ->get();

        $data['Cmsnews'] = $Cmsnews;
        $data['page_title'] = "CMS";
        $data['page_description'] = "Campony News";
        $data['breadcrumb'] = [
            ['title' => 'CMS Search News', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Search';

        AuditReportsController::store('Content Management', 'Company News search page Accessed', "Company search page Accessed", 0);
        return view('cms.camponynews_results')->with($data);

    }

    public function cmsratings($id, $cmsID)
    {

        $cms_news_rating = cms_rating::where('cms_id', $cmsID)->first();
        // return  $loggedInEmplID = Auth::user()->person->id;

        if (empty($cms_news_rating)) {
            $cms_news_rating = new cms_rating();
            $cms_news_rating->rating_1 = 0;
            $cms_news_rating->rating_2 = 0;
            $cms_news_rating->rating_3 = 0;
            $cms_news_rating->rating_4 = 0;
            $cms_news_rating->rating_5 = 0;
            $cms_news_rating->cms_id = $cmsID;
            $cms_news_rating->user_id = $loggedInEmplID = Auth::user()->person->id;
            $cms_news_rating->save();
        }

        if ($id == 1) {
            $cms_news_rating->rating_1 = 1;
            $cms_news_rating->rating_2 = 0;
            $cms_news_rating->rating_3 = 0;
            $cms_news_rating->rating_4 = 0;
            $cms_news_rating->rating_5 = 0;
        } elseif ($id == 2) {
            $cms_news_rating->rating_1 = 1;
            $cms_news_rating->rating_2 = 1;
            $cms_news_rating->rating_3 = 0;
            $cms_news_rating->rating_4 = 0;
            $cms_news_rating->rating_5 = 0;
        } elseif ($id == 3) {
            $cms_news_rating->rating_1 = 1;
            $cms_news_rating->rating_2 = 1;
            $cms_news_rating->rating_3 = 1;
            $cms_news_rating->rating_4 = 0;
            $cms_news_rating->rating_5 = 0;
        } elseif ($id == 4) {
            echo $cms_news_rating->rating_1;
            // die;
            $cms_news_rating->rating_1 = 1;
            $cms_news_rating->rating_2 = 1;
            $cms_news_rating->rating_3 = 1;
            $cms_news_rating->rating_4 = 1;
            $cms_news_rating->rating_5 = 0;
        } elseif ($id == 5) {
            $cms_news_rating->rating_1 = 1;
            $cms_news_rating->rating_2 = 1;
            $cms_news_rating->rating_3 = 1;
            $cms_news_rating->rating_4 = 1;
            $cms_news_rating->rating_5 = 1;
        }

        $cms_news_rating->update();

        AuditReportsController::store('Content Management', 'Company News Ratings', "Company News Ratings", 0);

        return back();
    }

    public function cms_report()
    {
        $Cmsnews = Cmsnews::all();
        // return $Cmsnews;

        $data['Cmsnews'] = $Cmsnews;
        $data['page_title'] = "CMS";
        $data['page_description'] = "Campony News";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Content Management', 'Company News search page Accessed', "Company search page Accessed", 0);
        return view('cms.reports.search_results')->with($data);
    }

    public function cms_rankings()
    {

        $Cmsnews = Cmsnews::all();
        // return $Cmsnews;

        $data['Cmsnews'] = $Cmsnews;
        $data['page_title'] = "CMS";
        $data['page_description'] = "Campony News";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Content Management', 'Company News search page Accessed', "Company search page Accessed", 0);
        return view('cms.reports.search_results')->with($data);
    }

    public function cms_Star_rankings(Request $request, Cmsnews $news)
    {
        $ID = $news->id;

        $ratings = cms_rating::select('cms_news_ratings.*', 'hr_people.id', 'hr_people.first_name', 'hr_people.surname')
            ->join('hr_people', 'cms_news_ratings.user_id', '=', 'hr_people.id')
            ->where('cms_id', $ID)
            ->get();
        // return $ratings;

        $data['news'] = $news;
        $data['ratings'] = $ratings;
        $data['page_title'] = "CMS";
        $data['page_description'] = "Campony News";
        $data['breadcrumb'] = [
            ['title' => 'Reports', 'path' => '/News', 'icon' => 'fa fa-spinner', 'active' => 0, 'is_module' => 1],
            ['title' => 'Content Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Content Management';
        $data['active_rib'] = 'Reports';

        AuditReportsController::store('Content Management', 'Company News search page Accessed', "Company search page Accessed", 0);
        return view('cms.partials.new_ratings')->with($data);
    }
	// general informations
	public function generalInformations()
    {
        $data['page_title'] = "General Info";
        $data['page_description'] = "General Informations";
        $data['breadcrumb'] = [
            ['title' => 'General ', 'path' => '/general_information/view', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'General Info', 'active' => 1, 'is_module' => 0]
        ];

        AuditReportsController::store('Content Management', 'Company News Added', "Company News Content Management Accessed", 0);
        return view('cms.view_general_info')->with($data);
    }
}
