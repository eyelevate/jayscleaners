<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
	static public function dump($results) {
		if(isset($results)) {
			echo '<pre>';
			print_r($results);
			echo '</pre>';
		}

		return false;
	}

	static public function strip($value) {
		$string = str_replace(' ', '', preg_replace("/[^a-z0-9.]+/i", "", $value));
		return $string;
	}

	static public function formatPhoneString($data) {
		return "(".substr($data, 0, 3).") ".substr($data, 3, 3)."-".substr($data,6);
	}

	static public function states() {
		return $states = [''=>'Select State',
						'AL'=>'Alabama',
						'AK'=>'Alaska',
					    'AZ'=>'Arizona',
					    'AR'=>'Arkansas',
					    'CA'=>'California',
					    'CO'=>'Colorado',
					    'CT'=>'Connecticut',
					    'DE'=>'Delaware',
					    'DC'=>'District of Columbia',
					    'FL'=>'Florida',
					    'GA'=>'Georgia',
					    'HI'=>'Hawaii',
					    'ID'=>'Idaho',
					    'IL'=>'Illinois',
					    'IN'=>'Indiana',
					    'IA'=>'Iowa',
					    'KS'=>'Kansas',
					    'KY'=>'Kentucky',
					    'LA'=>'Louisiana',
					    'ME'=>'Maine',
					    'MD'=>'Maryland',
					    'MA'=>'Massachusetts',
					    'MI'=>'Michigan',
					    'MN'=>'Minnesota',
					    'MS'=>'Mississippi',
					    'MO'=>'Missouri',
					    'MT'=>'Montana',
					    'NE'=>'Nebraska',
					    'NV'=>'Nevada',
					    'NH'=>'New Hampshire',
					    'NJ'=>'New Jersey',
					    'NM'=>'New Mexico',
					    'NY'=>'New York',
					    'NC'=>'North Carolina',
					    'ND'=>'North Dakota',
					    'OH'=>'Ohio',
					    'OK'=>'Oklahoma',
					    'OR'=>'Oregon',
					    'PA'=>'Pennsylvania',
					    'RI'=>'Rhode Island',
					    'SC'=>'South Carolina',
					    'SD'=>'South Dakota',
					    'TN'=>'Tennessee',
					    'TX'=>'Texas',
					    'UT'=>'Utah',
					    'VT'=>'Vermont',
					    'VA'=>'Virginia',
					    'WA'=>'Washington',
					    'WV'=>'West Virginia',
					    'WI'=>'Wisconsin',
					    'WY'=>'Wyoming'];
	}

	public static function getUserIP() {
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP)) {
	        $ip = $client;
	    } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
	        $ip = $forward;
	    } else {
	        $ip = $remote;
	    }

	    return $ip;
	}


}
