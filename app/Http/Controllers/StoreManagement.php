<?php

namespace App\Http\Controllers;
use App\CompanyIdentity;
use App\HRPerson;
use App\Http\Requests;
use App\Mail\confirm_collection;
use App\stock;
use App\stockhistory;
use App\StockSettings;
use App\Users;
use App\stockLevel;
use App\stockLevelFive;
use App\stockLevelFour;
use App\stockLevelOne;
use App\stockLevelThree;
use App\stockLevelTwo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StoreManagement extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}
	
	public function showSetup()
    {
        $stock_types = DB::table('stock_levels')->orderBy('level', 'desc')->get();
		$stockSettings = StockSettings::orderBy('id', 'desc')->first();

        $data['stock_types'] = $stock_types;
        $data['stockSettings'] = $stockSettings;
        $data['page_title'] = "Stock Management";
        $data['page_description'] = " Stock Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => 'stock/setup', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Setup';

        AuditReportsController::store('Stock Management', 'view Stock Setup Page', "Accessed By User", 0);
        return view('stock.stock_setup')->with($data);
    }
	public function addSettings(Request $request,StockSettings $settings)
    {
         $this->validate($request, [
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);

        $settings->unit_of_measurement = $request->input('unit_of_measurement');
        $settings->save();
        return back();

        AuditReportsController::store('Stock Management', 'Add Stock Settinfs', "Added By User", 0);
    }
	
	public function approvalSettings(Request $request,StockSettings $settings)
    {
         $this->validate($request, [
        ]);

        $SysData = $request->all();
        unset($SysData['_token']);
		
        $settings->require_managers_approval = $request->input('require_managers_approval');
        $settings->require_store_manager_approval = $request->input('require_store_manager_approval');
        $settings->require_department_head_approval = $request->input('require_department_head_approval');
        $settings->require_ceo_approval = $request->input('require_ceo_approval');
        $settings->save();

		return back();

        AuditReportsController::store('Stock Management', 'Add Stock Settinfs', "Added By User", 0);
    }
	
	public function viewLevel()
    {
        $stock_types = DB::table('stock_levels')->orderBy('level', 'desc')->get();
		$employees = HRPerson::where('status', 1)->get();
        $highestLvl = stockLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->load('stockLevelGroup.stockManager');
        $lowestactiveLvl = stockLevel::where('active', 1)->orderBy('level', 'asc')->limit(1)->get()->first()->level;
        if ($highestLvl->level > $lowestactiveLvl) {
           $childLevelname = stockLevel::where('level', $highestLvl->level - 1)->get()->first()->plural_name;
        }
        $data['employees'] = $employees;
        $data['stock_types'] = $stock_types;
        $data['highestLvl'] = $highestLvl;
        $data['lowestactiveLvl'] = $lowestactiveLvl;
        $data['childLevelname'] = $childLevelname;
        $data['page_title'] = "Store Management";
        $data['page_description'] = "Store Management";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => '/stock/store_management', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Store Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Store Management';

        AuditReportsController::store('Stock Management', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('stock.stock_store_management')->with($data);
    }

    public function addLevel(Request $request, stockLevel $stockLevel)
    {
        $this->validate($request, [
            'manager_id' => 'required',
            'name' => 'required',
        ]);
        $firstLevelData = $request->all();

        if ($stockLevel->level == 5) {
            $childStock = new stockLevelFive($firstLevelData);
            $childStock->division_level_id = 5;
			$childStock->active = 1;
        } elseif ($stockLevel->level == 4) {
            $childStock = new stockLevelFour($firstLevelData);
            $childStock->division_level_id = 4;
        } elseif ($stockLevel->level == 3) {
            $childStock = new stockLevelThree($firstLevelData);
            $childStock->division_level_id = 3;
        } elseif ($stockLevel->level == 2) {
            $childStock = new stockLevelTwo($firstLevelData);
            $childStock->division_level_id = 2;
        } elseif ($stockLevel->level == 1) {
            $childStock = new stockLevelOne($firstLevelData);
            $childStock->division_level_id = 1;
        }
        $childStock->active = 1;
        $stockLevel->addStockLevelGroup($childStock);

        AuditReportsController::store('Stock Management', 'Employee Group Level Modified', "Actioned By User", 0);
    }

    public function activateLevel(stockLevel $stockLevel, $childID)
    {
        if ($stockLevel->level == 5) {
            $childStock = stockLevelFive::find($childID);

        } elseif ($stockLevel->level == 4) {
            $childStock = stockLevelFour::find($childID);

        } elseif ($stockLevel->level == 3) {
            $childStock = stockLevelThree::find($childID);

        } elseif ($stockLevel->level == 2) {
            $childStock = stockLevelTwo::find($childID);

        } elseif ($stockLevel->level == 1) {
            $childStock = stockLevelOne::find($childID);

        }
        if ($childStock->active == 1) $stastus = 0;
        else $stastus = 1;
        $childStock->active = $stastus;
        $childStock->update();
        AuditReportsController::store('Stock Management', 'division level active status changed', "Edited by User", 0);
        return back();
    }

    public function updateLevel(Request $request, stockLevel $stockLevel, $childID)
    {
        $this->validate($request, [
            'name' => 'required',
            'manager_id' => 'numeric|required',
        ]);

        if ($stockLevel->level == 5) {
            $childStock = stockLevelFive::find($childID);
            $childStock->update($request->all());
        } elseif ($stockLevel->level == 4) {
            $childStock = stockLevelFour::find($childID);
            $childStock->update($request->all());
        } elseif ($stockLevel->level == 3) {
            $childStock = stockLevelThree::find($childID);
            $childStock->update($request->all());
        } elseif ($stockLevel->level == 2) {
            $childStock = stockLevelTwo::find($childID);
            $childStock->update($request->all());
        } elseif ($stockLevel->level == 1) {
            $childStock = stockLevelOne::find($childID);
            $childStock->update($request->all());
        }

        AuditReportsController::store('Stock Management', 'Stock level Informations Edited', "Edited by User", 0);
        return response()->json();
    }

    public function viewchildLevel($parentLevel, $parent_id)
    {
        //   $childLevel = null;
		//echo $parentLevel;
		//echo $parent_id;
		//die;
		
        $intCurrentLvl = 0;
        if ($parentLevel == 5) {
            $parentDiv = stockLevelFive::find($parent_id);
            $childStock = $parentDiv->childStock;
            $intCurrentLvl = 4;
			$newParent_id = 0;
        } elseif ($parentLevel == 4) {
            $parentDiv = stockLevelFour::find($parent_id);
            $childStock = $parentDiv->childStock;
            $intCurrentLvl = 3;
			$newParent_id =  stockLevelFour::where('id',$parent_id)->first();
			$newParent_id =  $newParent_id->parent_id;
			//
        } elseif ($parentLevel == 3) {
            $parentDiv = stockLevelThree::find($parent_id);
            $childStock = $parentDiv->childStock;
            $intCurrentLvl = 2;
			$newParent_id =  stockLevelThree::where('id',$parent_id)->first();
			$newParent_id =  $newParent_id->parent_id;
        } elseif ($parentLevel == 2) {
            $parentDiv = stockLevelTwo::find($parent_id);
            $childStock = $parentDiv->childStock;
            $intCurrentLvl = 1;
			$newParent_id =  stockLevelTwo::where('id',$parent_id)->first();
			$newParent_id =  $newParent_id->parent_id;
        } elseif ($parentLevel == 1) {
            $parentDiv = stockLevelOne::find($parent_id);
            $childStock = null;
            $intCurrentLvl = 0;
			$newParent_id =  stockLevelOne::where('id',$parent_id)->first();
			$newParent_id =  $newParent_id->parent_id;
        }
        $employees = HRPerson::where('status', 1)->get();
         $lowestactiveLvl = stockLevel::where('active', 1)->orderBy('level', 'asc')->limit(1)->get()->first()->level;
        if ($parentLevel > $lowestactiveLvl) {
            $childLevel = stockLevel::where('level', $parentLevel - 1)->get()->first();
            $curLvlChild = stockLevel::where('level', $childLevel->level - 1)->get()->first();
        }
		//echo $newParent_id;
		//die;
        $data['childStock'] = $childStock;
        $data['employees'] = $employees;
        $data['parentLevel'] = $parentLevel;
        $data['parentDiv'] = $parentDiv;
        $data['lowestactiveLvl'] = $lowestactiveLvl;
        $data['childLevel'] = $childLevel;
        $data['curLvlChild'] = $curLvlChild;
        $data['page_title'] = "Store Management";
        $data['page_description'] = "Stores";
        $data['breadcrumb'] = [
            ['title' => 'Stock Management', 'path' => '/stock/store_management', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Store Management', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Stock Management';
        $data['active_rib'] = 'Store Management';
		$newParent = $parentLevel + 1;
		if ($parentLevel < 5) $data['back'] = "/stock/child_setup/$newParent/$newParent_id";
		else $data['back'] = "/stock/store_management";
        AuditReportsController::store('Stock Management', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('stock.child_setup')->with($data);
    }


    public function addChild(Request $request, $parentLevel, $parent_id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $childData = $request->all();

        if ($parentLevel == 5) {
            $parentDiv = stockLevelFive::find($parent_id);
            $childDiv = new stockLevelFour($childData);
			$childDiv->manager_id = !empty($childData['manager_id']) ? $childData['manager_id'] : 0;
            $childDiv->division_level_id = 4;

        } elseif ($parentLevel == 4) {
            $parentDiv = stockLevelFour::find($parent_id);
            $childDiv = new stockLevelThree($childData);
			$childDiv->manager_id = !empty($childData['manager_id']) ? $childData['manager_id'] : 0;
            $childDiv->division_level_id = 3;
        } elseif ($parentLevel == 3) {
            $parentDiv = stockLevelThree::find($parent_id);
            $childDiv = new stockLevelTwo($childData);
			$childDiv->manager_id = !empty($childData['manager_id']) ? $childData['manager_id'] : 0;
            $childDiv->division_level_id = 2;
        } elseif ($parentLevel == 2) {
            $parentDiv = stockLevelTwo::find($parent_id);
            $childDiv = new stockLevelOne($childData);
            $childDiv->manager_id = !empty($childData['manager_id']) ? $childData['manager_id'] : 0;
            $childDiv->division_level_id = 1;
        } elseif ($parentLevel == 1) {
            $parentDiv = stockLevelOne::find($parent_id);
            $childDiv = null;
        }
        $childDiv->active = 1;
        $parentDiv->addChildStock($childDiv);

        // return $stockLevel;

        AuditReportsController::store('Stock Management', 'Employee Group Level Modified', "Actioned By User", 0);
    }

    public function updateChild(Request $request, $parentLevel, $childID)
    {

        $this->validate($request, [
            'manager_id' => 'required',
            'name' => 'required',
        ]);

        $childData = $request->all();


        if ($parentLevel == 5) {
            $childDiv = stockLevelFive::find($childID);
            $childDiv->update($request->all());
        } elseif ($parentLevel == 4) {
            $childDiv = stockLevelFour::find($childID);
            $childDiv->update($request->all());
        } elseif ($parentLevel == 3) {
            $childDiv = stockLevelThree::find($childID);
            $childDiv->update($request->all());
        } elseif ($parentLevel == 2) {
            $childDiv = stockLevelTwo::find($childID);
            $childDiv->update($request->all());
        } elseif ($parentLevel == 1) {
            $childDiv = stockLevelOne::find($childID);
            $childDiv->update($request->all());
        }

        AuditReportsController::store('Stock Management', 'Employee Group Level Modified', "Actioned By User", 0);
        return response()->json();

    }

    public function activateChild($parentLevel, $childID)
    {
        if ($parentLevel == 5) {
            $childDiv = stockLevelFive::find($childID);

        } elseif ($parentLevel == 4) {
            $childDiv = stockLevelFour::find($childID);
        } elseif ($parentLevel == 3) {
            $childDiv = stockLevelThree::find($childID);
        } elseif ($parentLevel == 2) {
            $childDiv = stockLevelTwo::find($childID);
        } elseif ($parentLevel == 1) {
            $childDiv = stockLevelOne::find($childID);
        }

        if ($childDiv->active == 1) $stastus = 0;
        else $stastus = 1;
        $childDiv->active = $stastus;
        $childDiv->update();
        AuditReportsController::store('Stock Management', 'division level active satus changed', "Edited by User", 0);
        return back();
    }
	public function updateGroupLevel(Request $request, stockLevel $groupLevel) {
        //validate name required if active
        $this->validate($request, [
            'name' => 'bail|required|min:2',
            'plural_name' => 'bail|required|min:2',
        ]);
        //save the changes
        $groupLevelData = $request->all();
        $groupLevel->update($groupLevelData);
        AuditReportsController::store('Stock Management', 'Stock Group Level Modified', "Actioned By User", 0);
    }
	
	public function activateGroupLevel(stockLevel $groupLevel) {
        if ($groupLevel->active == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $groupLevel->active = $stastus;
        $groupLevel->update();
        return back();
    }
}
