<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ZipcodeRequest extends Model
{
    use SoftDeletes;

    static public function getBackgroundColor($idx){
    	$backgroundColor = [
            "#FF6384",
            "#4BC0C0",
            "#FFCE56",
            "#E7E9ED",
            "#36A2EB",
            "#1ABC9C",
            "#E67E22",
            "#F1948A",
            "#884EA0",
            "#D4EFDF",
            "#AED6F1",
            "#7E5109",
            "#2471A3",
            "#F9E79F",
            "#EBDEF0",
            "#7D6608",
            "#58D68D",
            "#117A65",
            "#9C640C",
            "#E59866"
        ];

        return $backgroundColor[$idx];
    }
}
