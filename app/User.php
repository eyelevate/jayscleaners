<?php

namespace App;
use App\Job;
use App\User;
use App\Invoice;
use App\Custid;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use SoftDeletes;
    public $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','phone', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function custids()
    {
        return $this->hasMany(Custid::class,'customer_id','id');
    }


    static public function role($role_id) {
        switch ($role_id) {
            case 1: // Superadmin
                return 'Superadmin';
            break;

            case 2: // Admin
                return 'Admin';
            break;

            case 3: // Employee
                return 'Employee';
            break;

            case 4: 

            break;

            case 5: // member
                return 'Member';
            break;
        }
    }



}
