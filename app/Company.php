<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public static function getCompany(){

    	return [1=>'Montlake',2=>'Roosevelt'];
    }

    public static function getStarch(){

    	return [1=>'None',2=>'Light',3=>'Medium',4=>'Heavy'];
    }

    public static function getShirt(){
    	return [1=>'Hanger',2=>'Box/Folded'];
    }

    public static function getDelivery(){
    	return [false=>'No', true=>'Yes'];
    }

    public static function getAccount(){
    	return [false=>'No', true=>'Yes'];

    }
}
