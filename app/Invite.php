<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invite extends Model
{
    use SoftDeletes;
    public $table = "invites";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','company_id','mobile_number',
    ];

    public function users(){
        return $this->belongsTo('App\User','user_id');
    }

    public function companies() {
        return $this->belongsTo('App\Company','company_id','id');
    }
}
