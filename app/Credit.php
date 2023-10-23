<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Credit extends Model
{
    use SoftDeletes;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function prepareReason(){
    	return [
    		''=>'Select Reason',
    		'Customer Dissatisfaction'=>'Customer Dissatisfaction',
    		'Gift Certificate'=>'Gift Certificate',
    		'Human Error' => 'Human Error',
    		'Other'=>'Other'
    	];
    }

    public static function prepareCreditHistory($id) {
        $customers = User::find($id);
        $customer_first_name = $customers->first_name;
        $customer_last_name = $customers->last_name;
        $customer_name = '('.$id.') '.ucFirst($customer_first_name).' '.ucFirst($customer_last_name);
        $credits = Credit::where('customer_id',$id)->get();
        if (count($credits) > 0) {
            foreach ($credits as $key => $value) {
                if (isset($credits[$key]['employee_id'])) {
                    $employee_id = $value['employee_id'];
                    $employees = User::find($employee_id);
                    $employee_name = '('.$employee_id.') '.ucFirst($employees->first_name).' '.ucFirst($employees->last_name);
                    $credits[$key]['employee_name'] = $employee_name;
                }

                if (isset($credits[$key]['customer_id'])) {
                    $credits[$key]['customer_name'] = $customer_name;
                }

                if (isset($credits[$key]['created_at'])) {
                    $credits[$key]['created'] = date('D n/d/Y g:ia',strtotime($value['created_at']));
                }
            }
        }

        return $credits;
    }
}
