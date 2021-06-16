<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\CustomField;
use App\Http\Controllers\Controller;
use App\Setting;
use Carbon\Carbon;
use DB;
use App\Utilities\PHPMySQLBackup;

class UtilityController extends Controller
{
    /**
     * Show the Settings Page.
     *
     * @return Response
     */

	public function __construct(){
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
	} 
	 
    public function settings($store = '', Request $request)
    {
		if($store == ''){
		   $data = array();
		   $data['customfields'] = CustomField::all()->sortByDesc("id");
           return view('backend.administration.general_settings.settings',$data);
        }else{	   
		    foreach($_POST as $key => $value){
				 if($key == "_token"){
					 continue;
				 }
				 
				 $data = array();
				 $data['value'] = $value; 
				 $data['updated_at'] = Carbon::now();
				 if(Setting::where('name', $key)->exists()){				
					Setting::where('name','=',$key)->update($data);			
				 }else{
					$data['name'] = $key; 
					$data['created_at'] = Carbon::now();
					Setting::insert($data); 
				 }
		    } //End Loop
			if(! $request->ajax()){
			   return redirect('admin/administration/general_settings')->with('success', _lang('Saved sucessfully'));
			}else{
			   return response()->json(['result'=>'success','action'=>'update','message'=>_lang('Saved sucessfully')]);
			}
			//return redirect('administration/general_settings')->with('success',_lang('Saved Sucessfully'));
		}
	}
	
	public function update_theme_option($store="",Request $request)
    {		
	    
		foreach($_POST as $key => $value){
			 if($key == "_token"){
				 continue;
			 }
			 
			 $data = array();
			 $data['value'] = is_array($value) ? serialize($value) : $value; 
			 $data['updated_at'] = Carbon::now();
			 if(Setting::where('name', $key)->exists()){				
				Setting::where('name','=',$key)->update($data);			
			 }else{
				$data['name'] = $key; 
				$data['created_at'] = Carbon::now();
				Setting::insert($data); 
			 }

		} //End $_POST Loop
		
		foreach($_FILES as $key => $value){
		   $this->upload_file($key,$request);
		}
		
		
		if(! $request->ajax()){
		   return back()->with('success', _lang('Saved sucessfully'));
		}else{
		   return response()->json(['result'=>'success','action'=>'update','message'=>_lang('Saved sucessfully')]);
		}
	}
	
	
	public function upload_logo(Request $request){
		$this->validate($request, [
			'logo' => 'required|image|mimes:jpeg,png,jpg|max:8192',
		]);

		if ($request->hasFile('logo')) {
			$image = $request->file('logo');
			$name = 'logo.'.$image->getClientOriginalExtension();
			$destinationPath = public_path('/uploads');
			$image->move($destinationPath, $name);

			$data = array();
			$data['value'] = $name; 
			$data['updated_at'] = Carbon::now();
			
			if(Setting::where('name', "logo")->exists()){				
				Setting::where('name','=',"logo")->update($data);			
			}else{
				$data['name'] = "logo"; 
				$data['created_at'] = Carbon::now();
				Setting::insert($data); 
			}
			
			if(! $request->ajax()){
			   return redirect('admin/administration/general_settings')->with('success', _lang('Saved sucessfully'));
			}else{
			   return response()->json(['result'=>'success','action'=>'update','message'=>_lang('Logo Upload successfully')]);
			}

		}
	}
	
	public function upload_file($file_name,Request $request){

		if ($request->hasFile($file_name)) {
			$file = $request->file($file_name);
			$name = 'file_'.time().".".$file->getClientOriginalExtension();
			$destinationPath = public_path('/uploads/media');
			$file->move($destinationPath, $name);

			$data = array();
			$data['value'] = $name; 
			$data['updated_at'] = Carbon::now();
			
			if(Setting::where('name', $file_name)->exists()){				
				Setting::where('name','=',$file_name)->update($data);			
			}else{
				$data['name'] = $file_name; 
				$data['created_at'] = Carbon::now();
				Setting::insert($data); 
			}	
		}
	}

	public function message_template(){
		return view('backend.administration.mesage_template');
	}
	
	
	public function backup_database(){
		@ini_set('max_execution_time', 0);
		@set_time_limit(0);
			
		$return = "";
		$database = 'Tables_in_'.DB::getDatabaseName();
		$tables = array();
		$result = DB::select("SHOW TABLES");

		foreach($result as $table){
			$tables[] = $table->$database;
		}


		//loop through the tables
		foreach($tables as $table){			
			$return .= "DROP TABLE IF EXISTS $table;";

			$result2 = DB::select("SHOW CREATE TABLE $table");
			$row2 = $result2[0]->{'Create Table'};

			$return .= "\n\n".$row2.";\n\n";
			
			$result = DB::select("SELECT * FROM $table");

			foreach($result as $row){	
				$return .= "INSERT INTO $table VALUES(";
				foreach($row as $key=>$val){	
					$return .= "'".addslashes($val)."'," ;	
				}
				$return = substr_replace($return, "", -1);
				$return .= ");\n";
			}
   
			$return .= "\n\n\n";
		}

		//save file
		$file = 'public/backup/DB-BACKUP-'.time().'.sql';
		$handle = fopen($file,'w+');
		fwrite($handle,$return);
		fclose($handle);
		
		return response()->download($file);
		return back()->with('success', _lang('Backup Created Sucessfully'));		
	}
	
}