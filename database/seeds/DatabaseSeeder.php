<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
		    [
		        'username' => 'onedough83',
		        'first_name'=> 'Wondo',
		        'last_name'=>'Choung',
		        'company_id' =>1,
		        'email' => 'wondo@eyelevate.com',
		        'role_id' => '1',
		        'password' => bcrypt('0987poiu')
		    ]
		 ]);
    }
}
