<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactCompany extends Model
{
    //Specify the table name
    public $table = 'contact_companies';

    // Mass assignable fields
    protected $fillable = [
        'company_type', 'name', 'trading_as', 'registration_number', 'vat_number', 'tax_number', 'contact_person', 'cp_cell_number', 'cp_home_number', 'bee_score', 'bee_certificate_doc', 'comp_reg_doc', 'sector', 'phone_number', 'fax_number', 'email', 'phys_address', 'phys_city', 'phys_postal_code', 'phys_province', 'postal_address'
    ];

    //relationship between contact_company and contact person (contacts_contacts)
    public function employee() {
        return $this->hasMany(ContactPerson::class, 'company_id');
    }

    //Accessor to return the company's full  physical address
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
}