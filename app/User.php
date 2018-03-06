<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'password_changed_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'password_changed_at'
    ];

    /**
     * The relationships between user and hr_person / contacts_contacts.
     *
     * @return array HRPerson|ContactPerson hasOne
     */
    public function person()
    {
        if ($this->type === 1 || $this->type === 3) { //dev and hr person
            return $this->hasOne(HRPerson::class, 'user_id');
        } elseif ($this->type === 2) { //contact person
            return $this->hasOne(ContactPerson::class, 'user_id');
        } elseif ($this->type === 4) { //educator
            return $this->hasOne(educator::class, 'user_id');
        }
    }

    /**
     * The function to save user's hr / contacts records.
     *
     * @param HRPerson|ContactPerson $person
     * @return HRPerson|ContactPerson saved person
     */
    public function addPerson($person)
    {
        return $this->person()->save($person);
    }
}
