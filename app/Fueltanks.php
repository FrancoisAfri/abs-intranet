<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fueltanks extends Model
{
    public $table = 'fuel_tanks';
    // Mass assignable fields
    protected $fillable = ['division_level_1',
    'division_level_2','division_level_3','division_level_4','division_level_5','tank_name','tank_location',
    'tank_description','tank_capacity','tank_manager','status','current_fuel_litres','available_litres'];
}
