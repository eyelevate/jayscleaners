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

    static public function getRequestDataset() {
    	$request_dataset = [];
    	$zipcodes = ZipcodeRequest::where('status',1)->get();

		$l = [];
		if (count($zipcodes) > 0) {
			foreach ($zipcodes as $zipcode) {
				$l[$zipcode->zipcode] = $zipcode->zipcode;

			}
		}
		ksort($l);
		$label = [];
		if (count($l) > 0) {
			foreach ($l as $key => $value) {
				array_push($label,$value);
			}
		}

		$count_label = [];
		if (count($label) > 0) {
			foreach ($label as $key => $value) {
				$search = ZipcodeRequest::where('zipcode',$value)->get();
				$count_label[$value] = count($search);
			}
		}

		arsort($count_label);

		$label = [];
		if (count($count_label) > 0) {
			foreach ($count_label as $key => $value) {
				array_push($label,$key);
			}
		}		


		$datasets = [];
		$data = [];
		$background_colors = [];
		if (count($label) > 0) {

			$idx = -1;
			foreach ($label as $key => $value) {
				$idx++;
				$search = ZipcodeRequest::where('zipcode',$value)->get();
				array_push($data,count($search));
				array_push($background_colors, ZipcodeRequest::getBackgroundColor($idx));
			}
			$datasets =['data'=>$data,'backgroundColor'=>$background_colors];
		}

		$request_dataset['labels'] = $label;
		$request_dataset['datasets'] = $datasets;

		return $request_dataset;
    }
}
