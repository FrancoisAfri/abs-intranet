<?php

namespace App\Http\Controllers;

use App\ContactPerson;
use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelOne;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\HRPerson;
use App\vehiclemodel;
use App\jobcart_parts;
use App\product_products;
use App\appraisalKpas;
use App\stockLevelFive;
use App\stockLevelFour;
use App\stockLevelThree;
use App\stockLevelTwo;
use App\stockLevelOne;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class DropDownAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //load division level 5, 4, 3, 2, or 1
    public function divLevelGroupDD(Request $request) {
        $divLevel = (int) $request->input('div_level');
        $divHeadSpecific = (int) $request->input('div_head_specific');
        $parentID = $request->input('parent_id');
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $divisions = [];
        $managerID = ($divHeadSpecific == 1) ? Auth::user()->person->id : 0;
        if ($divLevel === 5) {
            $divisions = DivisionLevelFive::where(function ($query) use($incInactive, $managerID) {
                if ($incInactive == -1) $query->where('active', 1);
                if ($managerID > 0) $query->where('manager_id', $managerID);
            })->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        elseif ($divLevel === 4) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelFour::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelFour::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 3) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelThree::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelThree::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 2) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelTwo::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelTwo::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 1) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelOne::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelOne::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        
        return $divisions;
    }

    //Load HR People from specific division level
    public function hrPeopleDD(Request $request) {
        $divLevel = (int) $request->input('div_level');
        $divValue = (int) $request->input('div_val');
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $hrPeople = [];
        switch ($divLevel) {
            case 5:
                $whereField = 'division_level_5';
                break;
            case 4:
                $whereField = 'division_level_4';
                break;
            case 3:
                $whereField = 'division_level_3';
                break;
            case 2:
                $whereField = 'division_level_2';
                break;
            case 1:
                $whereField = 'division_level_1';
                break;
            default:
                $whereField = '';
        }
        if ($divLevel > 0 && $whereField != '' && $loadAll == -1) $hrPeople = HRPerson::peopleFronDivLvl($whereField, $divValue, $incInactive);
        elseif ($loadAll == 1) {
            $hrPeople = HRPerson::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('status', 1);
                    }
                })->get()
                ->sortBy('full_name')
                ->pluck('id', 'full_name');
        }

        return $hrPeople;
    }
	
	//Load KPAs from specific category
    public function kpaDD(Request $request) {
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $kpas = [];
        if ($loadAll == -1) $kpas = appraisalKpas::kpaFronCat('category_id', $divValue, $incInactive);
        elseif ($loadAll == 1) {
            $kpas = appraisalKpas::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('status', 1);
                    }
                })->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }

        return $kpas;
    }

    //Load HR People from specific division level
    public function contactPeopleDD(Request $request) {
        $companyID = (int) $request->input('company_id');
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $contactPeople = [];
        if ($loadAll == -1) $contactPeople = ContactPerson::peopleFromCompany('company_id', $companyID, $incInactive);
        elseif ($loadAll == 1) {
            $contactPeople = ContactPerson::where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
                ->sortBy('full_name')
                ->pluck('id', 'full_name');
        }

        return $contactPeople;
    }

     public function vehiclemomdeDDID(Request $request) {
          $makeID = (int) $request->input('vehiclemake_id');
          $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
          $loadAll = $request->input('load_all');
          $model = [];
          if ($loadAll == -1) $model = vehiclemodel::movhedels('vehiclemake_id', $makeID, $incInactive);
          elseif ($loadAll == 1) {
              $model = vehiclemodel::where(function ($query) use($incInactive) {
                  if ($incInactive == -1) {
                      $query->where('status', 1);
                  }
              })->get()
                  ->sortBy('id')
                  ->pluck('id', 'name');
          }
          return $model;
      }

      public function jobcategorymomdelDDID(Request $request) {
          $jobcategoryID = (int) $request->input('category_id');
          $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
          $loadAll = $request->input('load_all');
          $model = [];
          if ($loadAll == -1) $model = jobcart_parts::jobcardmodels('category_id', $jobcategoryID, $incInactive);
          elseif ($loadAll == 1) {
              $model = jobcart_parts::where(function ($query) use($incInactive) {
                  if ($incInactive == -1) {
                      $query->where('status', 1);
                  }
              })->get()
                  ->sortBy('id')
                  ->pluck('id', 'name' ,'no_of_parts_available');
          }

          return $model;
      }
      
    public function productCategoryDDID(Request $request){      
		$productbcategoryID = (int) $request->input('category_id');
		$incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
		$loadAll = $request->input('load_all');
		$products = [];
		if ($loadAll == -1) $products = product_products::movproductCategory('category_id', $productbcategoryID, $incInactive);
		 elseif ($loadAll == 1) {
			$products = product_products::where(function ($query) use($incInactive) {
				if ($incInactive == -1) {
					  $query->where('status', 1);
				}
			})->get()
				->sortBy('id')
				->pluck('id', 'name');
		}
		return $products;
    }
	
	//load stock level 5, 4, 3, 2, or 1
    public function stockLevelGroupDD(Request $request) {
        $divLevel = (int) $request->input('div_level');
        $divHeadSpecific = (int) $request->input('div_head_specific');
        $parentID = $request->input('parent_id');
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $stocks = [];
        $managerID = ($divHeadSpecific == 1) ? Auth::user()->person->id : 0;
        if ($divLevel === 5) {
            $stocks = stockLevelFive::where(function ($query) use($incInactive, $managerID) {
                if ($incInactive == -1) $query->where('active', 1);
                if ($managerID > 0) $query->where('manager_id', $managerID);
            })->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        elseif ($divLevel === 4) {
            if ($parentID > 0 && $loadAll == -1) $stocks = stockLevelFour::stockFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $stocks = stockLevelFour::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 3) {
            if ($parentID > 0 && $loadAll == -1) $stocks = stockLevelThree::stockFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $stocks = stockLevelThree::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 2) {
            if ($parentID > 0 && $loadAll == -1) $stocks = stockLevelTwo::stockFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $stocks = stockLevelTwo::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 1) {
            if ($parentID > 0 && $loadAll == -1) $stocks = stockLevelOne::stockFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $stocks = stockLevelOne::where(function ($query) use($incInactive, $managerID) {
                    if ($incInactive == -1) $query->where('active', 1);
                    if ($managerID > 0) $query->where('manager_id', $managerID);
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        
        return $stocks;
    }
}
