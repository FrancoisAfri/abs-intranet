<?php

namespace App\Http\Controllers;

use App\DivisionLevelFive;
use App\DivisionLevelFour;
use App\DivisionLevelOne;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\HRPerson;
use Illuminate\Http\Request;

use App\Http\Requests;

class DropDownAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //load division level 5, 4, 3, 2, or 1
    public function divLevelGroupDD(Request $request) {
        $divLevel = (int) $request->input('div_level');
        $parentID = $request->input('parent_id');
        $incInactive = !empty($request->input('inc_complete')) ? $request->input('inc_complete') : -1;
        $loadAll = $request->input('load_all');
        $divisions = [];
        if ($divLevel === 5) {
            $divisions = DivisionLevelFive::where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('active', 1);
                }
            })->get()
                ->sortBy('name')
                ->pluck('id', 'name');
        }
        elseif ($divLevel === 4) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelFour::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelFour::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('active', 1);
                    }
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 3) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelThree::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelThree::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('active', 1);
                    }
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 2) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelTwo::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelTwo::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('active', 1);
                    }
                })->get()
                    ->sortBy('name')
                    ->pluck('id', 'name');
            }
        }
        elseif ($divLevel === 1) {
            if ($parentID > 0 && $loadAll == -1) $divisions = DivisionLevelOne::divsFromParent($parentID, $incInactive);
            elseif ($loadAll == 1) {
                $divisions = DivisionLevelOne::where(function ($query) use($incInactive) {
                    if ($incInactive == -1) {
                        $query->where('active', 1);
                    }
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
                $whereField = 'group_level_five_id';
                break;
            case 4:
                $whereField = 'group_level_four_id';
                break;
            case 3:
                $whereField = 'group_level_three_id';
                break;
            case 2:
                $whereField = 'group_level_two_id';
                break;
            case 1:
                $whereField = 'group_level_one_id';
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
}
