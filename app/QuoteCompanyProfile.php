<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class QuoteCompanyProfile extends Model
{
    //Specify the table name
    public $table = 'quote_company_profiles';

    // Mass assignable fields
    protected $fillable = [
        'division_id', 'division_level', 'registration_number', 'vat_number', 'bank_name', 'bank_branch_code',
        'bank_account_name', 'bank_account_number', 'validity_period', 'letter_head', 'status', 'authorisation_required',
        'email', 'phone_number', 'phys_address', 'phys_city', 'phys_postal_code', 'phys_province'
    ];

    //relationship quote company profile and each specific division level(one to many)
    public function divisionLevelGroup() {
        if ($this->division_level === 5) {
            return $this->belongsTo(DivisionLevelFive::class, 'division_id');
        }
        elseif ($this->division_level === 4) {
            return $this->belongsTo(DivisionLevelFour::class, 'division_id');
        }
        if ($this->division_level === 3) {
            return $this->belongsTo(DivisionLevelThree::class, 'division_id');
        }
        if ($this->division_level === 2) {
            return $this->belongsTo(DivisionLevelTwo::class, 'division_id');
        }
        if ($this->division_level === 1) {
            return $this->belongsTo(DivisionLevelOne::class, 'division_id');
        }
    }

    public function getLetterheadUrlAttribute() {
        return (!empty($this->letter_head)) ? Storage::disk('local')->url("letterheads/$this->letter_head") : null;
    }
}
