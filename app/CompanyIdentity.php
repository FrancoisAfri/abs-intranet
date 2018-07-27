<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CompanyIdentity extends Model
{
    //Specify the table name
    public $table = 'company_identities';

    //Mass assignable fields
    protected $fillable = [
        'company_name', 'full_company_name', 'header_name_bold', 'header_name_regular'
		, 'header_acronym_bold','header_acronym_regular', 'company_logo'
		, 'sys_theme_color', 'mailing_name', 'mailing_address', 'support_email'
		,'company_website','password_expiring_month','system_background_image','login_background_image'
    ];

    /**
     * Getter: get the location of the logo on the server.
     *
     * @return string
     */
    public function getCompanyLogoUrlAttribute()
    {
        if (! empty($this->company_logo)) return Storage::disk('local')->url("logos/$this->company_logo");
        else return '';
    } 
	public function getSystemBackgroundImageUrlAttribute()
    {
        if (! empty($this->system_background_image)) return Storage::disk('local')->url("logos/$this->system_background_image");
        else return '';
    } 
	public function getLoginbackgroundImageUrlAttribute()
    {
        if (! empty($this->login_background_image)) return Storage::disk('local')->url("logos/$this->login_background_image");
        else return '';
    }

    /**
     * Static helper function that return the company identity data from the setup or the default values.
     *
     * @param string $settingName
     * @return string
     * @return array
     */
    public static function systemSettings($settingName = null)
    {
        //$companyDetails = CompanyIdentity::first();
        $companyDetails = (Schema::hasTable('company_identities')) ? CompanyIdentity::first() : null;
        $settings = [];
        $settings['header_name_bold'] = ($companyDetails && $companyDetails->header_name_bold) ? $companyDetails->header_name_bold : 'Afrixcel';
        $settings['header_name_regular'] = ($companyDetails && $companyDetails->header_name_regular) ? $companyDetails->header_name_regular : 'BS';
        $settings['header_acronym_bold'] = ($companyDetails && $companyDetails->header_acronym_bold) ? $companyDetails->header_acronym_bold : 'A';
        $settings['header_acronym_regular'] = ($companyDetails && $companyDetails->header_acronym_regular) ? $companyDetails->header_acronym_regular : 'BS';
        $settings['sys_theme_color'] = ($companyDetails && $companyDetails->sys_theme_color) ? $companyDetails->sys_theme_color : 'blue';
        $settings['mailing_address'] = ($companyDetails && $companyDetails->mailing_address) ? $companyDetails->mailing_address : 'noreply@afrixcel.co.za';
        $settings['mailing_name'] = ($companyDetails && $companyDetails->mailing_name) ? $companyDetails->mailing_name : 'Afrixcel Support';
        $settings['company_name'] = ($companyDetails && $companyDetails->company_name) ? $companyDetails->company_name : 'Afrixcel';
        $settings['full_company_name'] = ($companyDetails && $companyDetails->full_company_name) ? $companyDetails->full_company_name : 'Afrixcel Business Solutions (PTY) LTD';
        $settings['support_email'] = ($companyDetails && $companyDetails->support_email) ? $companyDetails->support_email : 'support@afrixcel.co.za';
        $settings['company_logo_url'] = ($companyDetails && $companyDetails->company_logo_url) ? $companyDetails->company_logo_url : Storage::disk('local')->url('logos/logo.jpg');
        $settings['system_background_image_url'] = ($companyDetails && $companyDetails->system_background_image_url) ? $companyDetails->system_background_image_url : '';
        $settings['login_background_image_url'] = ($companyDetails && $companyDetails->login_background_image_url) ? $companyDetails->login_background_image_url : '';
        if ($settingName != null) {
            if (array_key_exists($settingName, $settings)) return $settings[$settingName];
            else return null;
        }
        else return $settings;
    }
}
