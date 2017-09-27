<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactCompany extends Model
{
    //Specify the table name
    public $table = 'contact_companies';

    // Mass assignable fields
    protected $fillable = [
        'company_type', 'name', 'trading_as', 'registration_number', 'vat_number', 
		'tax_number', 'contact_person', 'cp_cell_number', 'cp_home_number', 
		'bee_score', 'bee_certificate_doc', 'comp_reg_doc', 'sector', 'phone_number',
		'fax_number', 'email', 'phys_address', 'phys_city', 'phys_postal_code',
        'phys_province', 'postal_address', 'account_number','estimated_spent','domain_name',
        'division_level_1', 'division_level_2', 'division_level_3','division_level_4', 'division_level_5'
    ];

    /**
     * Relationship between Contact Company and contact person (contacts_contacts)
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees() {
        return $this->hasMany(ContactPerson::class, 'company_id');
    }

    /**
     * Relationship between Contact Company and Quotations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'company_id');
    }

    /**
     * Relationship between Contact Company and Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts()
    {
        return $this->hasMany(CRMAccount::class, 'company_id');
    }
/**
     * Relationship between Contact Company and Meetings
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meetings()
    {
        return $this->hasMany(MeetingMinutes::class, 'company_id');
    }

    /**
     * Relationship hr_person (manager) and Division level group
     *
     * @return \Illuminate\Database\Eloquent\Relations\Hasone
     */
    public function divisionLevelGroup() {
        return $this->hasOne(DivisionLevelGroup::class, 'manager_id');
    }

    /**
     * Accessor to return the company's full  physical address
     *
     * @return String
     */
    public function getFullPhysAddressAttribute () {
        $address = "";
        $address .= (!empty($this->phys_address)) ? $this->phys_address : "";
        $address .= (!empty($this->phys_city)) ? ", \n" . $this->phys_city : "";
        $address .= (!empty($this->phys_postal_code)) ? " \n" . $this->phys_postal_code : "";
        if(!empty($this->phys_province) && $this->phys_province > 0) {
            $province = Province::find($this->phys_province);
            $address .= " \n" . $province->name;
        }
        return $address;
    }

    ##
     //function to get people from a specific div level
    public static function peopleFronDivLvl($whereField, $divValue, $incInactive) {
        $hrPeople = HRPerson::where($whereField, $divValue)
            ->where(function ($query) use($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
            ->sortBy('full_name')
            ->pluck('id', 'full_name');
        return $hrPeople;
    }
}
