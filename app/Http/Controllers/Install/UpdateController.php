<?php

namespace App\Http\Controllers\Install;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Artisan;


class UpdateController extends Controller
{	
	public function __construct()
    {	
		//Constructor
    }
	
	public function update_migration(){
		 Artisan::call('migrate', ['--force' => true]);
		 echo "Migration Updated Sucessfully";
	} 
}
