<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HRPerson extends Model
{
    use Uuids;

    /**
     * @var string[]
     */
    protected $hidden = [
        'id'
    ];

    //Specify the table name
    public $table = 'hr_people';

    // Mass assignable fields
    protected $fillable = [
        'first_name', 'surname', 'middle_name', 'maiden_name', 'aka', 'initial', 'email', 'cell_number',
        'phone_number', 'id_number', 'date_of_birth', 'passport_number', 'drivers_licence_number', 'drivers_licence_code',
        'proof_drive_permit', 'proof_drive_permit_exp_date', 'drivers_licence_exp_date', 'gender', 'own_transport', 'marital_status',
        'ethnicity', 'profile_pic', 'status', 'division_level_1', 'division_level_2', 'division_level_3', 'employee_number',
        'division_level_4', 'division_level_5', 'leave_profile', 'manager_id', 'second_manager_id', 'date_joined', 'date_left', 'role_id', 'position',

    ];


    //Many to many Relationship Between leavetype and Hr person
    public function leave_types()
    {
        return $this->belongsToMany('App\LeaveType', 'leave_credit', 'hr_id', 'leave_type_id')->withPivot('leave_balance');
    }

    //Relationship hr_person and user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //Relationship leave_application and hr people
    public function leaveAppmanId()
    {
        return $this->hasMany(leave_application::class, 'manager_id');
    }

    //Relationship leave_application and hr people
    public function leaveApphr()
    {
        return $this->hasMany(leave_application::class, 'hr_id');
    }

    //Relationship hr_person and user
    public function programme()
    {
        return $this->hasMany(programme::class, 'manager_id');
    }
    //relationship betwewn hr person and leave credit
//     public function leave_credit() {
//        return $this->hasMany(leave_credit::class, 'hr_id');
//    }

    public function rateUsers()
    {
        return $this->hasMany(cms_rating::class, 'user_id');
    }


    //Relationship hr_person (manager) and Division level group
    public function divisionLevelGroup()
    {
        return $this->hasOne(DivisionLevelGroup::class, 'manager_id');
    }

    //
    public function tickets()
    {
        # code...
        return $this->belongsTo(ticket::class, 'operator_id');
    }

    //Relationship hr person and job title
    public function jobTitle()
    {
        return $this->belongsTo(JobTitle::class, 'position');
    }

    //Relationship hr person and Roles
    public function hrRoles()
    {
        return $this->belongsTo(HRRoles::class, 'role_id');
    }


    public function HrPositions()
    {
        return $this->belongsTo(HRRoles::class, 'position');
    }

    //Relationship hr person and province
    public function province()
    {
        return $this->belongsTo(Province::class, 'res_province_id');
    }

    //Relationship hr person and province
    public function division()
    {
        return $this->belongsTo(DivisionLevelFive::class, 'division_level_5');
    }

    //Relationship hr person and province
    public function department()
    {
        return $this->belongsTo(DivisionLevelFour::class, 'division_level_4');
    }

    public function section()
    {
        return $this->belongsTo(DivisionLevelThree::class, 'division_level_3');
    }

    public function team()
    {
        return $this->belongsTo(DivisionLevelTwo::class, 'division_level_2');
    }

    //Relationship hr person and 360 person
    public function threeSixtyPeople()
    {
        return $this->hasMany(AppraisalThreeSixtyPerson::class, 'hr_id');
    }

    /**
     * Relationship between HRPerson Quotation
     *
     * @return
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'hr_person_id');
    }

    //Full Name accessor
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->surname;
    }

    //Rate my service full link accessor (with encrypted hr ID)
    public function getEncryptedRateMyServiceLinkAttribute()
    {
        return url('/rate-our-services/') . '/' . encrypt($this->id);
    }

    #
    public function business_cards()
    {
        return $this->hasOne(business_card::class, 'hr_id');
    }

    //Full Profile picture url accessor
    public function getProfilePicUrlAttribute()
    {
        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        return (!empty($this->profile_pic)) ? Storage::disk('local')->url("avatars/$this->profile_pic") : (($this->gender === 2) ? $f_silhouette : $m_silhouette);
    }

    //function to get people from a specific div level
    public static function peopleFronDivLvl($whereField, $divValue, $incInactive)
    {
        return HRPerson::where($whereField, $divValue)
            ->where(function ($query) use ($incInactive) {
                if ($incInactive == -1) {
                    $query->where('status', 1);
                }
            })->get()
            ->sortBy('full_name')
            ->pluck('id', 'full_name');
    }

    public static function getAllUsers()
    {
        return HRPerson::where('status', 1)->get();
    }

    public static function getManagerDetails($hrDetails)
    {
        return HRPerson::where(['id' => $hrDetails, 'status' => 1])
            ->select('first_name', 'surname', 'email', 'manager_id')
            ->first();
    } 
	// get manager names
	public static function getManagername($id)
    {
        return HRPerson::where(['id' => $id, 'status' => 1])
            ->select('first_name', 'surname', 'email')
            ->first();
    } 
	// get second manager names
	public static function getSecondManagername($id)
    {
        return HRPerson::where(['id' => $id, 'status' => 1])
            ->select('first_name', 'surname', 'email')
            ->first();
    }

    public static function getUserDetails($employeeNumber)
    {
        return HRPerson::where(['employee_number' => $employeeNumber, 'status' => 1])
            ->select('id', 'first_name', 'surname', 'email', 'employee_number')
            ->first();
    }

    public static function getEmployeeNumber()
    {
        $users = HRPerson::where(
            'status', 1
        )->pluck('employee_number');

        return $filteredCollection = $users->filter();
    }

    /**
     * @param $first_name
     * @param $surname
     * @return string
     */
    public static function getFullName($first_name, $surname): string
    {
        return $first_name . ' ' . $surname;
    }

//     $STATUS_SELECT = [
//        '1' => 'Active',
//        '0' => 'De-Activated',
//    ];
//

    public static function STATUS_SELECT(): \Illuminate\Support\Collection
    {
        $status = [
            '1' => 'Active',
            '0' => 'De-Activated',
        ];

        return collect($status);
    }


    public static function getAllEmployeesByStatus($status = 1, $user, $collector)
    {

        $query = HRPerson::select(
            'hr_people.*',
            'hp.first_name as manager_first_name',
            'hp.surname as manager_surname',
            'd4.name as department',
            'd5.name as division',
            'provinces.name as province'
        )
            ->whereHas('user', function ($query) use ($user, $status) {

                if ($user > 0) {
                    $query->where('id', $user);
                    $query->whereIn('type', [1, 3]);
                    $query->where('status', $status);
                } elseif ($status == null) {
                    $query->whereIn('type', [1, 3]);
                    $query->where('status', 1);
                } else {
                    $query->whereIn('type', [1, 3]);
                    $query->where('status', $status);
                }
            })
            ->leftJoin(
                'hr_people as hp',
                'hr_people.manager_id',
                '=',
                'hp.id'
            )
            ->leftJoin(
                'provinces',
                'hr_people.res_province_id',
                '=',
                'provinces.id'
            )
            ->leftJoin(
                'division_level_fives as d5',
                'hr_people.division_level_5',
                '=',
                'd5.id'
            )
            ->leftJoin(
                'division_level_fours as d4',
                'hr_people.division_level_4',
                '=',
                'd4.id'
            )
            ->with('jobTitle')
            ->$collector();

        return $query;
    }

    // get employee details

    public static function getEmployee($user)
    {

        $query = HRPerson::select(
            'hr_people.*'
        )
        ->where('hr_people.id', $user)
        ->with('jobTitle','province','division','department')
        ->first();

        return $query;
    }


    public static function getDirectorDetails($id)
    {
        return HRPerson::with('HrPositions')
            ->where('user_id', $id)
            ->first();
    }


    public static function getUsersFromTeam($dv2)
    {
        return HRPerson::select(
            'hr_people.*'
        )
            ->where('hr_people.division_level_4', $dv2)
            ->with('jobTitle','section', 'team')
            ->get();
//        section
//team

    }


}
